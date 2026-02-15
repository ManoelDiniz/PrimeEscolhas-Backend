<?php

namespace App\Filament\Resources\AffiliateLinks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AffiliateLinksForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('platform')
                    ->required(),
                TextInput::make('url')
                    ->url()
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
            ]);
    }
}
