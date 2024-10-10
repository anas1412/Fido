<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4">
            Mettre à jour l'année de l'exercice
        </x-filament::button>
    </form>
</x-filament-panels::page>
