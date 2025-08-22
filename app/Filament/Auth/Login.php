<?php

namespace App\Filament\Auth;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Config;

class Login extends \Filament\Auth\Pages\Login
{
    protected function form(Schema $schema): Schema
    {
        $schema = parent::form($schema);

        

        return $schema;
    }
}
