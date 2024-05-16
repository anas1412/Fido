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

class HonoraireResource extends Resource
{
    protected static ?string $model = Honoraire::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = "Clients Space";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
