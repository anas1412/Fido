<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
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


    public function form(Form $form): Form
    {


        return $form
            ->schema([
                Forms\Components\TextInput::make('note')
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->label("Date d'honoraire")
                    /* ->default(Carbon::createFromDate(config('fiscal_year.current_year'), 1, 1)) */
                    ->default(now()->toDateString()),
                Forms\Components\TextInput::make('object')
                    ->label("Objet d'honoraire"),
                Forms\Components\TextInput::make('montantHT')
                    ->label("Montant H.T")
                    ->live(onBlur: true)
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $newTva = $get('exonere_tva') ? 0 : ($get('montantHT') * config('taxes.tva'));

                            $newMontantTTC = $get('montantHT') + $newTva;
                            $newRs = $get('exonere_rs') ? 0 : ($newMontantTTC * config('taxes.rs'));
                            $newTf = $get('exonere_tf') ? 0 : config('taxes.tf');
                            $newNetapayer = $newMontantTTC - $newRs + $newTf;

                            $currentYear = date('Y');
                            /* $count = Honoraire::where('client_id', $state)->count(); */
                            $count = Honoraire::count();
                            $newNote = str_pad($count + 1, 4, '0', STR_PAD_LEFT) . $currentYear;
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

                Forms\Components\TextInput::make('montantTTC')
                    ->label("Montant T.T.C"),
                Forms\Components\TextInput::make('netapayer')
                    ->label("Net à Payer"),
                Forms\Components\TextInput::make('tva')
                    ->label("T.V.A"),

                Forms\Components\TextInput::make('rs')
                    ->label("R/S"),
                Forms\Components\TextInput::make('tf')
                    ->label("Timbre Fisacle"),
                Toggle::make('exonere_tva')
                    ->label('Exonération TVA')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $newTva = $state ? 0 : ($get('montantHT') * config('taxes.tva'));
                        $newMontantTTC = $get('montantHT') + $newTva;
                        $newRs = $get('exonere_rs') ? 0 : ($newMontantTTC * config('taxes.rs'));
                        $set('tva', $newTva);
                        $set('montantTTC', $newMontantTTC);
                        $set('rs', $newRs);
                        $set('netapayer', $newMontantTTC - $newRs + $get('tf'));
                    }),
                Toggle::make('exonere_rs')
                    ->label('Exonération RS')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $newRs = $state ? 0 : ($get('montantTTC') * config('taxes.rs'));
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
                Tables\Columns\TextColumn::make('note')
                    ->sortable()
                    ->label("Note d'honoraire")
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('object')
                    ->label("Objet d'honoraire"),
                Tables\Columns\TextColumn::make('client.mf')
                    ->label('Matricule Fiscale')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label("Date d'honoraire")
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('pdf')
                        ->label('PDF')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn(Honoraire $record) => route('pdf', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
