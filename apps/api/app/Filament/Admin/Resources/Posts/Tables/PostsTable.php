<?php

namespace App\Filament\Admin\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

// 👇 Spatie + Filament (columna para Media Library)
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Imagen OG (Media Library -> conversion 'thumb')
                SpatieMediaLibraryImageColumn::make('og')
                    ->collection('og')
                    ->conversion('thumb')
                    ->label('OG')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->slug),

                TextColumn::make('excerpt')
                    ->label('Resumen')
                    ->limit(60)
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->sortable()
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'draft' ? 'Borrador' : 'Publicado'),

                TextColumn::make('published_at')
                    ->label('Publicado en')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('series.title')
                    ->label('Serie')
                    ->sortable()
                    ->default('-'),

                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->separator(','),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Borrador',
                        'published' => 'Publicado',
                    ]),
                SelectFilter::make('series_id')
                    ->relationship('series', 'title')
                    ->label('Serie'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
