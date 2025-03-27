<?php

namespace App\Models;

class Attribute extends AbstractModel
{
    public function getAttributesByProductId($productId)
    {
        $query = "
            SELECT a.id, a.name, a.type, pav.value, pav.display_value
            FROM product_attribute_values pav
            JOIN attributes a ON pav.attribute_id = a.id
            WHERE pav.product_id = :productId
        ";

        return $this->fetchAll($query, [':productId' => $productId]);
    }
}