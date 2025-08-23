<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\CompanySettingResource\Pages;
use App\Filament\Resources\CompanySettingResource\RelationManagers;
use App\Models\CompanySetting;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class CompanySettingResource extends Resource
{

    protected static ?string $model = CompanySetting::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slogan')
                    ->maxLength(255),
                TextInput::make('mf_number')
                    ->maxLength(255),
                TextInput::make('location')
                    ->maxLength(255),
                TextInput::make('address_line1')
                    ->maxLength(255),
                TextInput::make('address_line2')
                    ->maxLength(255),
                TextInput::make('phone1')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('phone2')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('phone3')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('fax')
                    ->maxLength(255),
                TextInput::make('email')
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