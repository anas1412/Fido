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

class HonoraireResource extends Resource
{
    protected static ?string $model = Honoraire::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = "Clients Space";


    public static function form(Form $form): Form
    {

        $currentYear = date('Y');
        $count = Honoraire::count();
        $newNote = str_pad($count + 1, 4, '0', STR_PAD_LEFT) . '/' . $currentYear;

        return $form
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
                Forms\Components\TextInput::make('note')
                    ->label("Note d'honoraire")
                    ->disabled(),
                Forms\Components\TextInput::make('object')
                    ->label("Objet d'honoraire"),
                /* ->disabled(), */
                Forms\Components\TextInput::make('montantHT')
                    ->label("Montant H.T")
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        if ($state) {
                            $newTva = $get('montantHT') * config('taxes.tva');
                            $newMontantTTC = $get('montantHT') + $newTva;
                            $newRs = $newMontantTTC * config('taxes.rs');
                            $newTf = config('taxes.tf');
                            $newNetapayer = $newMontantTTC - $newRs + $newTf;

                            $set('tva', $newTva);
                            $set('montantTTC', $newMontantTTC);
                            $set('rs', $newRs);
                            $set('tf', $newTf);
                            $set('netapayer', $newNetapayer);
                        }
                    }),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de création')
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
                        /* ->action(function ($record) {
                            return response()->streamDownload(function () use ($record) {
                                echo Pdf::loadHtml(
                                    Blade::render('pdf', ['record' => $record])
                                )->stream();
                            }, $record->number . '.pdf');
                        }), */
                        ->url(fn (Honoraire $record) => route('pdf', $record))
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
                Infolists\Components\TextEntry::make('client.name'),
                Infolists\Components\TextEntry::make('note'),
                Infolists\Components\TextEntry::make('montantHT'),
                Infolists\Components\TextEntry::make('montantTTC'),
                Infolists\Components\TextEntry::make('tva'),
                Infolists\Components\TextEntry::make('rs'),
                Infolists\Components\TextEntry::make('tf'),
                Infolists\Components\TextEntry::make('netapayer')

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
            'index' => Pages\ListHonoraires::route('/'),
            'create' => Pages\CreateHonoraire::route('/create'),
            'view' => Pages\ViewHonoraire::route('/{record}'),
            'edit' => Pages\EditHonoraire::route('/{record}/edit'),
        ];
    }
}
