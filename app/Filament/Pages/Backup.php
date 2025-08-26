<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

// CORE FILAMENT IMPORTS
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

// We only need the generic Action class
use Filament\Actions\Action;

class Backup extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithActions;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-server';
    protected static ?string $navigationLabel = 'Database Backups'; // Will be translated below
    protected static ?string $title = 'Database Backups'; // Will be translated below
    protected string $view = 'filament.pages.backup';
    protected static ?int $navigationSort = 90;

    // --- TRANSLATION HOOKS ---
    public static function getNavigationLabel(): string
    {
        return __('Database Backups');
    }

    public function getTitle(): string
    {
        return __('Database Backups');
    }
    // --- END TRANSLATION HOOKS ---

    public static function canView(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function getNavigationGroup(): string
    {
        return __('Admin Area');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createBackup')
                ->label(__('Create New Backup')) // MODIFIED
                ->icon('heroicon-o-plus')
                ->action(fn() => $this->createBackup()),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->records(fn () => $this->getBackups())
            ->columns([
                TextColumn::make('name')->label(__('File Name'))->searchable()->sortable(), // MODIFIED
                TextColumn::make('size')->label(__('Size')), // MODIFIED
                TextColumn::make('date')->label(__('Date'))->dateTime()->sortable(), // MODIFIED
            ])
            ->actions([
                Action::make('download')
                    ->label(__('Download')) // MODIFIED
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn(array $record): ?StreamedResponse => $this->downloadBackup($record['name'])),

                Action::make('apply')
                    ->label(__('Apply')) // MODIFIED
                    ->icon('heroicon-o-check-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading(__('Apply Backup')) // MODIFIED
                    ->modalDescription(__('Are you sure you want to apply this backup? This action overwrites the current database and cannot be undone. You must restart the application afterwards.')) // MODIFIED
                    ->modalSubmitActionLabel(__('Yes, Apply')) // MODIFIED
                    ->action(fn(array $record) => $this->applyBackup($record['name'])),

                Action::make('delete')
                    ->label(__('Delete')) // MODIFIED
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (array $record) {
                        $this->deleteBackup($record['name']);
                    })
                    ->after(fn () => $this->js('window.location.reload()')),
            ])
            ->emptyStateHeading(__('No backups found')) // MODIFIED
            ->emptyStateDescription(__('Click the "Create New Backup" button to get started.')) // MODIFIED
            ->emptyStateIcon('heroicon-o-circle-stack');
    }

    public function deleteBackup(string $fileName): void
    {
        $disk = 'local';
        $path = "backups/{$fileName}";
        if (!Storage::disk($disk)->exists($path)) {
            Notification::make()->title(__('Backup file not found.'))->danger()->send(); // MODIFIED
            return;
        }
        Storage::disk($disk)->delete($path);
        Notification::make()->title(__('Backup deleted successfully.'))->success()->send(); // MODIFIED
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
            Notification::make()->title(__('Database file not found.'))->danger()->send(); // MODIFIED
            return;
        }
        $backupDisk = 'local';
        $backupPath = 'backups';
        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $fileName = "backup-{$timestamp}.sqlite";
        try {
            Storage::disk($backupDisk)->put("{$backupPath}/{$fileName}", File::get($databasePath));
            Notification::make()->title(__('Backup created successfully.'))->success()->send(); // MODIFIED
        } catch (\Throwable $e) {
            Notification::make()->title(__('Backup creation failed.'))->body($e->getMessage())->danger()->send(); // MODIFIED
        }
    }

    public function downloadBackup(string $fileName): ?StreamedResponse
    {
        $disk = 'local';
        $path = "backups/{$fileName}";
        if (!Storage::disk($disk)->exists($path)) {
            Notification::make()->title(__('Backup file not found.'))->danger()->send(); // MODIFIED
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
            Notification::make()->title(__('Backup file not found.'))->danger()->send(); // MODIFIED
            return;
        }
        try {
            $dir = dirname($currentDatabasePath);
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true, true);
            }
            File::copy(Storage::disk($disk)->path($backupPath), $currentDatabasePath);
            Notification::make()
                ->title(__('Backup applied successfully!')) // MODIFIED
                ->body(__('IMPORTANT: You must RESTART the application for changes to take effect.')) // MODIFIED
                ->success()->persistent()->send();
        } catch (\Throwable $e) {
            Notification::make()->title(__('Error applying backup'))->body($e->getMessage())->danger()->send(); // MODIFIED
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