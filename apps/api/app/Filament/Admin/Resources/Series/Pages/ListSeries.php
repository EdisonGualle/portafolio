<?php

namespace App\Filament\Admin\Resources\Series\Pages;

use App\Filament\Admin\Resources\Series\SeriesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeries extends ListRecords
{
    protected static string $resource = SeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear serie')
                ->icon('heroicon-m-plus'),
        ];
    }
}
