<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Models\NoteDeDebit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NoteDeDebitsRelationManager extends RelationManager
{
    protected static string $relationship = 'noteDeDebits';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('note')
                    ->label("Référence")
                    ->required()
                    ->disabled(),
                Forms\Components\DatePicker::make('date') // Add date picker
                    ->label('Date d\'émission'),
                Forms\Components\TextInput::make('amount')
                    ->label('Montant')
                    ->numeric()
                    ->reactive()
                    ->required()
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
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->label('Description'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->whereYear('date', config('fiscal_year.current_year')))
            ->recordTitleAttribute('note')
            ->columns([
                Tables\Columns\TextColumn::make('note')
                    ->sortable()
                    ->label("Référence")
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label("Description"),
                Tables\Columns\TextColumn::make('date')
                    ->label("Date de debit")
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
                        ->url(fn(NoteDeDebit $record) => route('pdf.note-de-debit', ['noteDeDebit' => $record->id])),
                        
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
