<?php

namespace App\Filament\Resources\HonoraireReportResource\Pages;

use App\Models\TaxSetting;
use App\Models\CompanySetting;
use App\Filament\Resources\HonoraireReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Client;
use App\Models\Honoraire;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms;

use Filament\Forms\Components\Builder;

class ListHonoraireReports extends ListRecords
{
    protected static string $resource = HonoraireReportResource::class;

    protected static ?string $title = "Rapport des honoraires";

    protected function getHeaderActions(): array
    {
        return [
            Action::make('printReport')
                ->label("Imprimer Rapport")
                ->color('info')
                ->icon('heroicon-o-printer')
                ->url(function (\Filament\Resources\Pages\ListRecords $livewire): string {
                    $filterData = $livewire->getTableFiltersForm()->getState(); // Get the state of the filter form

                    $startDate = $filterData['date_range']['start_date'] ?? null;
                    $endDate = $filterData['date_range']['end_date'] ?? null;

                    if (!$startDate || !$endDate) {
                        return '#'; // Return a placeholder if dates are not selected, though required() should prevent this.
                    }

                    return route('pdf.honoraire-report', ['start_date' => $startDate, 'end_date' => $endDate]);
                })
                ->openUrlInNewTab()
        ];
    }
}
