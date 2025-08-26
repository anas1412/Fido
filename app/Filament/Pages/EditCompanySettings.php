<?php

namespace App\Filament\Pages;

use Filament\Schemas\Schema;
use Exception;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\CompanySetting;

class EditCompanySettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $slug = 'edit-company';

    protected static ?string $navigationLabel = null;

    protected static string | \UnitEnum | null $navigationGroup = null;

    public function getTitle(): string
    {
        return __('Edit Company');
    }

    public static function getNavigationLabel(): string
    {
        return __('Edit Company');
    }

    public static function getNavigationGroup(): string
    {
        return __('Settings');
    }

    public static function getNavigationSort(): ?int
    {
        return 999;
    }

    protected string $view = 'filament.pages.edit-company-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $companySetting = CompanySetting::firstOrCreate(
            [],
            [
                'company_name' => env('COMPANY_NAME', 'Your Company Name'),
                'slogan' => env('COMPANY_SLOGAN', 'Your Company Slogan'),
                'mf_number' => env('COMPANY_MF_NUMBER', 'MF123456789'),
                'location' => env('COMPANY_LOCATION', 'Your City'),
                'address_line1' => env('COMPANY_ADDRESS_LINE1', '123 Main St'),
                'address_line2' => env('COMPANY_ADDRESS_LINE2', 'Suite 100'),
                'phone1' => env('COMPANY_PHONE1', '+1234567890'),
                'phone2' => env('COMPANY_PHONE2', '+0987654321'),
                'fax' => env('COMPANY_FAX', '+1234567890'),
                'email' => env('COMPANY_EMAIL', 'info@yourcompany.com'),
            ]
        );

        if ($companySetting->wasRecentlyCreated || empty($companySetting->company_name)) {
            
        }

        $this->form->fill($companySetting->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->label(__('Company Name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('slogan')
                    ->label(__('Slogan'))
                    ->maxLength(255),
                TextInput::make('mf_number')
                    ->label(__('Tax ID Number'))
                    ->maxLength(255),
                TextInput::make('location')
                    ->label(__('Location'))
                    ->maxLength(255),
                TextInput::make('address_line1')
                    ->label(__('Address Line 1'))
                    ->maxLength(255),
                TextInput::make('address_line2')
                    ->label(__('Address Line 2'))
                    ->maxLength(255),
                TextInput::make('phone1')
                    ->label(__('Phone 1'))
                    ->tel()
                    ->maxLength(255),
                TextInput::make('phone2')
                    ->label(__('Phone 2'))
                    ->tel()
                    ->maxLength(255),
                
                TextInput::make('fax')
                    ->label(__('Fax'))
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->maxLength(255),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save Changes'))
                ->submit('save')
                ->disabled(auth()->user()->is_demo),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            CompanySetting::firstOrCreate([])->update($data);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('Error'))
                ->body(__('There was an error saving your changes.'))
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title(__('Saved successfully'))
            ->success()
            ->send();
    }
}