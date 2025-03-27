<?php

namespace App\GraphQL;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Attribute;
use Exception;

class Resolvers
{
    private $product;
    private $category;
    private $order;
    private $attribute;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
        $this->order = new Order();
        $this->attribute = new Attribute();
    }

    public function getProducts($root, $args)
    {
        try {
            return $this->product->getAllProducts($args['categoryId'] ?? null);
        } catch (Exception $e) {
            return ['error' => 'Failed to fetch products: ' . $e->getMessage()];
        }
    }

    public function getProductById($root, $args)
    {
        try {
            return $this->product->getProductById($args['id']) ?? ['error' => 'Product not found'];
        } catch (Exception $e) {
            return ['error' => 'Failed to fetch product: ' . $e->getMessage()];
        }
    }

    public function getCategories()
    {
        try {
            return $this->category->getAllCategories();
        } catch (Exception $e) {
            return ['error' => 'Failed to fetch categories: ' . $e->getMessage()];
        }
    }

    // In your resolver class
    public function placeOrder($root, array $args): array
    {
        return $this->order->placeOrder($args['items']);
    }
}