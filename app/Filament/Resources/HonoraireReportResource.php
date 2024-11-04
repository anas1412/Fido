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
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HonoraireReportResource extends Resource
{
    protected static ?string $model = Honoraire::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationGroup = "Rapports";

    protected static ?string $navigationLabel = 'Rapports des honoraires';

    public static function getEloquentQuery(): Builder
    {
        $fiscalYear = config('fiscal_year.current_year');
        return parent::getEloquentQuery()->whereYear('date', $fiscalYear);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('note')
                    ->label('Réf')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    }),
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
                /* SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->label('Nom du client'), */
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Date de début'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Date de fin'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'] && $data['end_date'],
                                fn(Builder $query): Builder => $query->whereBetween('date', [$data['start_date'], $data['end_date']]),
                            );
                    }),
            ], layout: FiltersLayout::AboveContent)->filtersFormColumns(1)
            /* ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Choisir un client et les dates')
            ) */
            ->actions([])
            ->bulkActions([]);
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
