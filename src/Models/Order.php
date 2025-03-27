<?php

namespace App\Models;

use App\Validator\OrderValidator;
use Exception;
use RuntimeException;

class Order extends AbstractModel
{
    public function __construct(
        private OrderValidator $validator = new OrderValidator(new Product())
    ) {}

    public function placeOrder(array $items): array
    {
        try {
            // Validate first
            $this->validator->validateOrderItems($items);

            // Proceed if validation passes
            $itemsJson = json_encode($items);
            $query = "INSERT INTO orders (items, created_at) VALUES (:items, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":items", $itemsJson);
            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Order placed successfully',
                'orderId' => $this->db->lastInsertId()
            ];
            
        } catch (RuntimeException $e) {
            // Validation errors
            return ['success' => false, 'message' => $e->getMessage()];
        } catch (Exception $e) {
            // Database errors
            return ['success' => false, 'message' => 'Order processing failed'];
        }
    }
}