<?php

namespace App\Observers;

use App\Models\Products;
use App\Services\Google\GoogleIndexNowService;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Products "created" event.
     */
    public function created(Products $product): void
    {
        $this->indexUrl($product);
    }

    /**
     * Handle the Products "updated" event.
     */
    public function updated(Products $product): void
    {
        $this->indexUrl($product);
    }

    /**
     * Handle the Products "deleted" event.
     */
    public function deleted(Products $product): void
    {
        try {
            $url = 'https://afiliado.primeescolhas.com.br' . '/produto/' . $product->slug;
            GoogleIndexNowService::indexUrl($url, 'URL_DELETED');
            Log::info("URL deletada indexada: {$url}");
        } catch (\Exception $e) {
            Log::error("Erro ao indexar URL deletada: {$e->getMessage()}");
        }
    }

    /**
     * Indexa a URL do produto no Google
     */
    private function indexUrl(Products $product): void
    {
        try {
            $url = 'https://afiliado.primeescolhas.com.br' . '/produto/' . $product->slug;
            GoogleIndexNowService::indexUrl($url);
            Log::info("URL indexada automaticamente: {$url}");
        } catch (\Exception $e) {
            Log::error("Erro ao indexar URL: {$e->getMessage()}");
        }
    }
}
