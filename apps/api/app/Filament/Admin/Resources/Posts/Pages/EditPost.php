<?php

namespace App\Filament\Admin\Resources\Posts\Pages;

use App\Filament\Admin\Resources\Posts\PostResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;


class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Generar/renovar link de vista previa temporal
            Actions\Action::make('preview')
                ->label('Vista previa')
                ->icon(Heroicon::Eye)
                ->color('gray')
                ->modalHeading('Generar link de vista previa')
                ->schema([
                    TextInput::make('ttl')
                        ->label('Vigencia (horas)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(168)
                        ->default(48),
                ])
                ->action(function (array $data): void {
                    $post   = $this->getRecord();            
                    $userId = Filament::auth()->id();          // id del usuario actual

                    $ttl       = (int) ($data['ttl'] ?? 48);
                    $expiresAt = now()->addHours($ttl);

                    // Obtén un token activo (si existe) o crea uno nuevo.
                    $token = $post->previewTokens()
                        ->where('expires_at', '>', now())
                        ->latest('expires_at')
                        ->first();

                    if ($token) {
                        $token->update([
                            'expires_at' => $expiresAt,
                            'created_by' => $userId,
                        ]);
                    } else {
                        $token = $post->previewTokens()->create([
                            'token'      => (string) Str::uuid(),
                            'expires_at' => $expiresAt,
                            'created_by' => $userId,
                        ]);
                    }

                    $url = route('preview.show', ['token' => $token->token]);

                    Notification::make()
                        ->title('Link de vista previa listo')
                        ->body("Comparte este link (expira en {$ttl} h):\n{$url}")
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
