<x-filament-panels::page>
    <x-filament::card>
        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="space-y-4">
                {{ $this->form }}
            </div>

            <x-filament::button type="submit" class="w-full mt-4 md:w-auto">
                {{ __('Mettre à jour l\'année de l\'exercice') }}
            </x-filament::button>
        </form>
    </x-filament::card>
</x-filament-panels::page>
