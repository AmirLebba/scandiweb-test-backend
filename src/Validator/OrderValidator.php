<?php

namespace App\Validator;

use App\Models\Product;
use RuntimeException;

class OrderValidator
{
    public function __construct(private Product $productModel) {}

    public function validateOrderItems(array $items): void
    {
        if (empty($items)) {
            throw new RuntimeException("Order must contain at least one item");
        }

        foreach ($items as $productId) {
            $product = $this->productModel->getProductById($productId);
            
            if (!$product) {
                throw new RuntimeException("Product {$productId} does not exist");
            }

            if (!$product['inStock']) {
                throw new RuntimeException("Product {$productId} is out of stock");
            }
        }
    }
}