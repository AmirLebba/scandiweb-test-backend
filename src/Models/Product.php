<?php

namespace App\Models;

use App\Query\ProductQuery;
use App\Utils\ProductTransformer;

class Product extends AbstractModel
{
    public function getAllProducts($categoryId = null)
    {

        $rows = ProductQuery::getAllProductsQuery($categoryId);

        return empty($rows) ? [] : ProductTransformer::transformProductRows($rows);
    }

    public function getProductById($id)
    {

        $rows = ProductQuery::getProductByIdQuery($id);

        if (empty($rows)) {
            error_log("No product found for ID: " . json_encode($id));
            return null;
        }

        return ProductTransformer::transformProductRows($rows)[0] ?? null;
    }
}
