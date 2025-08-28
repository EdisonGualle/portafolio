<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            @if ($profile->photo_url)
                <img src="{{ Storage::url($profile->photo_url) }}" 
                     alt="Foto de perfil" 
                     class="w-24 h-24 rounded-full object-cover">
            @endif
            <div>
                <h2 class="text-xl font-bold">{{ $profile->name }}</h2>
                <p class="text-gray-400">{{ $profile->role }}</p>
            </div>
        </div>

        <div>
            <h3 class="font-semibold">Biografía</h3>
            <p class="text-gray-300">{{ $profile->bio_md }}</p>
        </div>

        <div>
            <h3 class="font-semibold">Contacto</h3>
            <ul class="text-gray-300">
                <li><strong>Email:</strong> {{ $profile->email }}</li>
                <li><strong>Teléfono:</strong> {{ $profile->phone }}</li>
                <li><strong>Ubicación:</strong> {{ $profile->location }}</li>
            </ul>
        </div>

        <div>
            <h3 class="font-semibold">Redes sociales</h3>
            <ul class="text-gray-300">
                @foreach ($profile->socials_json ?? [] as $social)
                    <li>
                        <a href="{{ $social['url'] }}" target="_blank" class="text-primary-500 underline">
                            {{ $social['platform'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Botón para ir a editar --}}
        <div class="flex justify-end">
            <x-filament::button
                tag="a"
                href="{{ route('filament.admin.pages.edit-profile') }}"
                color="primary"
            >
                Editar perfil
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
