<?php

namespace App\Filament\Admin\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Proyecto')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => $record->slug),

                IconColumn::make('featured')
                    ->label('Destacado')
                    ->boolean(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ]),

                TextColumn::make('skills.name')
                    ->label('Skills')
                    ->badge()
                ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tags.name')
                    ->label('Tags')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('repo_url')
                    ->label('Repo')
                    ->icon('heroicon-o-code-bracket')
                    ->url(fn($record) => $record->repo_url, true)
                    ->tooltip(fn($record) => $record->repo_url),

                IconColumn::make('demo_url')
                    ->label('Demo')
                    ->icon('heroicon-o-globe-alt')
                    ->url(fn($record) => $record->demo_url, true)
                    ->tooltip(fn($record) => $record->demo_url),

                ImageColumn::make('og_image_url')
                    ->label('Imagen OG')
                    ->disk('public')
                    ->square()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('published_at')
                    ->label('Publicado en')
                    ->dateTime()
                    ->sortable(),

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

                SelectFilter::make('skills')
                    ->label('Skill')
                    ->multiple()
                    ->relationship('skills', 'name'),

                SelectFilter::make('tags')
                    ->label('Tag')
                    ->multiple()
                    ->relationship('tags', 'name'),
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
