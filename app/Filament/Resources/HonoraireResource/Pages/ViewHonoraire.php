<?php

namespace App\Filament\Resources\HonoraireResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\HonoraireResource;
use App\Models\Honoraire;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewHonoraire extends ViewRecord
{
    protected static string $resource = HonoraireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('pdf')
                ->label('PDF') 
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn(Honoraire $record) => route('pdf', $record)),

            EditAction::make()->visible(!auth()->user()?->is_demo),
            DeleteAction::make()->visible(!auth()->user()?->is_demo),
        ];
    }

    public function getTitle(): string
    {
        return __('View Honoraire');
    }
}
