<?php

namespace App\Filament\Pages;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\CompanySetting;

class EditCompanySettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Modifier l\'entreprise';

    protected static ?string $navigationGroup = "Parametres";

    public static function getNavigationSort(): ?int
    {
        return 99; // A high number to ensure it appears at the bottom
    }

    protected static string $view = 'filament.pages.edit-company-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $companySetting = CompanySetting::firstOrCreate([]);

        if ($companySetting->wasRecentlyCreated || empty($companySetting->company_name)) {
            $companySetting->company_name = env('COMPANY_NAME', 'Your Company Name');
            $companySetting->slogan = env('COMPANY_SLOGAN', 'Your Company Slogan');
            $companySetting->mf_number = env('COMPANY_MF_NUMBER', 'MF123456789');
            $companySetting->location = env('COMPANY_LOCATION', 'Your City');
            $companySetting->address_line1 = env('COMPANY_ADDRESS_LINE1', '123 Main St');
            $companySetting->address_line2 = env('COMPANY_ADDRESS_LINE2', 'Suite 100');
            $companySetting->phone1 = env('COMPANY_PHONE1', '+1234567890');
            $companySetting->phone2 = env('COMPANY_PHONE2', '+0987654321');
            $companySetting->fax = env('COMPANY_FAX', '+1234567890');
            $companySetting->email = env('COMPANY_EMAIL', 'info@yourcompany.com');
            $companySetting->save();
        }

        $this->form->fill($companySetting->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                
                TextInput::make('fax')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            CompanySetting::firstOrCreate([])->update($data);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('There was an error saving your changes.')
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
