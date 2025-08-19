<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = null;

    protected static ?string $navigationLabel = null;

    public static function getNavigationGroup(): string
    {
        return __('Admin Area');
    }

    public static function getNavigationLabel(): string
    {
        return __('Users Management');
    }



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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->minLength(3)
                    ->maxLength(30),
                Forms\Components\TextInput::make('password')
                    ->label(__('Password'))
                    ->required()
                    ->minLength(8)
                    ->maxLength(30)
                    ->revealable()
                    ->password(),
                Forms\Components\TextInput::make('email')
                    ->label(__('Email'))
                    ->required()
                    ->email()
                    ->maxLength(50),
                Forms\Components\Toggle::make('is_admin')
                    ->label(__('Admin'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_admin')
                    ->label(__('Admin'))
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->sortable()
                    ->datetime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
