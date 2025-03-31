<?php

namespace App\GraphQL\Resolvers;

use App\Models\Category;
use Exception;

class CategoryResolver
{
    private $category;

    public function getCategories()
    {
        try {
            return $this->category->getAllCategories();
        } catch (Exception $e) {
            error_log("Category fetch error: " . $e->getMessage());
            return ['error' => 'Failed to fetch categories'];
        }
    }
}