<?php

namespace App\Filament\Resources\RankingProducts\Pages;

use App\Filament\Resources\RankingProducts\RankingProductsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRankingProducts extends EditRecord
{
    protected static string $resource = RankingProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
