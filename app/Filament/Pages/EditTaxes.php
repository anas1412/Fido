<?php

namespace App\Filament\Pages;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\TaxSetting;

class EditTaxes extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Modifier les Taxes';
    protected static ?string $slug = 'edit-taxes';
    protected static ?string $title = 'Modifier les Paramètres de Taxes';
    protected static string $view = 'filament.pages.edit-taxes';
    protected static ?string $navigationGroup = "Parametres";

    public static function getNavigationSort(): ?int
    {
        return 100; // A high number to ensure it appears at the bottom
    }

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }

    public ?array $data = [];

    public function mount(): void
    {
        $taxSetting = TaxSetting::firstOrCreate([]);
        $this->form->fill($taxSetting->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('tva')
                    ->label('Valeur de TVA en virgule')
                    ->numeric()
                    ->required(),
                TextInput::make('rs')
                    ->label('Valeur de RS en virgule')
                    ->numeric()
                    ->required(),
                TextInput::make('tf')
                    ->label('Valeur de Timbre Fiscale en dinars')
                    ->numeric()
                    ->required(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Sauvegarder')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            TaxSetting::firstOrCreate([])->update($data);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('There was an error saving your changes.')
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title('Taxes mises à jour avec succès!')
            ->success()
            ->send();
    }
}
