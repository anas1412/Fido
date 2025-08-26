<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class Backup extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-server';

    protected static ?string $navigationLabel = 'Database Backups';

    protected static ?string $title = 'Database Backups';

    protected string $view = 'filament.pages.backup';

    protected static ?int $navigationSort = 90;

    public static function canView(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function getNavigationGroup(): string
    {
        return __('Admin Area');
    }

    public function createBackup()
    {
        $databasePath = database_path('database.sqlite');
        if (!File::exists($databasePath)) {
            Notification::make()
                ->title('Database file not found.')
                ->danger()
                ->send();
            return;
        }

        $backupDisk = 'local'; // Using the local disk
        $backupPath = 'backups';
        $timestamp = Carbon::now()->format('Y-m-d-H-i-s');
        $fileName = "backup-{$timestamp}.sqlite";

        Storage::disk($backupDisk)->put("{$backupPath}/{$fileName}", File::get($databasePath));

        Notification::make()
            ->title('Backup created successfully.')
            ->success()
            ->send();
    }

    public function getBackups()
    {
        $backupDisk = 'local';
        $backupPath = 'backups';
        $files = Storage::disk($backupDisk)->files($backupPath);

        return collect($files)
            ->filter(function ($file) {
                return basename($file) !== '.gitignore';
            })
            ->map(function ($file) use ($backupDisk) {
                return [
                    'name' => basename($file),
                    'size' => $this->formatBytes(Storage::disk($backupDisk)->size($file)),
                    'date' => Carbon::createFromTimestamp(Storage::disk($backupDisk)->lastModified($file))->toDateTimeString(),
                ];
            })->sortByDesc('date');
    }

    public function downloadBackup($fileName)
    {
        $backupDisk = 'local';
        $backupPath = 'backups';
        $filePath = "{$backupPath}/{$fileName}";

        if (!Storage::disk($backupDisk)->exists($filePath)) {
            Notification::make()
                ->title('Backup file not found.')
                ->danger()
                ->send();
            return;
        }

        return Storage::disk($backupDisk)->download($filePath);
    }

    public function deleteBackup($fileName)
    {
        $backupDisk = 'local';
        $backupPath = 'backups';
        $filePath = "{$backupPath}/{$fileName}";

        if (!Storage::disk($backupDisk)->exists($filePath)) {
            Notification::make()
                ->title('Backup file not found.')
                ->danger()
                ->send();
            return;
        }

        Storage::disk($backupDisk)->delete($filePath);

        Notification::make()
            ->title('Backup deleted successfully.')
            ->success()
            ->send();
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}