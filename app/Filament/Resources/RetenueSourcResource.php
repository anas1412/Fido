<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RetenueSourcResource\Pages;
use App\Models\Honoraire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;

class RetenueSourcResource extends Resource
{
    protected static ?string $model = Honoraire::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = "Rapports";

    protected static ?string $navigationLabel = 'Retenue à la source';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /* Tables\Columns\TextColumn::make('client.name')
                    ->label('Nom du client')
                    ->sortable()
                    ->searchable(), */
                Tables\Columns\TextColumn::make('note')
                    ->label('Note')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label("Date d'honoraire")
                    ->date(),
                Tables\Columns\TextColumn::make('montantTTC')
                    ->label('Montant TTC')
                    ->summarize(Sum::make()->label('')->money('TND'))
                    ->money('tnd'),
                Tables\Columns\TextColumn::make('rs')
                    ->label('Retenue à la source')
                    ->summarize(Sum::make()->label('')->money('TND'))
                    ->money('tnd'),
            ])
            ->defaultGroup(
                Group::make('client.name')
                    ->collapsible(),
            )
            ->filters([
                SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->label('Nom du client'),
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
            ], layout: FiltersLayout::AboveContent)->filtersFormColumns(2)
            /* ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Choisir un client et les dates')
            ) */
            ->actions([
                /* Tables\Actions\ViewAction::make(), */])
            ->bulkActions([
                // You can add bulk actions if needed
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
            'index' => Pages\ListRetenueSourc::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNotNull('rs')->where('rs', '>', 0);
    }
}
