<?php

namespace App\Filament\Resources\Products\Schemas;

use Dom\Text;
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
                                    ->type('number')
                                    ->required(),
                                TextInput::make('old_price')
                                    ->label('Preço Antigo')
                                    ->type('number')
                                    ->nullable(),
                                TextInput::make('image')
                                    ->label('Imagem')
                                    ->nullable(),
                                TextInput::make('affiliate_url')
                                    ->label('URL de Afiliado')
                                    ->nullable(),
                                TextInput::make('marketplace')
                                    ->label('Marketplace')
                                    ->nullable(),
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
                                        // Aqui você pode buscar as categorias do banco de dados
                                        return \App\Models\Categories::pluck('name', 'id');
                                    })
                                    ->label('ID da Categoria')
                                    ->nullable(),
                            ])
                            ->columnSpan(2)
                            ->columns(2),
                        Section::make('Descrições')
                            ->schema([
                                Textarea::make('description')
                                    ->label('Descrição')
                                    ->nullable(),
                                Textarea::make('long_description')
                                    ->label('Descrição Longa')
                                    ->nullable(),

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
                    ]),
            ]);
    }
}
