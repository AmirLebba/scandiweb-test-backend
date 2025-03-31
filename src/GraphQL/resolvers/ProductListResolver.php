<?php

namespace App\GraphQL\Resolvers;

use App\Models\Product;
use Exception;

class ProductListResolver
{
    private $product;

    

    public function getProducts($root, array $args)
    {
        try {
            return $this->product->getAllProducts($args['categoryId'] ?? null);
        } catch (Exception $e) {
            error_log("Product list error: " . $e->getMessage());
            return ['error' => 'Failed to fetch products'];
        }
    }
}