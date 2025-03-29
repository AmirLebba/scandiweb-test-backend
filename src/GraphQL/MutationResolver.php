<?php

namespace App\GraphQL;

use App\Models\Order;
use App\Validators\OrderValidator;
use Exception;

class MutationResolver
{
    public function __construct(
        private Order $order
    ) {}

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