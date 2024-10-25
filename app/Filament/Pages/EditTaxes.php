<?php

namespace App\Filament\Pages;

use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Widgets\InfoBox;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class EditTaxes extends Page
{
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

    public $tva;
    public $rs;
    public $tf;

    public function mount()
    {
        $this->tva = config('taxes.tva');
        $this->rs = config('taxes.rs');
        $this->tf = config('taxes.tf');
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('tva')
                ->label('Valeur de TVA en virgule')
                ->numeric()
                ->required()
                ->default($this->tva),
            TextInput::make('rs')
                ->label('Valeur de RS en virgule')
                ->numeric()
                ->required()
                ->default($this->rs),
            TextInput::make('tf')
                ->label('Valeur de Timbre Fiscale en dinars')
                ->numeric()
                ->required()
                ->default($this->tf),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        // Update the config file
        $taxes = [
            'tva' => $data['tva'],
            'rs' => $data['rs'],
            'tf' => $data['tf'],
        ];

        $path = config_path('taxes.php');
        $content = "<?php\n\nreturn " . var_export($taxes, true) . ";\n";
        File::put($path, $content);

        Notification::make()
            ->title('Taxes mises à jour avec succès!')
            ->success()
            ->send();
    }

    protected function getActions(): array
    {
        return [
            /* \Filament\Actions\Action::make('save')
                ->label('Sauvgarder')
                ->action('submit')
                ->color('primary'), */];
    }

    protected function getViewData(): array
    {
        return [
            'tva' => $this->tva,
            'rs' => $this->rs,
            'tf' => $this->tf,
        ];
    }
}