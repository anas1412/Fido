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

    protected static ?string $navigationLabel = null;
    protected static ?string $slug = 'modify-fiscal-year';
    protected static ?string $title = null;
    protected static string $view = 'filament.pages.modify-fiscal-year';
    protected static ?string $navigationGroup = null;

    public static function getNavigationLabel(): string
    {
        return __('Edit Fiscal Year');
    }

    public function getTitle(): string
    {
        return __('Edit Fiscal Year');
    }

    public static function getNavigationGroup(): string
    {
        return __('Settings');
    }

    public static function getNavigationSort(): ?int
    {
        return 1001;
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
                    ->label(__('Fiscal Year'))
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
                ->label(__('Save'))
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
                ->title(__('Error'))
                ->body(__('There was an error saving your changes.'))
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title(__('Fiscal Year updated successfully!'))
            ->success()
            ->send();
    }
}