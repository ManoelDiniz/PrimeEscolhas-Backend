<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductsResource;
use App\Jobs\IndexUrlJob;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateProducts extends CreateRecord
{
    protected static string $resource = ProductsResource::class;

    public function afterCreate(): void
    {
        $product = $this->record;
        $url = 'https://afiliado.primeescolhas.com.br/produto/' . $product->slug;

        IndexUrlJob::dispatch($url)
            ->onQueue('indexing')
            ->delay(now()->addSeconds(10));


        $this->notify('success', 'URL enviada para fila de indexação 🚀');

        Cache::tags(['products'])->flush();
    }
}
