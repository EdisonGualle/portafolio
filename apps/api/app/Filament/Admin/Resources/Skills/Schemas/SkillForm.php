<?php

namespace App\Filament\Admin\Resources\Skills\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(120)
                    ->unique(ignoreRecord: true, table: 'skills', column: 'name'),

                Select::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),

                TextInput::make('level')
                    ->label('Nivel (%)')
                    ->numeric()
                    ->default(70)
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
