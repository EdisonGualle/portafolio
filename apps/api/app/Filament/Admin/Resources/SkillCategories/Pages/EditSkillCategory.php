<?php

namespace App\Filament\Admin\Resources\SkillCategories\Pages;

use App\Filament\Admin\Resources\SkillCategories\SkillCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSkillCategory extends EditRecord
{
    protected static string $resource = SkillCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
