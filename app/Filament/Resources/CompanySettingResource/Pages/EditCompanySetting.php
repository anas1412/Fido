<?php

namespace App\Filament\Resources\CompanySettingResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CompanySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCompanySetting extends EditRecord
{
    protected static string $resource = CompanySettingResource::class;

    protected function resolveRecord(string | int $key): Model
    {
        return CompanySettingResource::getRecord();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}