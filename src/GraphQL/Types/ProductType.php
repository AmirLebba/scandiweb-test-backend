<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => [
                'id' => Type::string(),
                'name' => Type::string(),
                'categoryId' => Type::int(),
                'inStock' => Type::boolean(),
                'description' => Type::string(),
                'brand' => Type::string(),
                'prices' => Type::listOf(TypeRegistry::price()),
                'attributes' => Type::listOf(TypeRegistry::attributeSet()),
                'gallery' => Type::listOf(Type::string()),
            ],
        ]);
    }
}
