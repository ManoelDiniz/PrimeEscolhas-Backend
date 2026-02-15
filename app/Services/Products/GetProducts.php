<?php

namespace App\Services\Products;

use App\Dto\ParamDto;
use App\Models\Products;
use App\Repository\Products\ProductRepository;

class GetProducts
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }
    public function execute(ParamDto $params)
    {
        $cacheKey = 'products_' . md5(json_encode($params->toArray()));

        return cache()->remember($cacheKey, 3600, function () use ($params) {
            return $this->productRepository->getAllProducts($params);
        });
    }
}
