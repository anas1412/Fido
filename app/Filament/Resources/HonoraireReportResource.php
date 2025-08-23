<?php

namespace App\Filament\Resources;

use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\HonoraireReportResource\Pages\ListHonoraireReports;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document';

        protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): string
    {
        return __('Reports Generation');
    }

    public static function getNavigationLabel(): string
    {
        return __('Report of Honoraires');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Honoraires Reports');
    }

    public static function getModelLabel(): string
    {
        return __('Honoraire Report');
    }

    public static function getEloquentQuery(): Builder
    {
        $fiscalYear = config('fiscal_year.current_year');
        return parent::getEloquentQuery()->with('client')->whereYear('date', $fiscalYear);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('note')
                    ->label('Réf')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    }),
                TextColumn::make('date')
                    ->label("Date")
                    ->date(),
                TextColumn::make('client.name')
                    ->label('Client')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('client.mf')
                    ->label('M.F')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('montantHT')
                    ->label('Montant HT')
                    ->summarize(Sum::make()->label('')),
                TextColumn::make('tva')
                    ->label('TVA')
                    ->summarize(Sum::make()->label('')),
                TextColumn::make('rs')
                    ->label('R.S')
                    ->summarize(Sum::make()->label('')),
                TextColumn::make('montantTTC')
                    ->label('Montant TTC')
                    ->summarize(Sum::make()->label('')),
                TextColumn::make('tf')
                    ->label('Timbre')
                    ->summarize(Sum::make()->label('')),
                TextColumn::make('netapayer')
                    ->label('Net à payer')
                    ->summarize(Sum::make()->label('')),

            ])
            ->filters([
                /* SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->label('Nom du client'), */
                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Date de début'),
                        DatePicker::make('end_date')
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
            ->recordActions([])
            ->toolbarActions([]);
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
            'index' => ListHonoraireReports::route('/'),
            /* 'create' => Pages\CreateHonoraireReport::route('/create'),
            'edit' => Pages\EditHonoraireReport::route('/{record}/edit'), */
        ];
    }

    /* public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNotNull('rs')->where('rs', '>', 0);
    } */
}
