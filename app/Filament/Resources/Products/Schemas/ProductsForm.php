<?php

namespace App\Filament\Resources\Products\Schemas;

use Dom\Text;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(3)
                    ->columnSpan(2)
                    ->schema([
                        Section::make('Detalhes do Produto')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Título')
                                    ->required(),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required(),
                                TextInput::make('current_price')
                                    ->label('Preço Atual')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('old_price')
                                    ->label('Preço Antigo')
                                    ->numeric()
                                    ->nullable(),
                                FileUpload::make('image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('products')
                                    ->visibility('public')
                                    ->acceptedFileTypes([
                                        'image/jpeg',
                                        'image/png',
                                        'image/webp',
                                    ]),
                                TextInput::make('review_count')
                                    ->label('Contagem de Avaliações')
                                    ->type('number')
                                    ->default(0),
                                TextInput::make('rating')
                                    ->label('Avaliação')
                                    ->type('number')
                                    ->step(0.1)

                                    ->nullable(),
                                Select::make('in_stock')
                                    ->label('Em Estoque')
                                    ->options([
                                        1 => 'Sim',
                                        0 => 'Não',
                                    ])
                                    ->default(true),
                                Select::make('category_id')
                                    ->options(function () {

                                        return \App\Models\Categories::pluck('name', 'id');
                                    })
                                    ->label('ID da Categoria')
                                    ->nullable(),
                                TextInput::make('score')
                                    ->numeric()
                                    ->step(0.1)
                                    ->nullable(),

                                Select::make('is_featured')
                                    ->options([
                                        1 => 'Sim',
                                        0 => 'Não'
                                    ])
                                    ->default(0),
                            ])
                            ->columnSpan(2)
                            ->columns(2),
                        Section::make('Descrições')
                            ->schema([
                                Textarea::make('description')
                                    ->label('Descrição')
                                    ->nullable(),

                                RichEditor::make('long_description')
                                    ->columnSpanFull(),

                            ])
                            ->columnSpan(2),

                        Section::make('SEO')
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label('Meta Título')
                                    ->nullable(),
                                TextInput::make('meta_description')
                                    ->label('Meta Descrição')
                                    ->nullable(),
                                TextInput::make('meta_keywords')
                                    ->label('Meta Palavras-chave')
                                    ->nullable(),
                            ])
                            ->columnSpan(2),
                        Section::make('Links de Afiliado')
                            ->schema([
                                Repeater::make('affiliateLinks')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('platform')
                                            ->label('Plataforma')
                                            ->required(),

                                        TextInput::make('url')
                                            ->label('URL')
                                            ->required(),

                                        TextInput::make('oldPrice')
                                            ->label('Preço Antigo')
                                            ->numeric()
                                            ->nullable(),
                                        TextInput::make('currentPrice')
                                            ->label('Preço Atual')
                                            ->numeric()
                                            ->nullable(),
                                        TextInput::make('discountPercentage')
                                            ->label('Porcentagem de Desconto')
                                            ->numeric()
                                            ->nullable()
                                            ->default(function ($record) {
                                                if ($record && $record->oldPrice && $record->currentPrice) {
                                                    return round((($record->oldPrice - $record->currentPrice) / $record->oldPrice) * 100, 2);
                                                }
                                                return null;
                                            }),
                                    ])
                                    ->columns(3)
                            ])

                    ]),
            ]);
    }
}
