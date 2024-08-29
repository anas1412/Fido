<?php

namespace App\Filament\Resources\HonoraireResource\Pages;

use App\Filament\Resources\HonoraireResource;
use App\Models\Honoraire;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHonoraire extends ViewRecord
{
    protected static string $resource = HonoraireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('PDF')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn(Honoraire $record) => route('pdf', $record))
                ->openUrlInNewTab(),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
