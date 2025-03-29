<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use App\GraphQL\Types\TypeRegistry;
use App\Config\Database;

class SchemaFactory
{
    public static function create(): Schema
    {
       
        $db = Database::getConnection();

        $productModel = new \App\Models\Product($db);
        $categoryModel = new \App\Models\Category($db);
        $orderModel = new \App\Models\Order(
            $db,
            new \App\Validators\OrderValidator($productModel)
        );

        $queryResolver = new \App\GraphQL\QueryResolver($productModel, $categoryModel);
        $mutationResolver = new \App\GraphQL\MutationResolver($orderModel);

        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'products' => [
                    'type' => Type::listOf(TypeRegistry::product()),
                    'args' => ['categoryId' => ['type' => Type::int()]],
                    'resolve' => [$queryResolver, 'getProducts'],
                ],
                'product' => [
                    'type' => TypeRegistry::product(),
                    'args' => ['id' => ['type' => Type::string()]],
                    'resolve' => [$queryResolver, 'getProductById'],
                ],
                'categories' => [
                    'type' => Type::listOf(TypeRegistry::category()),
                    'resolve' => [$queryResolver, 'getCategories'],
                ],
            ],
        ]);

        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'placeOrder' => [
                    'type' => TypeRegistry::orderResponse(),
                    'args' => [
                        'items' => Type::listOf(Type::string()),
                    ],
                    'resolve' => [$mutationResolver, 'placeOrder'],
                ],
            ],
        ]);

        return new Schema([
            'query' => $queryType,
            'mutation' => $mutationType
        ]);
    }
}