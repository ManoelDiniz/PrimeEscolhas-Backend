<?php

namespace App\Services\Products;


use App\Repository\Products\ProductRepository;

class GetProductsBySlug
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }
    public function execute(string $slug)
    {
        $cacheKey = 'product_' . $slug;

        return cache()->remember($cacheKey, 3600, function () use ($slug) {
            return $this->productRepository->getBySlug($slug);
        });
    }
}
