<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Form;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\HonoraireReportController;
use App\Http\Controllers\NoteDeDebitPdfController;
use App\Http\Controllers\InvoicePdfController;



Route::redirect('/', '/dashboard');

Route::redirect('/login', '/dashboard/login');

Route::get('/login', function () { return redirect('/dashboard/login'); })->name('login');

/* Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
 */
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


Route::get('pdf/{honoraire}', PdfController::class)->name('pdf')
    ->middleware(['auth']);

Route::get('pdf-note-de-debit/{noteDeDebit}', NoteDeDebitPdfController::class)->name('pdf.note-de-debit')
    ->middleware(['auth']);

Route::get('pdf-invoice/{invoice}', InvoicePdfController::class)->name('pdf.invoice')
    ->middleware(['auth']);

Route::get('pdf-honoraire-report', [PdfController::class, 'generateHonoraireReportPdf'])
    ->name('pdf.honoraire-report')
    ->middleware(['auth']);

Route::get('pdf-note-de-debit-report', [PdfController::class, 'generateNoteDeDebitReportPdf'])
    ->name('pdf.note-de-debit-report')
    ->middleware(['auth']);

Route::get('pdf-retenue-source-report', [PdfController::class, 'generateRetenueSourceReportPdf'])
    ->name('pdf.retenue-source-report')
    ->middleware(['auth']);


require __DIR__ . '/auth.php';
