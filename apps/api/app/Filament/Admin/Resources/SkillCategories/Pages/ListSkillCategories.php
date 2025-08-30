<?php

namespace App\Filament\Admin\Resources\SkillCategories\Pages;

use App\Filament\Admin\Resources\SkillCategories\SkillCategoryResource;
use App\Filament\Admin\Resources\Skills\SkillResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSkillCategories extends ListRecords
{
    protected static string $resource = SkillCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('manageSkills')
                ->label('Gestionar skills')
                ->icon('heroicon-m-sparkles')
                ->color('gray')
                ->outlined()
                ->url(SkillResource::getUrl('index')),

            CreateAction::make()
                ->label('Crear categoría')
                ->icon('heroicon-m-plus'),
        ];
    }
}
