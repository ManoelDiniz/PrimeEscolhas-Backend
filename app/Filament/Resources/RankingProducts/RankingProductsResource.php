<?php

namespace App\Filament\Resources\RankingProducts;

use App\Filament\Resources\RankingProducts\Pages\CreateRankingProducts;
use App\Filament\Resources\RankingProducts\Pages\EditRankingProducts;
use App\Filament\Resources\RankingProducts\Pages\ListRankingProducts;
use App\Filament\Resources\RankingProducts\Schemas\RankingProductsForm;
use App\Filament\Resources\RankingProducts\Tables\RankingProductsTable;
use App\Models\RankingProducts;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RankingProductsResource extends Resource
{
    protected static ?string $model = RankingProducts::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Ranking Dos Produtos';

    public static function form(Schema $schema): Schema
    {
        return RankingProductsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RankingProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRankingProducts::route('/'),
            'create' => CreateRankingProducts::route('/create'),
            'edit' => EditRankingProducts::route('/{record}/edit'),
        ];
    }
}
