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
            Action::make('pdf')
                ->label('PDF')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn(Invoice $record) => route('pdf.invoice', ['invoice' => $record->id])),

            EditAction::make()->visible(!auth()->user()?->is_demo),
            DeleteAction::make()->visible(!auth()->user()?->is_demo),
        ];
    }

    public function getTitle(): string
    {
        return __('View Invoice');
    }
}

