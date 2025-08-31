<?php

namespace App\Filament\Admin\Resources\Posts\Schemas;

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

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('PostTabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Información')->schema([
                            Grid::make(2)->schema([
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

                            Textarea::make('excerpt')
                                ->label('Resumen')
                                ->rows(3)
                                ->columnSpanFull(),

                            MarkdownEditor::make('body_md')
                                ->label('Contenido')
                                ->columnSpanFull(),
                        ]),

                        Tab::make('Relaciones')->schema([
                            Grid::make(2)->schema([
                                Select::make('series_id')
                                    ->label('Serie')
                                    ->relationship('series', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Seleccione una serie'),

                                Select::make('tags')
                                    ->label('Tags')
                                    ->multiple()
                                    ->relationship('tags', 'name')
                                    ->preload(),
                            ]),
                        ]),

                        Tab::make('Publicación')->schema([
                            Grid::make(2)->schema([
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
                        ]),


                        Tab::make('SEO')->schema([
                            FileUpload::make('og_image_url')
                                ->label('Imagen OG')
                                ->image()
                                ->disk('public')
                                ->directory('posts/og/' . date('Y/m/d'))
                                ->visibility('public')
                                ->imageEditor()                        
                                ->imageResizeMode('cover')
                                ->imagePreviewHeight('200')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->preserveFilenames()                   
                                ->openable()                             
                                ->downloadable(),                       
                        ]),

                    ]),
            ]);
    }
}
