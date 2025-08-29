<?php

namespace App\Filament\Admin\Pages;

use App\Models\Profile;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

// v4: Schemas
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Support\Enums\Alignment;

// Infolists
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;

// Actions v4
use Filament\Actions\Action;

class ViewProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static ?string $title = 'Perfil';
    protected static ?string $navigationLabel = 'Perfil';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::User;

    protected string $view = 'filament.admin.pages.view-profile';

    public Profile $profile;

    public function mount(): void
    {
        $this->profile = Profile::firstOrFail();
    }

    public function profileInfolist(Schema $schema): Schema
    {
        return $schema
            ->record($this->profile)
            ->components([
                // ===== PERFIL
                Section::make('Perfil')
                    ->description('Nombre, rol, biografía y foto.')
                    ->schema([
                        Grid::make(3)->schema([
                            Section::make()
                                ->columns(2)
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Nombre')
                                        ->placeholder('No disponible'), 
                                    TextEntry::make('role')
                                        ->label('Rol')
                                        ->placeholder('No disponible'),
                                    TextEntry::make('bio_md')
                                        ->label('Biografía')
                                        ->markdown() 
                                        ->placeholder('No disponible')
                                        ->columnSpanFull(),
                                ])
                                ->columnSpan(2),

                            Section::make()
                                ->schema([
                                    ImageEntry::make('photo_url')
                                        ->label('Foto de perfil')
                                        ->disk('public')      
                                        
                                        // opcional: usa tu propia imagen si no hay foto
                                        // ->defaultImageUrl(url('/images/profile-placeholder.png'))
                                        ->placeholder('No disponible')
                                        
                                ])
                                ->columnSpan(1),
                        ]),
                    ]),

                // ===== CONTACTO
                Section::make('Contacto')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('email')
                            ->label('Correo electrónico')
                            ->placeholder('No disponible')
                            ->columnSpanFull(),
                        TextEntry::make('phone')
                            ->label('Teléfono')
                            ->placeholder('No disponible'),
                        TextEntry::make('location')
                            ->label('Ubicación')
                            ->placeholder('No disponible'),
                    ]),

                // ===== REDES SOCIALES
                Section::make('Redes sociales')
                    ->description('Perfiles públicos.')
                    ->schema([
                        RepeatableEntry::make('socials_json')
                            ->label('Listado')
                            ->schema([
                                TextEntry::make('platform')
                                    ->label('Plataforma')
                                    ->placeholder('—')
                                    ->columnSpan(3),
                                TextEntry::make('url')
                                    ->label('Enlace')
                                    ->url(fn (?string $state) => $state ?: null)
                                    ->openUrlInNewTab()
                                    ->placeholder('—')
                                    ->columnSpan(9),
                            ])
                            ->columns(12)
                            ->columnSpanFull(),
                    ]),

                // ===== ACCIÓN: EDITAR
                SchemaActions::make([
                    Action::make('edit')
                        ->label('Editar perfil')
                        ->icon('heroicon-m-pencil-square')
                        ->color('primary')
                        ->url(fn () => route('filament.admin.pages.edit-profile')),
                ])
                    ->alignment(Alignment::End)
                    ->columnSpanFull(),
            ]);
    }
}
