{{-- resources/views/filament/admin/pages/edit-profile.blade.php --}}
<x-filament-panels::page>
    <form wire:submit.prevent="save" class="space-y-6">
        {{ $this->form }}
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
