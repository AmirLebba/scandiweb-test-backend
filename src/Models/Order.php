<?php

namespace App\Models;

use App\Validators\OrderValidator;
use PDO;
use Exception;
use RuntimeException;
use Throwable;

class Order extends AbstractModel
{

    private OrderValidator $validator;


    public function __construct(PDO $db, OrderValidator $validator)
    {
        parent::__construct($db); 
        $this->validator = $validator;
    }

    public function placeOrder(array $items): array
    {
        try {
            error_log("Attempting order with items: " . print_r($items, true));

            // Debug database connection
            error_log("DB Connection: " . ($this->db ? "OK" : "NULL"));

            // Test simple query
            $test = $this->db->query("SELECT 1")->fetch();
            error_log("DB Test Query: " . print_r($test, true));

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
        } catch (Throwable $e) {
            error_log("ORDER FAILURE: " . $e->__toString());
            throw $e; // Re-throw for GraphQL handler
        }
    }
}