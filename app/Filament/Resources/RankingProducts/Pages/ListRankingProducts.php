<?php

namespace App\Filament\Resources\RankingProducts\Pages;

use App\Filament\Resources\RankingProducts\RankingProductsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRankingProducts extends ListRecords
{
    protected static string $resource = RankingProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
