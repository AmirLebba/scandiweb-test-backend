<?php

namespace App\GraphQL\Types;

class TypeRegistry
{
    private static $price;
    private static $currency;
    private static $attribute;
    private static $attributeSet;
    private static $product;
    private static $category;
    private static $orderResponse;

    public static function price()
    {
        return self::$price ?: (self::$price = new PriceType());
    }

    public static function currency()
    {
        return self::$currency ?: (self::$currency = new CurrencyType());
    }

    public static function attribute()
    {
        return self::$attribute ?: (self::$attribute = new AttributeType());
    }

    public static function attributeSet()
    {
        return self::$attributeSet ?: (self::$attributeSet = new AttributeSetType());
    }

    public static function product()
    {
        return self::$product ?: (self::$product = new ProductType());
    }

    public static function category()
    {
        return self::$category ?: (self::$category = new CategoryType());
    }

    public static function orderResponse()
    {
        return self::$orderResponse ?: (self::$orderResponse = new OrderResponseType());
    }
}