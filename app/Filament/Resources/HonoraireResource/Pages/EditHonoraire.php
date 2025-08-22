<?php

namespace App\Filament\Resources\HonoraireResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\HonoraireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHonoraire extends EditRecord
{
    protected static string $resource = HonoraireResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return __('Edit Honoraire');
    }
}
