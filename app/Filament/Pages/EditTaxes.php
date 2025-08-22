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
use App\Models\TaxSetting;

class EditTaxes extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = null;
    protected static ?string $slug = 'edit-taxes';
    protected static ?string $title = null;
    protected string $view = 'filament.pages.edit-taxes';
    protected static string | \UnitEnum | null $navigationGroup = null;

    public static function getNavigationLabel(): string
    {
        return __('Edit Tax');
    }

    public function getTitle(): string
    {
        return __('Edit Tax');
    }

    public static function getNavigationGroup(): string
    {
        return __('Settings');
    }

    public static function getNavigationSort(): ?int
    {
        return 1000;
    }

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }

    public ?array $data = [];
    public float $tva = 0;
    public float $rs = 0;
    public float $tf = 0;

    public function mount(): void
    {
        $taxSetting = TaxSetting::firstOrCreate(
            [],
            [
                'tva' => 0.19,
                'rs' => 0.03,
                'tf' => 1,
            ]
        );
        $this->form->fill($taxSetting->attributesToArray());
        $this->tva = $this->data['tva'] ?? 0;
        $this->rs = $this->data['rs'] ?? 0;
        $this->tf = $this->data['tf'] ?? 0;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tva')
                    ->label(__('TVA Value (decimal)'))
                    ->numeric()
                    ->required(),
                TextInput::make('rs')
                    ->label(__('RS Value (decimal)'))
                    ->numeric()
                    ->required(),
                TextInput::make('tf')
                    ->label(__('Fiscal Stamp Value (dinars)'))
                    ->numeric()
                    ->required(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save'))
                ->submit('save')
                ->disabled(auth()->user()->is_demo),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            TaxSetting::firstOrCreate([])->update($data);
            $this->tva = $data['tva'] ?? 0;
            $this->rs = $data['rs'] ?? 0;
            $this->tf = $data['tf'] ?? 0;
        } catch (Exception $e) {
            Notification::make()
                ->title(__('Error'))
                ->body(__('There was an error saving your changes.'))
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title(__('Taxes updated successfully!'))
            ->success()
            ->send();
    }
}
