<?php

namespace App\Filament\Admin\Resources\Series\Pages;

use App\Filament\Admin\Resources\Series\SeriesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSeries extends CreateRecord
{
    protected static string $resource = SeriesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
