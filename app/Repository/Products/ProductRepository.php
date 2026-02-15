<?php

namespace App\Repository\Products;

use App\Dto\ParamDto;
use App\Models\Products;

class ProductRepository
{
    public function getAllProducts(ParamDto $params)
    {
        $query = Products::query();
        if ($params->search) {
            $query->where('name', 'like', '%' . $params->search . '%');
        }
        if ($params->sortBy && $params->sortDirection) {
            $query->orderBy($params->sortBy, $params->sortDirection);
        }
        if ($params->is_featured && $params->is_featured !== null) {
            $query->where('is_featured', $params->is_featured);
        }

        return $query->with('affiliateLinks')->paginate($params->perPage, ['*'], 'page', $params->page);
    }

    public function getBySlug(string $slug)
    {
        return Products::where('slug', $slug)->with('affiliateLinks')->firstOrFail();
    }
}
