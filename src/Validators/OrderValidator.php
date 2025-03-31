<?php

namespace App\Validators;

use App\Models\Product;
use App\Exceptions\OrderValidationException;

class OrderValidator
{
    public function __construct(
        private Product $productModel
    ) {}

    public function validateOrderItems(array $items): void
    {
        try {
            $this->validateItemsNotEmpty($items);
            $this->validateProductsExistAndInStock($items);
        } catch (OrderValidationException $e) {
            
            error_log("Order validation failed: " . $e->getMessage());
            throw $e; // Re-throw for the controller to handle
        }
    }

    private function validateItemsNotEmpty(array $items): void
    {
        if (empty($items)) {
            throw new OrderValidationException(
                'Your cart is empty. Please add items before placing an order.',
                'EMPTY_CART'
            );
        }
    }

    private function validateProductsExistAndInStock(array $items): void
    {
        $errors = [];
        
        foreach ($items as $productId) {
            try {
                $product = $this->productModel->getProductById($productId);
                
                if (!$product) {
                    $errors[] = "Product '{$productId}' is no longer available";
                    continue;
                }
                
                if (!$product['inStock']) {
                    $errors[] = "Product '{$product['name']}' ({$productId}) is out of stock";
                }
            } catch (\Exception $e) {
                $errors[] = "Could not verify product '{$productId}' availability";
                error_log("Product validation error: " . $e->getMessage());
            }
        }

        if (!empty($errors)) {
            throw new OrderValidationException(
                "Some items in your cart are unavailable:\n- " . implode("\n- ", $errors),
                'INVALID_ITEMS',
                ['invalid_items' => $items]
            );
        }
    }
}