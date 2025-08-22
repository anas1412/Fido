<x-filament::page>
    {{ $this->form }}

    <!-- Actions -->
    <div class="mt-6 flex justify-end gap-3">
        <x-filament::button wire:click="save" type="button" color="primary">
            Save
        </x-filament::button>
    </div>
</x-filament::page>
