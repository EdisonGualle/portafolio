<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('ProjectTabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Información')->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Título')
                                        ->required()
                                        ->maxLength(180),

                                    TextInput::make('slug')
                                        ->label('Slug')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(200),
                                ]),

                            Textarea::make('summary')
                                ->label('Resumen')
                                ->rows(3)
                                ->columnSpanFull(),

                            MarkdownEditor::make('body_md')
                                ->label('Descripción')
                                ->columnSpanFull(),
                        ]),

                        Tab::make('Relaciones')->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('skills')
                                        ->label('Skills')
                                        ->multiple()
                                        ->relationship('skills', 'name')
                                        ->preload(),

                                    Select::make('tags')
                                        ->label('Tags')
                                        ->multiple()
                                        ->relationship('tags', 'name')
                                        ->preload(),
                                ]),
                        ]),

                        Tab::make('Publicación')->schema([
                            Grid::make(2)->schema([
                                Toggle::make('featured')
                                    ->label('Destacado'),

                                TextInput::make('sort_order')
                                    ->label('Orden')
                                    ->numeric()
                                    ->default(0),

                                Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        'draft' => 'Borrador',
                                        'published' => 'Publicado',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                DateTimePicker::make('published_at')
                                    ->label('Fecha de publicación'),
                            ]),

                            Grid::make(2)->schema([
                                TextInput::make('repo_url')
                                    ->label('Repositorio')
                                    ->url()
                                    ->placeholder('https://github.com/usuario/proyecto'),

                                TextInput::make('demo_url')
                                    ->label('Demo')
                                    ->url()
                                    ->placeholder('https://mi-proyecto-demo.com'),
                            ]),
                        ]),

                        Tab::make('SEO')->schema([
                            FileUpload::make('og_image_url')
                                ->label('Imagen OG')
                                ->disk('public')
                                ->directory('projects/og')
                                ->visibility('public')
                                ->preserveFilenames()
                                ->imagePreviewHeight('200') 
                                ->openable() 
                                ->downloadable() 
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),

                        ]),
                    ]),
            ]);
    }
}
