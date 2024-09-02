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
use Illuminate\Support\Facades\Blade;

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
                    ->default(now()->toDateString()),

                Forms\Components\TextInput::make('montantHT')
                    ->label("Montant H.T")
                    ->live(onBlur: true)
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $newTva = $get('montantHT') * config('taxes.tva');
                            $newMontantTTC = $get('montantHT') + $newTva;
                            $newRs = $newMontantTTC * config('taxes.rs');
                            $newTf = config('taxes.tf');
                            $newNetapayer = $newMontantTTC - $newRs + $newTf;

                            $currentYear = date('Y');
                            $count = Honoraire::where('client_id', $state)->count();
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
                Forms\Components\TextInput::make('object')
                    ->label("Objet d'honoraire"),
                Forms\Components\TextInput::make('tva')
                    ->label("T.V.A"),
                Forms\Components\TextInput::make('montantTTC')
                    ->label("Montant T.T.C"),
                Forms\Components\TextInput::make('rs')
                    ->label("R/S"),
                Forms\Components\TextInput::make('tf')
                    ->label("Timbre Fisacle"),
                Forms\Components\TextInput::make('netapayer')
                    ->label("Net à Payer"),
            ]);
    }



    public function table(Table $table): Table
    {
        return $table
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
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Nom de client')
                    ->sortable()
                    ->searchable(),
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