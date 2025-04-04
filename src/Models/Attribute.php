<?php

namespace App\Models;

use App\Database\QueryBuilder;

class Attribute extends AbstractModel
{
    public function getAttributesByProductId($productId)
    {
        return (new QueryBuilder($this->db))
            ->select([
                'a.id', 
                'a.name', 
                'a.type', 
                'pav.value', 
                'pav.display_value'
            ])
            ->from('product_attribute_values pav')
            ->leftJoin('attributes a', 'pav.attribute_id = a.id')
            ->where('pav.product_id', '=', $productId)
            ->execute();
    }
}