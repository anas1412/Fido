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
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Nom du client')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label("Date d'honoraire")
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('montantTTC')
                    ->label('Montant TTC')
                    ->money('tnd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('rs')
                    ->label('Retenue à la source')
                    ->money('tnd')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->label('Client'),
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
            ])
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
