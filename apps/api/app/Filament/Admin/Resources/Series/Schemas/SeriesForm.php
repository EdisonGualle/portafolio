<?php

namespace App\Filament\Admin\Resources\Series\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SeriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(180)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, callable $set, Get $get) {
                        // Autogenera slug si está vacío
                        if (blank($get('slug')) && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(200)
                    ->rule('alpha_dash')
                    ->unique(ignoreRecord: true)
                    ->helperText('Se autogenera desde el título; puedes ajustarlo.')
                    // Normaliza a slug al guardar
                    ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Str::slug($state) : null),
            ])->columnSpanFull(),

            Textarea::make('description')
                ->label('Descripción')
                ->columnSpanFull(),

            TextInput::make('sort_order')
                ->label('Orden')
                ->numeric()
                ->default(0)
                ->helperText('Entero; se usa para ordenar en listados.'),
        ]);
    }
}
