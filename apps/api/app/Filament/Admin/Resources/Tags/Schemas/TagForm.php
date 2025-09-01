<?php

namespace App\Filament\Admin\Resources\Tags\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(120)
                    ->unique(ignoreRecord: true, table: 'tags', column: 'name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, callable $set, Get $get) {
                        // Autogenera el slug si está vacío
                        if (blank($get('slug')) && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(120)
                    ->rule('alpha_dash')
                    ->unique(ignoreRecord: true, table: 'tags', column: 'slug')
                    ->helperText('Se autogenera desde el nombre; puedes ajustarlo.')
                    // Normaliza a slug al guardar
                    ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Str::slug($state) : null),
            ])->columnSpanFull(),
        ]);
    }
}
