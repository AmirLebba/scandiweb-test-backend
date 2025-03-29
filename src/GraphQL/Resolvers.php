<?php

namespace App\GraphQL;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Attribute;
use App\Validators\OrderValidator;
use Exception;

class Resolvers
{
    private Product $product;
    private Category $category;
    private Order $order;
    private Attribute $attribute;

    public function __construct()
    {
        $db = \App\Config\Database::getConnection();

        $this->product = new Product($db);
        $this->category = new Category($db);
        $this->attribute = new Attribute($db);

        $validator = new OrderValidator($this->product); 
        $this->order = new Order($db, $validator);
    }

    public function getProducts($root, $args)
    {
        try {
            return $this->product->getAllProducts($args['categoryId'] ?? null);
        } catch (Exception $e) {
            error_log("Product fetch error: " . $e->getMessage());
            return ['error' => 'Failed to fetch products'];
        }
    }

    public function getProductById($root, $args)
    {
        try {
            $product = $this->product->getProductById($args['id']);
            return $product ?? ['error' => 'Product not found'];
        } catch (Exception $e) {
            error_log("Product fetch error: " . $e->getMessage());
            return ['error' => 'Failed to fetch product'];
        }
    }

    public function getCategories()
    {
        try {
            return $this->category->getAllCategories();
        } catch (Exception $e) {
            error_log("Category fetch error: " . $e->getMessage());
            return ['error' => 'Failed to fetch categories'];
        }
    }

    public function placeOrder($root, array $args): array
    {
        try {
            if (empty($args['items'])) {
                return ['success' => false, 'message' => 'No items in order'];
            }

            return $this->order->placeOrder($args['items']);
        } catch (Exception $e) {
            error_log("Order placement error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Order processing failed'];
        }
    }
}