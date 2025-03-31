<?php

namespace App\GraphQL\Resolvers;

use App\Models\Order;
use Exception;

class OrderResolver
{
    private $order;

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