<x-filament::page>
    <div class="grid grid-cols-1 gap-6 mb-8">
        <x-filament::card>
            <x-slot name="header">
                <h2 class="text-lg font-medium text-gray-900">Année Fiscale Actuelle</h2>
            </x-slot>
            <div class="flex items-baseline">
                <span class="text-3xl font-semibold text-primary-600">{{ $this->year }}</span>
                <span class="ml-2 text-sm text-gray-600">Année Fiscale</span>
            </div>
        </x-filament::card>
    </div>

    <x-filament::card>
        <x-slot name="header">
            <h2 class="text-lg font-medium text-gray-900">Modifier l'Année Fiscale</h2>
        </x-slot>

        <form wire:submit.prevent="save" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end gap-3">
                <x-filament::button wire:click="save" type="button" color="primary">
                    Save
                </x-filament::button>
            </div>
        </form>
    </x-filament::card>
</x-filament::page>
