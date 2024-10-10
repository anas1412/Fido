<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
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
use Filament\Infolists\Infolist;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Forms\Components\Toggle;

class HonoraireResource extends Resource
{
    protected static ?string $model = Honoraire::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = "Espace Client";

    public static function getEloquentQuery(): Builder
    {
        $fiscalYear = config('fiscal_year.current_year');
        return parent::getEloquentQuery()->whereYear('date', $fiscalYear);
    }


    /* public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
 */


    public static function form(Form $form): Form
    {
        $currentYear = date('Y');
        $count = Honoraire::count();
        $newNote = str_pad($count + 1, 4, '0', STR_PAD_LEFT) . '/' . $currentYear;

        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Information sur le client')
                        ->schema([
                            Forms\Components\Select::make('client_id')
                                ->label('Client')
                                ->relationship('client', 'name')
                                ->searchable()
                                ->required()
                                ->reactive()
                                ->disabledOn('edit')
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->label("Nom de client")
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('address')
                                        ->label("Adresse")
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('phone')
                                        ->label("Numéro de téléphone")
                                        ->maxLength(15),
                                    Forms\Components\TextInput::make('mf')
                                        ->label("Matricule Fiscale")
                                        ->maxLength(255),
                                ])
                                ->editOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->label("Nom de client")
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('address')
                                        ->label("Adresse")
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('phone')
                                        ->label("Numéro de téléphone")
                                        ->maxLength(15),
                                    Forms\Components\TextInput::make('mf')
                                        ->label("Matricule Fiscale")
                                        ->maxLength(255),
                                ])
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        $currentYear = date('Y');
                                        $count = Honoraire::where('client_id', $state)->count();
                                        $newNote = str_pad($count + 1, 4, '0', STR_PAD_LEFT) . $currentYear;
                                        $newObject = "Assistance comptable de l'année $currentYear";

                                        $set('note', $newNote);
                                        $set('object', $newObject);
                                    }
                                }),
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

                                        $set('tva', $newTva);
                                        $set('montantTTC', $newMontantTTC);
                                        $set('rs', $newRs);
                                        $set('tf', $newTf);
                                        $set('netapayer', $newNetapayer);
                                    }
                                }),
                            Toggle::make('exonere_tf')
                                ->label('Exonération TF')
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    $newTf = $state ? 0 : config('taxes.tf');
                                    $set('tf', $newTf);
                                    $set('netapayer', $get('montantTTC') - $get('rs') + $newTf);
                                }),
                            Toggle::make('exonere_rs')
                                ->label('Exonération RS')
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set, $get) {
                                    $newRs = $state ? 0 : ($get('montantTTC') * config('taxes.rs'));
                                    $set('rs', $newRs);
                                    $set('netapayer', $get('montantTTC') - $newRs + $get('tf'));
                                }),
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
                        ]),
                    Forms\Components\Wizard\Step::make("Information de l'honoraire")
                        ->schema([
                            Forms\Components\TextInput::make('note')
                                ->label("Note d'honoraire")
                                ->disabled(),
                            Forms\Components\TextInput::make('object')
                                ->label("Objet d'honoraire"),
                            Forms\Components\DatePicker::make('date')
                                ->label("Date d'honoraire")
                                ->default(now()->toDateString()),
                        ]),
                    Forms\Components\Wizard\Step::make('Autre Information')
                        ->schema([
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
                        ]),
                ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->datetime()
                    ->sortable(),
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
                        TextEntry::make('object')
                            ->label("Objet d'honoraire"),
                        TextEntry::make('note')
                            ->label("Note d'honoraire")
                            ->formatStateUsing(fn(string $state): string => str_pad($state, 8, '0', STR_PAD_LEFT)),
                        TextEntry::make('date')
                            ->label('Date de honoraire')
                            ->date(),
                    ])
                    ->columns(3),
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
                    ->columns(3),
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
                    ]),
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
            'index' => Pages\ListHonoraires::route('/'),
            'create' => Pages\CreateHonoraire::route('/create'),
            'view' => Pages\ViewHonoraire::route('/{record}'),
            'edit' => Pages\EditHonoraire::route('/{record}/edit'),
        ];
    }
}