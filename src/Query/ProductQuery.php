<?php

namespace App\Query;

use App\Database\QueryBuilder;
use App\Config\Database;

class ProductQuery
{
    public static function getAllProductsQuery($categoryId = null)
    {
        $db = Database::getConnection();
        $qb = new QueryBuilder($db);

        $qb->select([
            "p.id",
            "p.name",
            "p.category_id",
            "p.in_stock",
            "p.description",
            "p.brand",
            "pp.amount AS price",
            "pp.currency_label",
            "pp.currency_symbol",
            "pg.image_url",
            "a.id AS attribute_id",
            "a.name AS attribute_name",
            "a.type AS attribute_type",
            "pav.id AS value_id",
            "pav.value",
            "pav.display_value"
        ])
            ->from("products p")
            ->leftJoin("product_prices pp", "p.id = pp.product_id")
            ->leftJoin("product_gallery pg", "p.id = pg.product_id")
            ->leftJoin("product_attribute_values pav", "p.id = pav.product_id")
            ->leftJoin("attributes a", "pav.attribute_id = a.id");

        if ($categoryId !== null && $categoryId != 1) {
            $qb->where("p.category_id", "=", $categoryId);
        }

        $qb->orderBy("p.name");

        $result = $qb->execute();

       
        error_log("Fetched products: " . json_encode($result));

        return $result;
    }


    public static function getProductByIdQuery($id)
    {
        $db = Database::getConnection();
        $qb = new QueryBuilder($db);

        error_log("Building SQL for getProductById with ID: " . json_encode($id));

        $qb->select([
            "p.id",
            "p.name",
            "p.category_id",
            "p.in_stock",
            "p.description",
            "p.brand",
            "pp.amount AS price",
            "pp.currency_label",
            "pp.currency_symbol",
            "pg.image_url",
            "a.id AS attribute_id",
            "a.name AS attribute_name",
            "a.type AS attribute_type",
            "pav.id AS value_id",
            "pav.value",
            "pav.display_value"
        ])
            ->from("products p")
            ->leftJoin("product_prices pp", "p.id = pp.product_id")
            ->leftJoin("product_gallery pg", "p.id = pg.product_id")
            ->leftJoin("product_attribute_values pav", "p.id = pav.product_id")
            ->leftJoin("attributes a", "pav.attribute_id = a.id")
            ->where("p.id", "=", $id);

        $sql = $qb->getQuery(); 
        error_log("Executing SQL: " . $sql);

        $result = $qb->execute();
        error_log("SQL Query Result: " . json_encode($result));

        return $result;
    }
}