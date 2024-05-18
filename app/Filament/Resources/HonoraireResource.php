<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HonoraireResource\Pages;
use App\Filament\Resources\HonoraireResource\RelationManagers;
use App\Models\Honoraire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\taxes;

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
                /* Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->required(), */
                /*                 Forms\Components\TextInput::make('note')
                    ->label("Note d'honoraire")
                    ->default("5")
                    ->disabled(), */
                Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->required()
                    ->reactive()
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
                    ->label("Objet d'honoraire")
                    ->disabled(),
                Forms\Components\TextInput::make('montantHT')
                    ->label("Montant H.T")
                    ->default(12000.00)
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('tva')
                    ->label("T.V.A")
                    ->default(config('taxes.tva'))
                    ->disabled(),
                Forms\Components\TextInput::make('montantTTC')
                    ->label("Montant T.T.C")
                    ->default(1356.000)
                    ->disabled(),
                Forms\Components\TextInput::make('rs')
                    ->label("R/S")
                    ->default(config('taxes.rs'))
                    ->disabled(),
                Forms\Components\TextInput::make('tf')
                    ->label("Timbre Fisacle")
                    ->default(config('taxes.tf'))
                    ->disabled(),
                Forms\Components\TextInput::make('netapayer')
                    ->label("Net à Payer")
                    ->default(1288.800)
                    ->disabled(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('note')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('object'),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Nom de client')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.mf')
                    ->label('M.F.')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'edit' => Pages\EditHonoraire::route('/{record}/edit'),
        ];
    }
}
