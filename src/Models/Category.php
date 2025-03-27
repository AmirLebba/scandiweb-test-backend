<?php

namespace App\Models;

class Category extends AbstractModel
{
    public function getAllCategories()
    {
        return $this->fetchAll("SELECT id, name FROM categories");
    }

    public function getCategoryById($id)
    {
        return $this->fetchOne("SELECT id, name FROM categories WHERE id = :id", [':id' => $id]);
    }
}