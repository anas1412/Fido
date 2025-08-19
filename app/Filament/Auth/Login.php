<?php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Config;

class Login extends BaseLogin
{
    protected function form(Form $form): Form
    {
        $form = parent::form($form);

        

        return $form;
    }
}
