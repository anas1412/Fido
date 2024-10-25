<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HonoraireReportResource\Pages;
use App\Filament\Resources\HonoraireReportResource\RelationManagers;
use App\Models\Honoraire;
use App\Models\HonoraireReport;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HonoraireReportResource extends Resource
{
    protected static ?string $model = Honoraire::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = "Rapports";

    protected static ?string $navigationLabel = 'Rapports des honoraires';


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('note')
                    ->label('Réf')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label("Date")
                    ->date(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('client.mf')
                    ->label('M.F')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('montantHT')
                    ->label('Montant HT')
                    ->summarize(Sum::make()->label('')),
                Tables\Columns\TextColumn::make('tva')
                    ->label('TVA')
                    ->summarize(Sum::make()->label('')),
                Tables\Columns\TextColumn::make('rs')
                    ->label('R.S')
                    ->summarize(Sum::make()->label('')),
                Tables\Columns\TextColumn::make('montantTTC')
                    ->label('Montant TTC')
                    ->summarize(Sum::make()->label('')),
                Tables\Columns\TextColumn::make('tf')
                    ->label('Timbre')
                    ->summarize(Sum::make()->label('')),
                Tables\Columns\TextColumn::make('netapayer')
                    ->label('Net à payer')
                    ->summarize(Sum::make()->label('')),

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
            'index' => Pages\ListHonoraireReport::route('/'),
            /* 'index' => Pages\ListHonoraireReports::route('/'), */
            /* 'create' => Pages\CreateHonoraireReport::route('/create'),
            'edit' => Pages\EditHonoraireReport::route('/{record}/edit'), */
        ];
    }

    /* public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNotNull('rs')->where('rs', '>', 0);
    } */
}
