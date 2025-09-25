<?php

namespace App\Filament\Resources\NoteDeDebitReportResource\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\NoteDeDebitReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Models\Client;
use App\Models\NoteDeDebit;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;

class ListNoteDeDebitReports extends ListRecords
{
    protected static string $resource = NoteDeDebitReportResource::class;

    protected static ?string $title = "Rapport des note des debits";

    protected static ?int $navigationSort = 60;

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

                    return route('pdf.note-de-debit-report', ['start_date' => $startDate, 'end_date' => $endDate]);
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
