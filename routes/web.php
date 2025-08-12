<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Form;
use App\Http\Controllers\PdfController;


Route::redirect('/', '/dashboard');

/* Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
 */
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

use App\Http\Controllers\NoteDeDebitPdfController;

Route::get('pdf/{honoraire}', PdfController::class)->name('pdf')
    ->middleware(['auth']);

Route::get('pdf-note-de-debit/{noteDeDebit}', NoteDeDebitPdfController::class)->name('pdf.note-de-debit')
    ->middleware(['auth']);

Route::post('pdf/retenue-source', [PdfController::class, 'generateRetenueSourcReport'])
    ->name('pdf.retenue-source')
    ->middleware(['auth']);


require __DIR__ . '/auth.php';
