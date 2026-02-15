<?php

namespace App\Filament\Resources\RankingProducts\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RankingProductsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('rankingProducts')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->options(\App\Models\Products::pluck('title', 'id'))
                            ->searchable()
                            ->required(),

                        TextInput::make('position')
                            ->numeric()
                            ->required(),
                    ])
            ]);
    }
}
