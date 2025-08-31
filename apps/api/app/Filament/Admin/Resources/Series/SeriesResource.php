<?php

namespace App\Filament\Admin\Resources\Series;

use App\Filament\Admin\Resources\Series\Pages\CreateSeries;
use App\Filament\Admin\Resources\Series\Pages\EditSeries;
use App\Filament\Admin\Resources\Series\Pages\ListSeries;
use App\Filament\Admin\Resources\Series\Schemas\SeriesForm;
use App\Filament\Admin\Resources\Series\Tables\SeriesTable;
use App\Models\Series;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SeriesResource extends Resource
{
    protected static ?string $model = Series::class;

protected static string|BackedEnum|null $navigationIcon = Heroicon::QueueList;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $label = 'Serie';
    protected static ?string $pluralLabel = 'Series';

    public static function form(Schema $schema): Schema
    {
        return SeriesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SeriesTable::configure($table);
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
            'index' => ListSeries::route('/'),
            'create' => CreateSeries::route('/create'),
            'edit' => EditSeries::route('/{record}/edit'),
        ];
    }
}
