<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static string | \UnitEnum | null $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): string
    {
        return __('Admin Area');
    }

    public static function getNavigationLabel(): string
    {
        return __('Users Management');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }

    public static function getModelLabel(): string
    {
        return __('User');
    }

    protected static ?int $navigationSort = 100;

    /* public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    } */


    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function canCreate(): bool
    {
        return !auth()->user()?->is_demo;
    }

    public static function canEdit(Model $record): bool
    {
        return !auth()->user()?->is_demo;
    }

    public static function canDelete(Model $record): bool
    {
        return !auth()->user()?->is_demo;
    }

    public static function canDeleteAny(): bool
    {
        return !auth()->user()?->is_demo;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->minLength(3)
                    ->maxLength(30),
                TextInput::make('password')
                    ->label(__('Password'))
                    ->required()
                    ->minLength(8)
                    ->maxLength(30)
                    ->revealable()
                    ->password(),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->required()
                    ->email()
                    ->maxLength(50),
                Toggle::make('is_admin')
                    ->label(__('Admin'))
                    ->required(),
                Toggle::make('is_demo')
                    ->label(__('Demo User'))
                    ->disabled(fn (?Model $record) => $record !== null),
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
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->toggleable()
                    ->searchable(),
                ToggleColumn::make('is_admin')
                    ->label(__('Admin'))
                    ->toggleable()
                    ->sortable(),
                ToggleColumn::make('is_demo')
                    ->label(__('Demo User'))
                    ->toggleable()
                    ->sortable()
                    ->disabled(),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->sortable()
                    ->datetime(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->recordUrl(
                #This makes the rows unclickable
                fn(User $record) => null,
            );
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
