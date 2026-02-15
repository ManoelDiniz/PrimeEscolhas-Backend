<?php

namespace App\Filament\Resources\AffiliateLinks;

use App\Filament\Resources\AffiliateLinks\Pages\CreateAffiliateLinks;
use App\Filament\Resources\AffiliateLinks\Pages\EditAffiliateLinks;
use App\Filament\Resources\AffiliateLinks\Pages\ListAffiliateLinks;
use App\Filament\Resources\AffiliateLinks\Schemas\AffiliateLinksForm;
use App\Filament\Resources\AffiliateLinks\Tables\AffiliateLinksTable;
use App\Models\AffiliateLinks;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AffiliateLinksResource extends Resource
{
    protected static ?string $model = AffiliateLinks::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Link Dos Afiliados';

    public static function form(Schema $schema): Schema
    {
        return AffiliateLinksForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AffiliateLinksTable::configure($table);
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
            'index' => ListAffiliateLinks::route('/'),
            'create' => CreateAffiliateLinks::route('/create'),
            'edit' => EditAffiliateLinks::route('/{record}/edit'),
        ];
    }
}
