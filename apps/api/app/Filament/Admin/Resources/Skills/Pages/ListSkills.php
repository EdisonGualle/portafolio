<?php

namespace App\Filament\Admin\Resources\Skills\Pages;

use App\Filament\Admin\Resources\Skills\SkillResource;
use App\Filament\Admin\Resources\SkillCategories\SkillCategoryResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSkills extends ListRecords
{
    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manageCategories')
                ->label('Gestionar categorías')
                ->icon('heroicon-m-folder-open')
                ->color('gray')
                ->outlined()
                ->url(SkillCategoryResource::getUrl('index')),

            CreateAction::make()
                ->label('Crear skill')
                ->icon('heroicon-m-plus'),
        ];
    }
}
