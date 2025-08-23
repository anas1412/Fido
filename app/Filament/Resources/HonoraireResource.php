<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use App\Models\TaxSetting;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use App\Filament\Resources\HonoraireResource\Pages\ListHonoraires;
use App\Filament\Resources\HonoraireResource\Pages\CreateHonoraire;
use App\Filament\Resources\HonoraireResource\Pages\ViewHonoraire;
use App\Filament\Resources\HonoraireResource\Pages\EditHonoraire;
use Filament\Forms;
use Filament\Tables;
use App\Models\Honoraire;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\taxes;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\HonoraireResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\HonoraireResource\RelationManagers;
use Filament\Infolists;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;

class HonoraireResource extends Resource
{
    protected static ?string $model = Honoraire::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): string
    {
        return __('Clients Area');
    }

    public static function getNavigationLabel(): string
    {
        return __('Honoraires');
    }

    public static function getModelLabel(): string
    {
        return __('Honoraire');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Honoraires');
    }


    /* public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
 */


    public static function form(Schema $schema): Schema
    {
        $currentYear = date('Y');
        $count = Honoraire::count();
        $newNote = str_pad($count + 1, 4, '0', STR_PAD_LEFT) . '/' . $currentYear;

        return $schema
            ->components([
                Wizard::make([
                    Step::make(__('Client Information'))
                        ->schema([
                            Select::make('client_id')
                                ->label(__('Client'))
                                ->relationship('client', 'name')
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->disabledOn('edit')
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label(__('Name'))
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('address')
                                        ->label(__('Address'))
                                        ->maxLength(255),
                                    TextInput::make('phone')
                                        ->label(__('Phone'))
                                        ->maxLength(15),
                                    TextInput::make('mf')
                                        ->label(__('Tax ID'))
                                        ->maxLength(255),
                                ])
                                ->editOptionForm([
                                    TextInput::make('name')
                                        ->label(__('Name'))
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('address')
                                        ->label(__('Address'))
                                        ->maxLength(255),
                                    TextInput::make('phone')
                                        ->label(__('Phone'))
                                        ->maxLength(15),
                                    TextInput::make('mf')
                                        ->label(__('Tax ID'))
                                        ->maxLength(255),
                                ])
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        $currentYear = date('Y');
                                        $count = Honoraire::count() + 1; // Start from count + 1 for the new note

                                        // Generate a new unique note reference
                                        do {
                                            $newNote = str_pad($count, 4, '0', STR_PAD_LEFT) . $currentYear;
                                            $count++; // Increment for the next check if it already exists
                                        } while (Honoraire::where('note', $newNote)->exists()); // Check for uniqueness




                                        $newObject = "Assistance comptable de l'année $currentYear";

                                        $set('note', $newNote);
                                        $set('object', $newObject);
                                    }
                                }),
                            TextInput::make('montantHT')
                                ->label(__('Amount HT'))
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

                                        $set('tva', $newTva);
                                        $set('montantTTC', $newMontantTTC);
                                        $set('rs', $newRs);
                                        $set('tf', $newTf);
                                        $set('netapayer', $newNetapayer);
                                    }
                                }),
                            Toggle::make('exonere_tf')
                                ->label(__('TF Exemption'))
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    $taxSettings = TaxSetting::first();
                                $newTf = $state ? 0 : $taxSettings->tf;
                                    $set('tf', $newTf);
                                    $set('netapayer', $get('montantTTC') - $get('rs') + $newTf);
                                }),
                            Toggle::make('exonere_rs')
                                ->label(__('RS Exemption'))
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    $taxSettings = TaxSetting::first();
                                $newRs = $state ? 0 : ($get('montantTTC') * $taxSettings->rs);
                                    $set('rs', $newRs);
                                    $set('netapayer', $get('montantTTC') - $newRs + $get('tf'));
                                }),
                            Toggle::make('exonere_tva')
                                ->label(__('TVA Exemption'))
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
                        ]),
                    Step::make(__('Fee Information'))
                        ->schema([
                            TextInput::make('note')
                                ->label(__('Fee Note'))
                                ->disabled(),
                            TextInput::make('object')
                                ->label(__('Fee Object')),
                            DatePicker::make('date')
                                ->label(__('Fee Date'))
                                ->default(now()->toDateString()),
                        ]),
                    Step::make(__('Other Information'))
                        ->schema([
                            TextInput::make('tva')
                                ->label(__('T.V.A')),
                            TextInput::make('montantTTC')
                                ->label(__('Amount TTC')),
                            TextInput::make('rs')
                                ->label(__('R/S')),
                            TextInput::make('tf')
                                ->label(__('Fiscal Stamp')),
                            TextInput::make('netapayer')
                                ->label(__('Net to Pay')),
                        ]),
                ])->columnSpanFull(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('note')
                    ->sortable()
                    ->label("Note d'honoraire")
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        // Pad the search input with zeros
                        $paddedSearch = str_pad($search, 8, '0', STR_PAD_LEFT);
                        $query->where('note', 'like', "%{$paddedSearch}%");
                    }),
                TextColumn::make('object')
                    ->label("Objet d'honoraire"),
                TextColumn::make('client.name')
                    ->label('Nom de client')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('client.mf')
                    ->label('Matricule Fiscale')
                    ->searchable(),
                TextColumn::make('date')
                    ->label("Date d'honoraire")
                    ->date()
                    ->sortable(),
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
                        TextEntry::make('object')
                            ->label("Objet d'honoraire"),
                        TextEntry::make('note')
                            ->label("Note d'honoraire")
                            ->formatStateUsing(fn(string $state): string => str_pad($state, 8, '0', STR_PAD_LEFT)),
                        TextEntry::make('date')
                            ->label('Date de honoraire')
                            ->date(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Informations sur exonération')
                    ->schema([
                        TextEntry::make('exonere_tf')
                            ->label('Exonération TF')
                            ->formatStateUsing(fn(bool $state): string => $state ? 'Oui' : 'Non')
                            ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
                        TextEntry::make('exonere_rs')
                            ->label('Exonération RS')
                            ->formatStateUsing(fn(bool $state): string => $state ? 'Oui' : 'Non')
                            ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
                        TextEntry::make('exonere_tva')
                            ->label('Exonération TVA')
                            ->formatStateUsing(fn(bool $state): string => $state ? 'Oui' : 'Non')
                            ->color(fn(bool $state): string => $state ? 'success' : 'danger'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Section::make('Détails financiers')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('montantHT')
                                    ->label('Montant H.T')
                                    ->money('tnd'),
                                TextEntry::make('tva')
                                    ->label('T.V.A')
                                    ->money('tnd'),
                                TextEntry::make('montantTTC')
                                    ->label('Montant T.T.C')
                                    ->money('tnd')
                                    ->color('success')
                                    ->weight('bold'),
                                TextEntry::make('rs')
                                    ->label('R/S')
                                    ->money('tnd'),
                                TextEntry::make('tf')
                                    ->label('Timbre Fiscal')
                                    ->money('tnd'),
                                TextEntry::make('netapayer')
                                    ->label('Net à Payer')
                                    ->money('tnd')
                                    ->color('success')
                                    ->weight('bold'),
                            ]),
                    ])->columnSpanFull(),
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
            'index' => ListHonoraires::route('/'),
            'create' => CreateHonoraire::route('/create'),
            'view' => ViewHonoraire::route('/{record}'),
            'edit' => EditHonoraire::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return !auth()->user()?->is_demo;
    }

}
