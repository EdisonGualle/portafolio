<?php

namespace App\Filament\Admin\Resources\Projects\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'blocks';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')
                ->label('Tipo de bloque')
                ->options([
                    'hero' => 'Hero',
                    'markdown' => 'Markdown',
                    'gallery' => 'Galería',
                    'cta' => 'Call To Action',
                ])
                ->required()
                ->reactive(),

            TextInput::make('data_json.title')
                ->label('Título')
                ->visible(fn($get) => $get('type') === 'hero'),

            FileUpload::make('data_json.image')
                ->label('Imagen principal')
                ->image()
                ->directory('projects/blocks')
                ->visible(fn($get) => $get('type') === 'hero'),

            TextInput::make('data_json.content')
                ->label('Contenido Markdown')
                ->visible(fn($get) => $get('type') === 'markdown'),

            Repeater::make('data_json.images')
                ->label('Galería de imágenes')
                ->schema([
                    FileUpload::make('url')
                        ->label('Imagen')
                        ->image()
                        ->directory('projects/gallery'),
                ])
                ->visible(fn($get) => $get('type') === 'gallery'),

            TextInput::make('data_json.button_text')
                ->label('Texto del botón')
                ->visible(fn($get) => $get('type') === 'cta'),

            TextInput::make('data_json.button_url')
                ->label('URL del botón')
                ->url()
                ->visible(fn($get) => $get('type') === 'cta'),

            TextInput::make('order_index')
                ->label('Orden')
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->reorderable('order_index')
            ->columns([
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->sortable(),

                TextColumn::make('data_json->title')
                    ->label('Título')
                    ->limit(30)
                    ->toggleable()
                    ->sortable()
                    ->visible(fn($record) => $record?->type === 'hero'),

                TextColumn::make('data_json->content')
                    ->label('Contenido')
                    ->limit(30)
                    ->toggleable()
                    ->sortable()
                    ->visible(fn($record) => $record?->type === 'markdown'),

                TextColumn::make('data_json->images')
                    ->label('Imágenes')
                    ->formatStateUsing(fn($state) => is_array($state) ? count($state) . ' imágenes' : '0')
                    ->visible(fn($record) => $record?->type === 'gallery'),

                TextColumn::make('data_json->button_text')
                    ->label('Botón')
                    ->toggleable()
                    ->visible(fn($record) => $record?->type === 'cta'),

                TextColumn::make('order_index')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar bloque')
                    ->icon('heroicon-m-plus'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
