<?php

namespace App\GraphQL;

use App\Models\Product;
use App\Models\Category;
use Exception;

class QueryResolver
{
    public function __construct(
        private Product $product,
        private Category $category
    ) {}

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
}