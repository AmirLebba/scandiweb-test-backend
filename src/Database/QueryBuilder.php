<?php

namespace App\Database;

use PDO;

class QueryBuilder
{
    private $pdo;
    private $query;
    private $params = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function select(array $columns)
    {
        $this->query = "SELECT " . implode(", ", $columns);
        return $this;
    }

    public function from(string $table)
    {
        $this->query .= " FROM " . $table;
        return $this;
    }

    public function leftJoin(string $table, string $on)
    {
        $this->query .= " LEFT JOIN $table ON $on";
        return $this;
    }

    public function where(string $column, string $operator, $value)
    {
        $placeholder = ":" . str_replace(".", "_", $column); 
        $this->query .= empty($this->params) ? " WHERE " : " AND ";
        $this->query .= "$column $operator $placeholder";
        $this->params[$placeholder] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = "ASC")
    {
        $this->query .= " ORDER BY $column $direction";
        return $this;
    }

    public function limit(int $limit)
    {
        $this->query .= " LIMIT $limit";
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function execute()
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}