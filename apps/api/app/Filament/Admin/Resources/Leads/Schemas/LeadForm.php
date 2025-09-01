<?php

namespace App\Filament\Admin\Resources\Leads\Schemas;

use Filament\Forms\Components\DateTimePicker;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Grid::make(2)->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),

                TextInput::make('subject')
                    ->label('Asunto'),

                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'new'     => 'Nuevo',
                        'read'    => 'Leído',
                        'replied' => 'Respondido',
                    ])
                    ->required()
                    ->native(false),
            ])->columnSpanFull(),


            Textarea::make('message')
                ->label('Mensaje')
                ->required()
                ->columnSpanFull(),

            Grid::make(2)->schema([
                TextInput::make('source')
                    ->label('Fuente')
                    ->placeholder('Ej: Campaña LinkedIn'),

                TextInput::make('ip_address')
                    ->label('IP'),

                KeyValue::make('utm_json')
                    ->label('UTM Params')
                    ->keyLabel('Parámetro')
                    ->valueLabel('Valor')
                    ->columnSpanFull()
                    ->addActionLabel('Añadir parámetro'),

                FileUpload::make('attachments')
                    ->label('Adjuntos')
                    ->multiple()
                    ->disk('public')
                    ->directory('leads/attachments')
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    ->previewable()
                    ->columnSpanFull()
                    ->default([]),


                Textarea::make('user_agent')
                    ->label('User Agent')
                    ->rows(2),

                Textarea::make('note')
                    ->label('Nota interna')
                    ->placeholder('Añade observaciones de seguimiento...'),


                DateTimePicker::make('processed_at')
                    ->label('Procesado en'),
            ])->columnSpanFull(),

        ]);
    }
}
