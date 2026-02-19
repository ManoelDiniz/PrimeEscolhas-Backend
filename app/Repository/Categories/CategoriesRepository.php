<?php

namespace App\Repository\Categories;

use App\Dto\ParamDto;
use App\Models\Categories;

class CategoriesRepository
{
    public function create(array $data)
    {
        return Categories::create($data);
    }

    public function getAll(?ParamDto $params = null)
    {
        $query = Categories::query();

        if ($params?->search) {
            $query->where('name', 'like', '%' . $params->search . '%');
        }

        if ($params?->sortBy && $params?->sortDirection) {
            $query->orderBy($params->sortBy, $params->sortDirection);
        }

        return $query->paginate($params?->perPage ?? 10, ['*'], 'page', $params?->page ?? 1);
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

    public function getBySlug(string $slug)
    {
        return Categories::where('slug', $slug)->with('products')->first();
    }
}
