<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanySettingResource\Pages;
use App\Filament\Resources\CompanySettingResource\RelationManagers;
use App\Models\CompanySetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class CompanySettingResource extends Resource
{

    protected static ?string $model = CompanySetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slogan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('mf_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address_line1')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address_line2')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone1')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone2')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone3')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fax')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
            ]);
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
}