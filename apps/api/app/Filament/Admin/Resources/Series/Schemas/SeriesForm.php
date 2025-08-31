<?php

namespace App\Filament\Admin\Resources\Series\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SeriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(180),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(200),

                Textarea::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
