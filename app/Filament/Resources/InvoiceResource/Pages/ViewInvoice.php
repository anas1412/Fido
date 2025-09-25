<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Invoice;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label(__('Print'))
                ->color('info')
                ->icon('heroicon-o-printer')
                ->url(fn(Invoice $record) => route('pdf.invoice', ['invoice' => $record->id]))
                ->openUrlInNewTab(),

            EditAction::make()->visible(!auth()->user()?->is_demo),
            DeleteAction::make()->visible(!auth()->user()?->is_demo),
        ];
    }

    public function getTitle(): string
    {
        return __('View Invoice');
    }
}

