<?php

namespace App\Filament\Admin\Pages;

use App\Models\Profile;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ViewProfile extends Page
{
    protected static ?string $title = 'Perfil';
     protected static ?string $navigationLabel = 'Perfil';
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::User;

    protected string $view = 'filament.admin.pages.view-profile';

    public Profile $profile;

    public function mount(): void
    {
        $this->profile = Profile::firstOrFail();
    }
}
