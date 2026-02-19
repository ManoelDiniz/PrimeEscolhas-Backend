<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductsResource;
use App\Jobs\IndexUrlJob;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditProducts extends EditRecord
{
    protected static string $resource = ProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $url = 'https://afiliado.primeescolhas.com.br/produto/' . $this->record->slug;
        IndexUrlJob::dispatch($url)
            ->onQueue('indexing')
            ->delay(now()->addSeconds(10));




        Cache::tags(['products'])->flush();
    }
}
