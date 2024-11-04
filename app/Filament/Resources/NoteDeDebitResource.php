<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteDeDebitResource\Pages;
use App\Filament\Resources\NoteDeDebitResource\RelationManagers;
use App\Models\NoteDeDebit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NoteDeDebitResource extends Resource
{
    protected static ?string $model = NoteDeDebit::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $navigationGroup = "Espace Client";

    public static function getEloquentQuery(): Builder
    {
        $fiscalYear = config('fiscal_year.current_year');
        return parent::getEloquentQuery()->whereYear('date', $fiscalYear);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Montant')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label('Description'),
                Forms\Components\DatePicker::make('date') // Add date picker
                    ->label('Date d\'émission'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('TND'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date d\'émission')
                    ->date(),
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
            'index' => Pages\ListNoteDeDebits::route('/'),
            'create' => Pages\CreateNoteDeDebit::route('/create'),
            'edit' => Pages\EditNoteDeDebit::route('/{record}/edit'),
        ];
    }
}
