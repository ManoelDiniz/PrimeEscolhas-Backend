<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('current_price')
                    ->label('Preço Atual')
                    ->money('BRL', true)
                    ->sortable(),
                TextColumn::make('old_price')
                    ->label('Preço Antigo')
                    ->money('BRL', true)
                    ->sortable(),
                TextColumn::make('marketplace')
                    ->label('Marketplace')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('review_count')
                    ->label('Contagem de Avaliações')
                    ->sortable(),
                TextColumn::make('rating')
                    ->label('Avaliação')
                    ->sortable(),
                BooleanColumn::make('in_stock')
                    ->label('Em Estoque')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
