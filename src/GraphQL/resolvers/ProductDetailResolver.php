<?php

namespace App\GraphQL\Resolvers;

use App\Models\Product;
use Exception;

class ProductDetailResolver
{
    private $product;

   

    public function getProductById($root, array $args)
    {
        try {
            $product = $this->product->getProductById($args['id']);
            return $product ?? ['error' => 'Product not found'];
        } catch (Exception $e) {
            error_log("Product detail error: " . $e->getMessage());
            return ['error' => 'Failed to fetch product'];
        }
    }
}