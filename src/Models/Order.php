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

            $this->validator->validateOrderItems($items);

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

            return ['success' => false, 'message' => $e->getMessage()];
        } catch (Exception $e) {

            return ['success' => false, 'message' => 'Order processing failed'];
        } catch (Throwable $e) {
            error_log("ORDER FAILURE: " . $e->__toString());
            throw $e;
        }
    }
}
