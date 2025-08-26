<x-filament-panels::page>
    <x-filament::button wire:click="createBackup">
        Create New Backup
    </x-filament::button>

    <div class="mt-4">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">File Name</th>
                        <th scope="col" class="px-6 py-3">Size</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->getBackups() as $backup)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">{{ $backup['name'] }}</td>
                            <td class="px-6 py-4">{{ $backup['size'] }}</td>
                            <td class="px-6 py-4">{{ $backup['date'] }}</td>
                            <td class="px-6 py-4 text-right">
                                <x-filament::button wire:click="downloadBackup('{{ $backup['name'] }}')">
                                    Download
                                </x-filament::button>
                                <x-filament::button color="danger" wire:click="deleteBackup('{{ $backup['name'] }}')">
                                    Delete
                                </x-filament::button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>