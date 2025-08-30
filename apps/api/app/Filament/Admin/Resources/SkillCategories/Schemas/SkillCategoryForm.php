<?php

namespace App\Filament\Admin\Resources\SkillCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class SkillCategoryForm
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
                    ->unique(
                        table: 'skill_categories',
                        column: 'name',
                        ignorable: fn (?Model $record) => $record
                    ),

                TextInput::make('sort_order')
                    ->label('Orden')
                    ->integer()
                    ->default(0),
            ]);
    }
}
