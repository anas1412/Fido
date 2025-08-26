<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use App\Models\TaxSetting;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Section;
use App\Filament\Resources\NoteDeDebitResource\Pages\ListNoteDeDebits;
use App\Filament\Resources\NoteDeDebitResource\Pages\CreateNoteDeDebit;
use App\Filament\Resources\NoteDeDebitResource\Pages\ViewNoteDeDebit;
use App\Filament\Resources\NoteDeDebitResource\Pages\EditNoteDeDebit;
use App\Filament\Resources\NoteDeDebitResource\Pages;
use App\Filament\Resources\NoteDeDebitResource\RelationManagers;
use App\Models\NoteDeDebit;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

class NoteDeDebitResource extends Resource
{
    protected static ?string $model = NoteDeDebit::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document';

    protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): string
    {
        return __('Clients Area');
    }

    public static function getNavigationLabel(): string
    {
        return __('Note de Débit');
    }

    public static function getModelLabel(): string
    {
        return __('Note de Débit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Notes de Débit');
    }

    protected static ?int $navigationSort = 30;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('note')
                    ->label(__('Reference'))
                    ->disabled(),
                DatePicker::make('date') // Add date picker
                    ->label(__('Issue Date')),
                Select::make('client_id')
                    ->label(__('Client'))
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
                TextInput::make('amount')
                    ->label(__('Amount'))
                    ->numeric()
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $taxSettings = TaxSetting::first();
                            $newTva = $get('exonere_tva') ? 0 : ($get('montantHT') * $taxSettings->tva);
                            $newMontantTTC = $get('montantHT') + $newTva;
                            $newRs = $get('exonere_rs') ? 0 : ($newMontantTTC * $taxSettings->rs);
                            $newTf = $get('exonere_tf') ? 0 : $taxSettings->tf;
                            $newNetapayer = $newMontantTTC - $newRs + $newTf;

                            $set('tva', $newTva);
                            $set('montantTTC', $newMontantTTC);
                            $set('rs', $newRs);
                            $set('tf', $newTf);
                            $set('netapayer', $newNetapayer);
                        }
                    }),
                TextInput::make('description')
                    ->required()
                    ->label(__('Description'))->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('note')
                    ->sortable()
                    ->label(__('Reference'))
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        $paddedSearch = str_pad($search, 8, '0', STR_PAD_LEFT);
                        $query->where('note', 'like', "%{$paddedSearch}%");
                    }),
                TextColumn::make('client.name')
                    ->label(__('Client')),
                TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money('TND'),
                TextColumn::make('date')
                    ->label(__('Issue Date'))
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('pdf')
                        ->label('PDF')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(fn(NoteDeDebit $record) => route('pdf.note-de-debit', ['noteDeDebit' => $record->id])),

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

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->columns(2)
                    ->columnSpanFull(),
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
                    ->columns(4)
                    ->columnSpanFull(),
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
            'index' => ListNoteDeDebits::route('/'),
            'create' => CreateNoteDeDebit::route('/create'),
            'view' => ViewNoteDeDebit::route('/{record}'),
            'edit' => EditNoteDeDebit::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return !auth()->user()?->is_demo;
    }

}
