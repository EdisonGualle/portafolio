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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('ProjectTabs')
                    ->columnSpanFull()
                    ->tabs([

                        // ===================== Información =====================
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
                                    ->unique(ignoreRecord: true)
                                    ->rule('alpha_dash')
                                    ->maxLength(200)
                                    ->helperText('Se autogenera desde el título; puedes ajustarlo.')
                                    ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Str::slug($state) : null),
                            ]),

                            Textarea::make('summary')
                                ->label('Resumen')
                                ->rows(3)
                                ->columnSpanFull(),

                            MarkdownEditor::make('body_md')
                                ->label('Descripción')
                                ->columnSpanFull(),
                        ]),

                        // ===================== Relaciones =====================
                        Tab::make('Relaciones')->schema([
                            Grid::make(2)->schema([
                                Select::make('skills')
                                    ->label('Skills')
                                    ->multiple()
                                    ->relationship('skills', 'name')
                                    ->searchable()
                                    ->preload(),

                                Select::make('tags')
                                    ->label('Tags')
                                    ->multiple()
                                    ->relationship('tags', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                        ]),

                        // ===================== Publicación =====================
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
                                        'draft'     => 'Borrador',
                                        'published' => 'Publicado',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                DateTimePicker::make('published_at')
                                    ->label('Fecha de publicación')
                                    ->native(false),
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

                        // ===================== SEO =====================
                        Tab::make('SEO')->schema([
                            FileUpload::make('og_image_url')
                                ->label('Imagen OG')
                                ->image()
                                ->disk('public')
                                ->directory('projects/og/' . date('Y/m/d'))
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
