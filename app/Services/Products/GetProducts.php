<?php

namespace App\Services\Products;

use App\Dto\ParamDto;
use App\Models\Products;
use App\Repository\Products\ProductRepository;
use Illuminate\Support\Facades\Cache;

class GetProducts
{
    protected int $cacheDuration = 60; // Cache duration in seconds 1 minute = 60 seconds
    public function __construct(
        private ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }
    public function execute(ParamDto $params)
    {
        $cacheKey = 'products_' . md5(json_encode($params->toArray()));

        return Cache::tags(['products'])->remember($cacheKey, $this->cacheDuration, function () use ($params) {
            return $this->productRepository->getAllProducts($params);
        });
    }
}
