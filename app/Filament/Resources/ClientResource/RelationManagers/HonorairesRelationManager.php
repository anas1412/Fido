<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use App\Models\TaxSetting;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Honoraire;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Carbon;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Model;

class HonorairesRelationManager extends RelationManager
{
    protected static string $relationship = 'honoraires';

    public function isReadOnly(): bool
    {
        return false;
    }

    /*     public static function getEloquentQuery(): Builder
    {
        $fiscalYear = config('fiscal_year.current_year');
        return parent::getEloquentQuery()->whereYear('date', $fiscalYear);
    }
 */
    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Honoraires');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Honoraires');
    }

    public static function getModelLabel(): string
    {
        return __('Honoraire');
    }


    public function form(Schema $schema): Schema
    {


        return $schema
            ->components([
                TextInput::make('note')
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                DatePicker::make('date')
                    ->label("Date d'honoraire")
                    /* ->default(Carbon::createFromDate(config('fiscal_year.current_year'), 1, 1)) */
                    ->default(now()->toDateString()),
                TextInput::make('object')
                    ->label("Objet d'honoraire"),
                TextInput::make('montantHT')
                    ->label("Montant H.T")
                    ->live(onBlur: true)
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $taxSettings = TaxSetting::first();
                            $newTva = $get('exonere_tva') ? 0 : ($get('montantHT') * $taxSettings->tva);

                            $newMontantTTC = $get('montantHT') + $newTva;
                            $newRs = $get('exonere_rs') ? 0 : ($newMontantTTC * $taxSettings->rs);
                            $newTf = $get('exonere_tf') ? 0 : $taxSettings->tf;
                            $newNetapayer = $newMontantTTC - $newRs + $newTf;

                            $currentYear = date('Y');
                            $count = Honoraire::count() + 1; // Start from count + 1 for the new note

                            // Generate a new unique note reference
                            do {
                                $newNote = str_pad($count, 4, '0', STR_PAD_LEFT) . $currentYear;
                                $count++; // Increment for the next check if it already exists
                            } while (Honoraire::where('note', $newNote)->exists()); // Check for uniqueness



                            $newObject = "Assistance comptable de l'année $currentYear";

                            $set('tva', $newTva);
                            $set('montantTTC', $newMontantTTC);
                            $set('rs', $newRs);
                            $set('tf', $newTf);
                            $set('netapayer', $newNetapayer);
                            $set('note', $newNote);
                            $set('object', $newObject);
                        }
                    }),

                TextInput::make('montantTTC')
                    ->label("Montant T.T.C"),
                TextInput::make('netapayer')
                    ->label("Net à Payer"),
                TextInput::make('tva')
                    ->label("T.V.A"),

                TextInput::make('rs')
                    ->label("R/S"),
                TextInput::make('tf')
                    ->label("Timbre Fisacle"),
                Toggle::make('exonere_tva')
                    ->label('Exonération TVA')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $taxSettings = TaxSetting::first();
                        $newTva = $state ? 0 : ($get('montantHT') * $taxSettings->tva);
                        $newMontantTTC = $get('montantHT') + $newTva;
                        $newRs = $get('exonere_rs') ? 0 : ($newMontantTTC * $taxSettings->rs);
                        $set('tva', $newTva);
                        $set('montantTTC', $newMontantTTC);
                        $set('rs', $newRs);
                        $set('netapayer', $newMontantTTC - $newRs + $get('tf'));
                    }),
                Toggle::make('exonere_rs')
                    ->label('Exonération RS')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $taxSettings = TaxSetting::first();
                        $newRs = $state ? 0 : ($get('montantTTC') * $taxSettings->rs);
                        $set('rs', $newRs);
                        $set('netapayer', $get('montantTTC') - $newRs + $get('tf'));
                    }),
                Toggle::make('exonere_tf')
                    ->label('Exonération TF')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $newTf = $state ? 0 : config('taxes.tf');
                        $set('tf', $newTf);
                        $set('netapayer', $get('montantTTC') - $get('rs') + $newTf);
                    }),
            ])->columns(3);
    }



    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->whereYear('date', config('fiscal_year.current_year')))
            ->recordTitleAttribute('note')
            ->columns([
                TextColumn::make('note')
                    ->sortable()
                    ->label("Note d'honoraire")
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    })
                    ->searchable(),
                TextColumn::make('object')
                    ->label("Objet d'honoraire"),
                TextColumn::make('date')
                    ->label("Date d'honoraire")
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->visible(!auth()->user()?->is_demo),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('pdf')
                        ->label('PDF')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn(Honoraire $record) => route('pdf', $record)),

                    EditAction::make()->visible(!auth()->user()?->is_demo),
                    DeleteAction::make()->visible(!auth()->user()?->is_demo),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(!auth()->user()?->is_demo),
                ]),
            ]);
    }
}
