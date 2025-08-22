<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Section;
use App\Filament\Resources\ClientResource\Pages\ListClients;
use App\Filament\Resources\ClientResource\Pages\CreateClient;
use App\Filament\Resources\ClientResource\Pages\ViewClient;
use App\Filament\Resources\ClientResource\Pages\EditClient;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\ClientResource\RelationManagers\HonorairesRelationManager;
use App\Filament\Resources\ClientResource\RelationManagers\NoteDeDebitsRelationManager;
use App\Models\Client;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;


class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';

    protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): string
    {
        return __('Clients Area');
    }

    public static function getNavigationLabel(): string
    {
        return __('Clients');
    }

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



    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->label(__('Address'))
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->maxLength(15),
                TextInput::make('mf')
                    ->label(__('Tax ID'))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('address')
                    ->label(__('Address')),
                TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable(),
                TextColumn::make('mf')
                    ->label(__('Tax ID'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            HonorairesRelationManager::class,
            NoteDeDebitsRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'view' => ViewClient::route('/{record}'),
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return !auth()->user()?->is_demo;
    }

}
