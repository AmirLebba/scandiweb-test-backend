<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use App\GraphQL\Types\TypeRegistry;
use App\GraphQL\Resolvers;

class SchemaFactory
{
    public static function create(): Schema
    {
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'products' => [
                    'type' => Type::listOf(TypeRegistry::product()),
                    'args' => ['categoryId' => ['type' => Type::int()]],
                    'resolve' => fn($root, $args) => (new Resolvers())->getProducts($root, $args),
                ],
                'product' => [
                    'type' => TypeRegistry::product(),
                    'args' => ['id' => ['type' => Type::string()]],
                    'resolve' => fn($root, $args) => (new Resolvers())->getProductById($root, $args),
                ],
                'categories' => [
                    'type' => Type::listOf(TypeRegistry::category()),
                    'resolve' => fn() => (new Resolvers())->getCategories(),
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
                    'resolve' => fn($root, $args) => (new Resolvers())->placeOrder($root, $args),
                ],
            ],
        ]);

        return new Schema([
            'query' => $queryType,
            'mutation' => $mutationType
        ]);
    }
}