<?php

namespace App\Filament\Pages;

use App\Rules\IsSqliteFile; // <-- IMPORT THE CUSTOM RULE
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Backup extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithActions;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-server';
    protected static ?string $navigationLabel = 'Database Backups';
    protected static ?string $title = 'Database Backups';
    protected string $view = 'filament.pages.backup';
    protected static ?int $navigationSort = 90;

    public static function getNavigationLabel(): string
    {
        return __('Database Backups');
    }

    public function getTitle(): string | Htmlable
    {
        return __('Database Backups');
    }

    public static function canView(Model $record): bool
{
    return auth()->user()->is_admin || auth()->user()->is_demo;
}


    public static function getNavigationGroup(): string
    {
        return __('Admin Area');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createBackup')
                ->label(__('Create New Backup'))
                ->icon('heroicon-o-plus')
                ->action(fn() => $this->createBackup())
                ->disabled(fn () => auth()->user()?->is_demo)
                ->tooltip(__('Disabled in demo mode')),

            Action::make('importBackup')
                ->label(__('Import & Apply Backup'))
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->form([
                    FileUpload::make('upload')
                        ->label(__('Backup File (.sqlite)'))
                        ->required()
                        // This uses the dedicated Rule class to fix all previous validation errors
                        ->rules(['required', 'file', new IsSqliteFile()])
                ])
                ->requiresConfirmation()
                ->modalHeading(__('Import & Apply Backup'))
                ->modalDescription(__('Are you sure you want to import and apply this backup? This action will OVERWRITE the current database and cannot be undone. You must restart the application afterwards.'))
                ->modalSubmitActionLabel(__('Yes, Import and Apply'))
                ->action(function (array $data) {
                    $this->importAndApplyBackup($data['upload']);
                })
                ->disabled(fn () => auth()->user()?->is_demo)
                ->tooltip(__('Disabled in demo mode')),

            Action::make('formatDatabase')
                ->label(__('Reset Application Data'))
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(__('Reset Application Data'))
                ->modalDescription(__('DANGER: Are you sure you want to reset the application? This action will PERMANENTLY DELETE ALL DATA and restore the database to its default state, including the default admin account. This cannot be undone.'))
                ->modalSubmitActionLabel(__('Yes, I understand, reset the application'))
                ->action(fn() => $this->formatDatabase())
                ->disabled(fn () => auth()->user()?->is_demo)
                ->tooltip(__('Disabled in demo mode')),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(fn () => $this->getBackups())
            ->columns([
                TextColumn::make('name')->label(__('File Name'))->searchable()->sortable(),
                TextColumn::make('size')->label(__('Size')),
                TextColumn::make('date')->label(__('Date'))->dateTime()->sortable(),
            ])
            ->actions([
                Action::make('download')
                    ->label(__('Download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn(array $record): ?StreamedResponse => $this->downloadBackup($record['name'])),

                Action::make('apply')
                    ->label(__('Apply'))
                    ->icon('heroicon-o-check-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading(__('Apply Backup'))
                    ->modalDescription(__('Are you sure you want to apply this backup? This action overwrites the current database and cannot be undone. You must restart the application afterwards.'))
                    ->modalSubmitActionLabel(__('Yes, Apply'))
                    ->action(fn(array $record) => $this->applyBackup($record['name']))
                    ->visible(!auth()->user()?->is_demo),

                Action::make('delete')
                    ->label(__('Delete'))
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (array $record) {
                        $this->deleteBackup($record['name']);
                    })
                    ->after(fn () => $this->js('window.location.reload()'))
                    ->visible(!auth()->user()?->is_demo),
            ])
            ->emptyStateHeading(__('No backups found'))
            ->emptyStateDescription(__('Click the "Create New Backup" button to get started.'))
            ->emptyStateIcon('heroicon-o-circle-stack');
    }

    /**
     * This method correctly handles the temporary file path (string) from Livewire.
     */
    public function importAndApplyBackup(string $uploadedFile): void
    {
        $currentDatabasePath = config('database.connections.sqlite.database');
        $temporaryFilePath = Storage::disk('local')->path($uploadedFile);

        try {
            $dir = dirname($currentDatabasePath);
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true, true);
            }
            File::move($temporaryFilePath, $currentDatabasePath);

            Notification::make()
                ->title(__('Backup imported and applied successfully!'))
                ->body(__('IMPORTANT: You must RESTART the application for changes to take effect.'))
                ->success()->persistent()->send();
        } catch (\Throwable $e) {
            Notification::make()->title(__('Error importing backup'))->body($e->getMessage())->danger()->send();
        }
    }

    /**
     * This method safely resets the database using Artisan commands.
     */
    public function formatDatabase(): void
    {
        try {
            Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
            Artisan::call('seed:admin');

            Notification::make()
                ->title(__('Database formatted successfully!'))
                ->body(__('The database has been reset to its default state.'))
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title(__('Error formatting database'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function deleteBackup(string $fileName): void
    {
        $disk = 'local';
        $path = "backups/{$fileName}";
        if (!Storage::disk($disk)->exists($path)) {
            Notification::make()->title(__('Backup file not found.'))->danger()->send();
            return;
        }
        Storage::disk($disk)->delete($path);
        Notification::make()->title(__('Backup deleted successfully.'))->success()->send();
    }

    public function getBackups(): Collection
    {
        $backupDisk = 'local';
        $backupPath = 'backups';
        if (!Storage::disk($backupDisk)->exists($backupPath)) {
            Storage::disk($backupDisk)->makeDirectory($backupPath);
        }
        return collect(Storage::disk($backupDisk)->files($backupPath))
            ->filter(fn($file) => basename($file) !== '.gitignore' && pathinfo($file, PATHINFO_EXTENSION) === 'sqlite')
            ->map(function ($file) use ($backupDisk) {
                return [
                    'name' => basename($file),
                    'size' => $this->formatBytes(Storage::disk($backupDisk)->size($file)),
                    'date' => Carbon::createFromTimestamp(Storage::disk($backupDisk)->lastModified($file)),
                ];
            })->sortByDesc('date')->values();
    }

    public function createBackup(): void
    {
        $databasePath = config('database.connections.sqlite.database');
        if (!File::exists($databasePath)) {
            Notification::make()->title(__('Database file not found.'))->danger()->send();
            return;
        }
        $backupDisk = 'local';
        $backupPath = 'backups';
        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $fileName = "backup-{$timestamp}.sqlite";
        try {
            Storage::disk($backupDisk)->put("{$backupPath}/{$fileName}", File::get($databasePath));
            Notification::make()->title(__('Backup created successfully.'))->success()->send();
        } catch (\Throwable $e) {
            Notification::make()->title(__('Backup creation failed.'))->body($e->getMessage())->danger()->send();
        }
    }

    public function downloadBackup(string $fileName): ?StreamedResponse
    {
        $disk = 'local';
        $path = "backups/{$fileName}";
        if (!Storage::disk($disk)->exists($path)) {
            Notification::make()->title(__('Backup file not found.'))->danger()->send();
            return null;
        }
        return Storage::disk($disk)->download($path);
    }

    public function applyBackup(string $fileName): void
    {
        $disk = 'local';
        $backupPath = "backups/{$fileName}";
        $currentDatabasePath = config('database.connections.sqlite.database');
        if (!Storage::disk($disk)->exists($backupPath)) {
            Notification::make()->title(__('Backup file not found.'))->danger()->send();
            return;
        }
        try {
            $dir = dirname($currentDatabasePath);
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true, true);
            }
            File::copy(Storage::disk($disk)->path($backupPath), $currentDatabasePath);
            Notification::make()
                ->title(__('Backup applied successfully!'))
                ->body(__('IMPORTANT: You must RESTART the application for changes to take effect.'))
                ->success()->persistent()->send();
        } catch (\Throwable $e) {
            Notification::make()->title(__('Error applying backup'))->body($e->getMessage())->danger()->send();
        }
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes === 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $pow = (int) floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}