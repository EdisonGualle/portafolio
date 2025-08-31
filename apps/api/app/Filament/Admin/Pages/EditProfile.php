<?php

namespace App\Filament\Admin\Pages;

use App\Models\Profile;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Actions as SchemaActions;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

use Filament\Support\Enums\Alignment;

class EditProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Editar Perfil';
    protected string $view = 'filament.admin.pages.edit-profile';

    public ?array $data = [];
    public Profile $profile;

    public function mount(): void
    {
        $this->profile = Profile::firstOrCreate([], []);
        $this->form->fill($this->profile->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // ===== PERFIL
                Section::make('Perfil')
                    ->description('Nombre, rol, biografía y foto.')
                    ->columns(3)
                    ->schema([
                        Section::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(150),

                                TextInput::make('role')
                                    ->label('Rol')
                                    ->required()
                                    ->maxLength(150),

                                Textarea::make('bio_md')
                                    ->label('Biografía (Markdown)')
                                    ->rows(6)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(2),

                        Section::make('Foto de perfil')
                            ->schema([
                                FileUpload::make('photo_url')
                                    ->label('Foto de perfil')
                                    ->disk('public')
                                    ->directory('profile')
                                    ->visibility('public')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->previewable(true)
                                    ->openable()
                                    ->downloadable(),
                            ])
                            ->columnSpan(1),
                    ]),

                // ===== CONTACTO
                Section::make('Contacto')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->columnSpanFull(),

                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel(),

                        TextInput::make('location')
                            ->label('Ubicación'),
                    ]),

                // ===== REDES
                Section::make('Redes sociales')
                    ->description('Añade enlaces a tus perfiles públicos.')
                    ->schema([
                        Repeater::make('socials_json')
                            ->addActionLabel('Añadir red social')
                            ->columns(12)
                            ->schema([
                                TextInput::make('platform')
                                    ->label('Plataforma')
                                    ->placeholder('GitHub / LinkedIn / X')
                                    ->maxLength(50)
                                    ->columnSpan(4),

                                TextInput::make('url')
                                    ->label('Enlace')
                                    ->placeholder('https://...')
                                    ->url()
                                    ->columnSpan(8),
                            ])
                            ->default([])
                            ->reorderable()
                            ->collapsible(),
                    ]),

                // ===== ACCIONES DEL FORM
                SchemaActions::make([
                    Action::make('save')
                        ->label('Guardar cambios')
                        ->icon('heroicon-m-check')
                        ->color('primary')
                        ->keyBindings(['mod+s'])
                        ->action(fn() => $this->save()),

                    Action::make('volver') // ← evita colisión con “cancel”
                        ->label('Cancelar')
                        ->icon('heroicon-m-x-mark')
                        ->color('gray')
                        ->outlined()
                        ->action(fn() => $this->cancel()),
                ])
                    ->alignment(Alignment::End)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function cancel(): void
    {
        $this->form->fill($this->profile->attributesToArray());
        $this->redirect(ViewProfile::getUrl(), navigate: true);
    }

    public function save(): void
    {
        $this->profile->update($this->form->getState());

        Notification::make()
            ->success()
            ->title('Perfil actualizado correctamente')
            ->send();

        $this->redirect(ViewProfile::getUrl(), navigate: true);
    }
}
