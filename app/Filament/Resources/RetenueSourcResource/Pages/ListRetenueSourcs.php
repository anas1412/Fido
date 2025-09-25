<?php

namespace App\Filament\Resources\RetenueSourcResource\Pages;

use App\Models\CompanySetting;
use App\Helpers\FiscalHelper;
use App\Models\TaxSetting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\RetenueSourcResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Models\Client;
use App\Models\Honoraire;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListRetenueSourcs extends ListRecords
{
    protected static string $resource = RetenueSourcResource::class;

    protected static ?string $title = "Rapport des retenues à la source";

    protected function getHeaderActions(): array
    {
        return [

            Action::make('printReport')
                ->label("Imprimer Rapport")
                ->color('info')
                ->icon('heroicon-o-printer')
                ->url(function (\Filament\Resources\Pages\ListRecords $livewire): string {
                    $filterData = $livewire->getTableFiltersForm()->getState();

                    $startDate = $filterData['date_range']['start_date'] ?? null;
                    $endDate = $filterData['date_range']['end_date'] ?? null;

                    if (!$startDate || !$endDate) {
                        return '#';
                    }

                    return route('pdf.retenue-source-report', ['start_date' => $startDate, 'end_date' => $endDate]);
                })
                ->openUrlInNewTab()
                ->form([
                    DatePicker::make('start_date')
                        ->label('Date de début')
                        ->required()
                        ->default(now()->startOfYear()),
                    DatePicker::make('end_date')
                        ->label('Date de fin')
                        ->required()
                        ->default(now()->endOfYear()),
                ]),
        ];
    }
}