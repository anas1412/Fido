<?php

namespace App\Filament\Pages;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\FiscalYearSetting;

class ModifyFiscalYear extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Modifier Année Fiscale';
    protected static ?string $slug = 'modify-fiscal-year';
    protected static ?string $title = 'Modifier l\'Année Fiscale';
    protected static string $view = 'filament.pages.modify-fiscal-year';
    protected static ?string $navigationGroup = "Parametres";

    public static function getNavigationSort(): ?int
    {
        return 101; // Just after EditTaxes
    }

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }

    public ?array $data = [];
    public int $year = 0;

    public function mount(): void
    {
        $fiscalYearSetting = FiscalYearSetting::firstOrCreate(
            [],
            ['year' => date('Y')]
        );
        $this->form->fill($fiscalYearSetting->attributesToArray());
        $this->year = $this->data['year'] ?? 0;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('year')
                    ->label('Année Fiscale')
                    ->numeric()
                    ->required()
                    ->minValue(1900)
                    ->maxValue(2100),
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
            $fiscalYearSetting = FiscalYearSetting::firstOrNew([]);
            $fiscalYearSetting->fill($data);
            $fiscalYearSetting->save();
            $this->year = $data['year'] ?? 0;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('There was an error saving your changes.')
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title('Année Fiscale mise à jour avec succès!')
            ->success()
            ->send();
    }
}