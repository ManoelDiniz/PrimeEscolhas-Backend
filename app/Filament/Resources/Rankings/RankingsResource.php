<?php

namespace App\Filament\Resources\Rankings;

use App\Filament\Resources\Rankings\Pages\CreateRankings;
use App\Filament\Resources\Rankings\Pages\EditRankings;
use App\Filament\Resources\Rankings\Pages\ListRankings;
use App\Filament\Resources\Rankings\Schemas\RankingsForm;
use App\Filament\Resources\Rankings\Tables\RankingsTable;
use App\Models\Rankings;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RankingsResource extends Resource
{
    protected static ?string $model = Rankings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Ranks';

    public static function form(Schema $schema): Schema
    {
        return RankingsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RankingsTable::configure($table);
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
            'index' => ListRankings::route('/'),
            'create' => CreateRankings::route('/create'),
            'edit' => EditRankings::route('/{record}/edit'),
        ];
    }
}
