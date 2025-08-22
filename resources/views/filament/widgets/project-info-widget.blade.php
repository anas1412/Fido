<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-wrap items-center justify-between gap-4">

            <!-- Left Side: Icon + Project Info -->
            <div class="flex items-center gap-3">
                <x-filament::icon
                    icon="heroicon-o-code-bracket-square"
                    class="h-10 w-10 text-zinc-400 dark:text-zinc-500"
                />

                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                        Fido Project
                    </h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Version {{ $version }}
                    </p>
                </div>
            </div>

            <!-- Right Side: Action Buttons -->
            <div class="flex items-center gap-3">
                <x-filament::button
                    color="success"
                    icon="heroicon-m-envelope"
                    x-on:click="$dispatch('open-modal', { id: 'supportModal' })"
                >
                    Support
                </x-filament::button>

                <x-filament::button
                    tag="a"
                    href="https://fido.tn"
                    target="_blank"
                    color="gray"
                >
                    <x-slot name="icon">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 2c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.68 4.9l-2.12-2.12A5.98 5.98 0 0 0 18 12c0-3.31-2.69-6-6-6s-6 2.69-6 6c0 1.65.67 3.15 1.76 4.24l-2.12 2.12A7.962 7.962 0 0 1 4 12c0-4.42 3.58-8 8-8zm0 4a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"/>
                        </svg>
                    </x-slot>
                    Fido Website
                </x-filament::button>
            </div>

        </div>
    </x-filament::section>

    <!-- Modal for Support -->
    <x-filament::modal id="supportModal" width="md">
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <x-filament::icon
                icon="heroicon-o-question-mark-circle"
                class="h-6 w-6 text-zinc-500 dark:text-zinc-400"
            />
            <span class="text-lg font-semibold text-zinc-900 dark:text-white">
                Support Contact
            </span>
        </div>
    </x-slot>

    <div class="mt-4 space-y-3 text-center">
        <p class="text-lg text-zinc-700 dark:text-zinc-300 flex items-center justify-center gap-2">
            <x-filament::icon icon="heroicon-o-envelope" class="h-5 w-5 text-zinc-500 dark:text-zinc-400" />
            Email: <span class="font-semibold text-zinc-900 dark:text-white">anas.bassoumi@gmail.com</span>
        </p>
        <p class="text-lg text-zinc-700 dark:text-zinc-300 flex items-center justify-center gap-2">
            <x-filament::icon icon="heroicon-o-chat-bubble-bottom-center" class="h-5 w-5 text-zinc-500 dark:text-zinc-400" />
            WhatsApp: <span class="font-semibold text-zinc-900 dark:text-white">+216 50 377 851</span>
        </p>
        <p class="text-lg text-zinc-700 dark:text-zinc-300 flex items-center justify-center gap-2">
            <x-filament::icon icon="heroicon-o-phone" class="h-5 w-5 text-zinc-500 dark:text-zinc-400" />
            GSM: <span class="font-semibold text-zinc-900 dark:text-white">+216 50 377 851</span>
        </p>
    </div>

    <x-slot name="footer">
        <div class="flex justify-end gap-3">
            <x-filament::button
                color="gray"
                x-on:click="$dispatch('close-modal', { id: 'supportModal' })"
            >
                Close
            </x-filament::button>
        </div>
    </x-slot>
</x-filament::modal>

</x-filament-widgets::widget>
