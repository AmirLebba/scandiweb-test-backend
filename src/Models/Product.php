<?php

namespace App\Models;

use App\Query\ProductQuery;
use App\Utils\ProductTransformer;
use Exception;

class Product extends AbstractModel
{
    public function getAllProducts($categoryId = null)
    {
        error_log("Executing getAllProducts with categoryId: " . json_encode($categoryId));

        $rows = ProductQuery::getAllProductsQuery($categoryId);

        error_log("Raw product data from DB: " . json_encode($rows));

        return empty($rows) ? [] : ProductTransformer::transformProductRows($rows);
    }

    public function getProductById($id)
    {
        error_log("Executing getProductById for ID: " . json_encode($id));

        $rows = ProductQuery::getProductByIdQuery($id);

        if (empty($rows)) {
            error_log("No product found for ID: " . json_encode($id));
            return null;
        }

        error_log("Raw product data from DB: " . json_encode($rows));

        return ProductTransformer::transformProductRows($rows)[0] ?? null;
    }
}
