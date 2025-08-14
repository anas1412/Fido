<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteDeDebitReportResource\Pages;
use App\Filament\Resources\NoteDeDebitReportResource\RelationManagers;
use App\Models\NoteDeDebit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Grouping\Group;

class NoteDeDebitReportResource extends Resource
{
    protected static ?string $model = NoteDeDebit::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): string
    {
        return __('Reports Generation');
    }

    public static function getNavigationLabel(): string
    {
        return __('Report of Debit Notes');
    }

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        $fiscalYear = config('fiscal_year.current_year');
        return parent::getEloquentQuery()->with('client')->whereYear('date', $fiscalYear);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('note')
                    ->label('Référence')
                    ->getStateUsing(function ($record) {
                        return str_pad($record->note, 8, '0', STR_PAD_LEFT);
                    }),
                Tables\Columns\TextColumn::make('date')
                    ->label("Date de debit")
                    ->date(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant de debit')
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
            'index' => Pages\ListNoteDeDebitReports::route('/'),
        ];
    }
}
