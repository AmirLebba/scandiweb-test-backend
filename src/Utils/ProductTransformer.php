<?php

namespace App\Utils;

class ProductTransformer
{
    public static function transformProductRows(array $rows): array
    {
        if (empty($rows)) {
            error_log("ProductTransformer: No data received from database");
            return [];
        }

        error_log("ProductTransformer received: " . json_encode($rows));

        $products = [];

        foreach ($rows as $row) {
            error_log("Processing product: " . json_encode($row));

            $productId = $row['id'];

            if (!isset($products[$productId])) {
                $products[$productId] = [
                    'id' => $row['id'] ?? "MISSING ID",
                    'name' => $row['name'] ?? "MISSING NAME",
                    'categoryId' => $row['category_id'] ?? null,
                    'inStock' => isset($row['in_stock']) ? (bool)$row['in_stock'] : null,
                    'description' => $row['description'] ?? "No description",
                    'brand' => $row['brand'] ?? "Unknown",
                    'prices' => [],
                    'attributes' => [],
                    'gallery' => [],
                ];
            }


            if (!empty($row['price'])) {
                $currencyKey = $row['currency_label'] . $row['currency_symbol'];
                $existingCurrencies = array_column($products[$productId]['prices'], 'currency');

                if (!in_array($currencyKey, $existingCurrencies)) {
                    $products[$productId]['prices'][] = [
                        'amount' => (float)$row['price'],
                        'currency' => [
                            'label' => $row['currency_label'] ?? "Unknown",
                            'symbol' => $row['currency_symbol'] ?? "?",
                        ],
                    ];
                }
            }


            if (!empty($row['image_url']) && !in_array($row['image_url'], $products[$productId]['gallery'])) {
                $products[$productId]['gallery'][] = $row['image_url'];
            }


            if (!empty($row['attribute_name']) && !empty($row['attribute_id'])) {
                $attributeKey = $row['attribute_id']; 

                if (!isset($products[$productId]['attributes'][$attributeKey])) {
                    $products[$productId]['attributes'][$attributeKey] = [
                        'id' => $row['attribute_id'],
                        'name' => $row['attribute_name'],
                        'type' => $row['attribute_type'] ?? "Unknown",
                        'items' => [],
                    ];
                }


                $existingValues = array_column($products[$productId]['attributes'][$attributeKey]['items'], 'value');
                if (!in_array($row['value'], $existingValues)) {
                    $products[$productId]['attributes'][$attributeKey]['items'][] = [
                        'id' => $row['value_id'] ?? "MISSING VALUE ID",
                        'displayValue' => $row['display_value'] ?? "Unknown",
                        'value' => $row['value'] ?? "Unknown",
                    ];
                }
            }
        }


        foreach ($products as &$product) {
            $product['attributes'] = array_values($product['attributes']);
        }

        return array_values($products);
    }
}