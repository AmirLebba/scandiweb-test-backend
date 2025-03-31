<?php

namespace App\Models;

use App\Config\Database;
use PDO;
use Exception;

abstract class AbstractModel
{
    protected $db;

    private function executeQuery($query, $params = [], $fetchMode = PDO::FETCH_ASSOC, $fetchAll = true)
    {
        try {
            $stmt = $this->db->prepare($query);
            foreach ($params as $key => &$value) {
                $stmt->bindParam($key, $value);
            }
            $stmt->execute();
            return $fetchAll ? $stmt->fetchAll($fetchMode) : $stmt->fetch($fetchMode);
        } catch (Exception $e) {
            error_log("Database Query Error: " . $e->getMessage());
            return $fetchAll ? [] : null;
        }
    }

    protected function fetchAll($query, $params = [])
    {
        return $this->executeQuery($query, $params, PDO::FETCH_ASSOC, true);
    }

    protected function fetchOne($query, $params = [])
    {
        return $this->executeQuery($query, $params, PDO::FETCH_ASSOC, false);
    }
}