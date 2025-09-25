<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Models\NoteDeDebit;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class NoteDeDebitsRelationManager extends RelationManager
{
    protected static string $relationship = 'noteDeDebits';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Notes de débit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Notes de Débit');
    }   

    public static function getModelLabel(): string
    {
        return __('Note de Débit');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('note')
                    ->label("Référence")
                    ->required()
                    ->disabled(),
                DatePicker::make('date') // Add date picker
                    ->label('Date d\'émission'),
                TextInput::make('amount')
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
                TextInput::make('description')
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
                TextColumn::make('note')
                    ->sortable()
                    ->label("Référence")
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    })
                    ->searchable(),
                TextColumn::make('date')
                    ->label("Date de debit")
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->visible(!auth()->user()?->is_demo),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('print')
                    ->label(__('Print'))
                    ->color('info')
                    ->icon('heroicon-o-printer')
                    ->url(fn(NoteDeDebit $record) => route('pdf.note-de-debit', ['noteDeDebit' => $record->id]))
                    ->openUrlInNewTab(),

                EditAction::make()->visible(!auth()->user()?->is_demo),
                DeleteAction::make()->visible(!auth()->user()?->is_demo),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(!auth()->user()?->is_demo),
                ]),
            ]);
    }
}
