<?php

namespace App\Filament\Admin\Resources\Posts\Schemas;

use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

// 👇 Spatie + Filament (input para Media Library)
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

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
                                    ->maxLength(180)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (?string $state, callable $set, Get $get) {
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
                                    ->dehydrateStateUsing(fn(?string $state) => filled($state) ? Str::slug($state) : null),
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
                                    ->searchable()
                                    ->preload(),
                            ]),
                        ]),

                        Tab::make('Publicación')->schema([
                            Grid::make(2)->schema([
                                Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        'draft'     => 'Borrador',
                                        'published' => 'Publicado',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                DateTimePicker::make('published_at')
                                    ->label('Fecha de publicación')
                                    ->native(false),
                            ]),
                        ]),


                        Tab::make('SEO')->schema([
                            SpatieMediaLibraryFileUpload::make('og')
                                ->label('Imagen OG')
                                ->collection('og')               
                                ->image()
                                ->imageEditor()
                                ->imageCropAspectRatio('1200:630')
                                ->preserveFilenames()
                                ->responsiveImages()
                                ->openable()
                                ->downloadable()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(4096), // 4MB
                        ]),

                        // (Opcional) Galería para el Post
                        Tab::make('Galería')->schema([
                            SpatieMediaLibraryFileUpload::make('gallery')
                                ->collection('gallery')
                                 ->conversion('thumb') 
                                ->multiple()
                                ->reorderable()
                                ->panelLayout('grid')
                                ->image()
                                ->imageEditor()
                                ->preserveFilenames()
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxFiles(12)
                                ->maxSize(6144)
                        ]),

                    ]),
            ]);
    }
}
