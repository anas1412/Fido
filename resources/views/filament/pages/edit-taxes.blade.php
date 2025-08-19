{{-- resources/views/filament/pages/edit-taxes.blade.php --}}

<x-filament::page>
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
        <x-filament::card>
            <x-slot name="header">
                <h2 class="text-lg font-medium text-gray-900">TVA Rate</h2>
            </x-slot>
            <div class="flex items-baseline">
                <span class="text-3xl font-semibold text-primary-600">{{ number_format($this->tva * 100, 2) }}%</span>
                <span class="ml-2 text-sm text-gray-600">Pourcentage de TVA</span>
            </div>
        </x-filament::card>

        <x-filament::card>
            <x-slot name="header">
                <h2 class="text-lg font-medium text-gray-900">RS Value</h2>
            </x-slot>
            <div class="flex items-baseline">
                <span class="text-3xl font-semibold text-primary-600">{{ number_format($this->rs * 100, 2) }}%</span>
                <span class="ml-2 text-sm text-gray-600">Pourcentage de RS</span>
            </div>
        </x-filament::card>

        <x-filament::card>
            <x-slot name="header">
                <h2 class="text-lg font-medium text-gray-900">TF Value</h2>
            </x-slot>
            <div class="flex items-baseline">
                <span class="text-3xl font-semibold text-primary-600">{{ number_format($this->tf * 1000, 0) }}</span>
                <span class="ml-2 text-sm text-gray-600">Millimes (Timbre Fiscale)</span>
            </div>
        </x-filament::card>
    </div>

    <x-filament::card>
        <x-slot name="header">
            <h2 class="text-lg font-medium text-gray-900">Modifier les taux</h2>
        </x-slot>

        <form wire:submit.prevent="save" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end">
                <x-filament-actions::actions :actions="$this->getFormActions()" />
            </div>
        </form>
    </x-filament::card>
</x-filament::page>
