{{-- resources/views/filament/pages/edit-taxes.blade.php --}}

<x-filament::page>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <x-filament::card>
            <x-slot name="header">
                <div class="text-lg font-medium text-gray-900">TVA Rate</div>
            </x-slot>
            <div class="text-3xl font-semibold">{{ $tva * 100 }}%</div>
            <div class="text-gray-600">Pourcentage de TVA</div>
        </x-filament::card>

        <x-filament::card>
            <x-slot name="header">
                <div class="text-lg font-medium text-gray-900">RS Value</div>
            </x-slot>
            <div class="text-3xl font-semibold">{{ $rs * 100 }}%</div>
            <div class="text-gray-600">Pourcentage de RS</div>
        </x-filament::card>

        <x-filament::card>
            <x-slot name="header">
                <div class="text-lg font-medium text-gray-900">TF Value</div>
            </x-slot>
            <div class="text-3xl font-semibold">{{ $tf * 100 }}%</div>
            <div class="text-gray-600">Pourcentage de Tarif Fiscale</div>
        </x-filament::card>
    </div>

    <form wire:submit.prevent="submit" class="mt-6">
        {{ $this->form }}

        {{-- <x-filament::button type="submit" color="primary" class="mt-4">
            Save
        </x-filament::button> --}}
    </form>
</x-filament::page>
