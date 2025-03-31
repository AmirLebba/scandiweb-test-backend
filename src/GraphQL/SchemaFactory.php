<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use App\GraphQL\Types\TypeRegistry;
use App\Config\Database;
use App\GraphQL\Resolvers\ProductListResolver;
use App\GraphQL\Resolvers\ProductDetailResolver;
use App\GraphQL\Resolvers\CategoryResolver;
use App\GraphQL\Resolvers\OrderResolver;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Validators\OrderValidator;

class SchemaFactory
{
    public static function create(): Schema
    {
        $db = Database::getConnection();

        $productModel = new Product($db);
        $categoryModel = new Category($db);
        $orderModel = new Order(
            $db,
            new OrderValidator($productModel)
        );

        $productListResolver = new ProductListResolver($productModel);
        $productDetailResolver = new ProductDetailResolver($productModel);
        $categoryResolver = new CategoryResolver($categoryModel);
        $orderResolver = new OrderResolver($orderModel);

        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'products' => [
                    'type' => Type::listOf(TypeRegistry::product()),
                    'args' => ['categoryId' => ['type' => Type::int()]],
                    'resolve' => [$productListResolver, 'getProducts'],
                ],
                'product' => [
                    'type' => TypeRegistry::product(),
                    'args' => ['id' => ['type' => Type::string()]],
                    'resolve' => [$productDetailResolver, 'getProductById'],
                ],
                'categories' => [
                    'type' => Type::listOf(TypeRegistry::category()),
                    'resolve' => [$categoryResolver, 'getCategories'],
                ],
            ],
        ]);

        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'placeOrder' => [
                    'type' => TypeRegistry::orderResponse(),
                    'args' => ['items' => Type::listOf(Type::string())],
                    'resolve' => [$orderResolver, 'placeOrder'],
                ],
            ],
        ]);

        return new Schema([
            'query' => $queryType,
            'mutation' => $mutationType
        ]);
    }
}