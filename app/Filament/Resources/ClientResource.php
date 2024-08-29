<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\ClientResource\RelationManagers\HonorairesRelationManager;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;


class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = "Espace Client";

    protected static ?string $recordTitleAttribute = "name";

    protected static int $globalSearchResultsLimit = 20;

    /* public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    } */

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        return ClientResource::getUrl('view', ['record' => $record]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'mf',
        ];
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label("Nom de client")
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label("Adresse")
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label("Numéro de Téléphone")
                    ->maxLength(15),
                Forms\Components\TextInput::make('mf')
                    ->label("Matricule Fiscale")
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Adresse'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Numéro de téléphone'),
                Tables\Columns\TextColumn::make('mf')
                    ->label('Matricule Fiscale')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
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
                Section::make('Informations du client')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nom du client')
                            ->weight('bold')
                            ->icon('heroicon-o-user-circle')
                            ->columnSpan(2),
                        TextEntry::make('mf')
                            ->label('Matricule Fiscale')
                            ->icon('heroicon-o-identification')
                            ->columnSpan(2),
                        TextEntry::make('address')
                            ->label('Adresse')
                            ->icon('heroicon-o-map-pin')
                            ->columnSpan(2),
                        TextEntry::make('phone')
                            ->label('Numéro de téléphone')
                            ->icon('heroicon-o-phone')
                            ->columnSpan(2),
                    ])
                    ->columns(4),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\HonorairesRelationManager::class
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'view' => Pages\ViewClient::route('/{record}'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
