<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteDeDebitResource\Pages;
use App\Filament\Resources\NoteDeDebitResource\RelationManagers;
use App\Models\NoteDeDebit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Forms\Components\Toggle;

class NoteDeDebitResource extends Resource
{
    protected static ?string $model = NoteDeDebit::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $navigationGroup = "Espace Client";

    protected static ?string $navigationLabel = 'Notes de Débit';

    public static function getEloquentQuery(): Builder
    {
        $fiscalYear = config('fiscal_year.current_year');
        return parent::getEloquentQuery()->whereYear('date', $fiscalYear);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('note')
                    ->label("Référence")
                    ->disabled(),
                Forms\Components\DatePicker::make('date') // Add date picker
                    ->label('Date d\'émission'),
                Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->disabledOn('edit')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $currentYear = date('Y');
                            $count = NoteDeDebit::count() + 1; // Start from count + 1 for the new note

                            // Generate a new unique note reference
                            do {
                                $newNote = str_pad($count, 4, '0', STR_PAD_LEFT) . $currentYear;
                                $count++; // Increment for the next check if it already exists
                            } while (NoteDeDebit::where('note', $newNote)->exists()); // Check for uniqueness

                            $set('note', $newNote);
                        }
                    }),
                Forms\Components\TextInput::make('amount')
                    ->label('Montant')
                    ->numeric()
                    ->required(),
                /* ->live(onBlur: true)
                    
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $newTva = $get('exonere_tva') ? 0 : ($get('montantHT') * config('taxes.tva'));
                            $newMontantTTC = $get('montantHT') + $newTva;
                            $newRs = $get('exonere_rs') ? 0 : ($newMontantTTC * config('taxes.rs'));
                            $newTf = $get('exonere_tf') ? 0 : config('taxes.tf');
                            $newNetapayer = $newMontantTTC - $newRs + $newTf;

                            $set('tva', $newTva);
                            $set('montantTTC', $newMontantTTC);
                            $set('rs', $newRs);
                            $set('tf', $newTf);
                            $set('netapayer', $newNetapayer);
                        }
                    }),*/
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->label('Description')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('note')
                    ->sortable()
                    ->label("Réference")
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        $paddedSearch = str_pad($search, 8, '0', STR_PAD_LEFT);
                        $query->where('note', 'like', "%{$paddedSearch}%");
                    }),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('TND'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date d\'émission')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('pdf')
                        ->label('PDF')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn(NoteDeDebit $record) => route('pdf', $record))
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informations du client')
                    ->schema([
                        TextEntry::make('client.name')
                            ->label('Nom du client')
                            ->weight('bold')
                            ->icon('heroicon-o-user-circle'),
                        TextEntry::make('client.mf')
                            ->label('Matricule Fiscale')
                            ->icon('heroicon-o-identification'),
                    ])
                    ->columns(2),
                Section::make('Informations supplémentaires')
                    ->schema([
                        TextEntry::make('note')
                            ->label("Référence")
                            ->formatStateUsing(fn(string $state): string => str_pad($state, 8, '0', STR_PAD_LEFT)),
                        TextEntry::make('description')
                            ->label("Description"),
                        TextEntry::make('amount')
                            ->label('Montant')
                            ->money('tnd'),
                        TextEntry::make('date')
                            ->label('Date de debit')
                            ->date(),

                    ])
                    ->columns(4),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNoteDeDebits::route('/'),
            'create' => Pages\CreateNoteDeDebit::route('/create'),
            'view' => Pages\ViewNoteDeDebit::route('/{record}'),
            'edit' => Pages\EditNoteDeDebit::route('/{record}/edit'),
        ];
    }
}
