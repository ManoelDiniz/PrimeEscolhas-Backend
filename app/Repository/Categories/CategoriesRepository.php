<?php

namespace App\Repository\Categories;

use App\Models\Categories;

class CategoriesRepository
{
    public function create(array $data)
    {
        return Categories::create($data);
    }

    public function getAll()
    {
        return Categories::all();
    }

    public function findById(int $id)
    {
        return Categories::find($id);
    }

    public function delete(int $id): bool
    {
        $category = Categories::find($id);
        if ($category) {
            return $category->delete();
        }
        return false;
    }

    public function update(int $id, array $data)
    {
        $category = Categories::find($id);
        if ($category) {
            $category->update($data);
            return $category;
        }
        return null;
    }

    public function count(): int
    {
        return Categories::count();
    }
}
