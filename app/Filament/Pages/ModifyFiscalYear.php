<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

class ModifyFiscalYear extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.modify-fiscal-year';
    public ?string $fiscalYear = null;
    protected static ?string $navigationLabel = 'Modifier l\'année de l\'exercice';
    protected static ?string $slug = 'modify-fiscal-year';
    protected static ?string $title = 'Modifier l\'année de l\'exercice';

    protected static ?string $navigationGroup = "Parametres";
    public static function getNavigationSort(): ?int
    {
        return 99; // A high number to ensure it appears at the bottom
    }


    public function mount(): void
    {
        $this->fiscalYear = config('fiscal_year.current_year', date('Y'));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('fiscalYear')
                    ->label("Année de l'exercice")
                    ->required()
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        // Update the config file
        $path = config_path('fiscal_year.php');
        $content = "<?php\n\nreturn [\n    'current_year' => '{$data['fiscalYear']}',\n];";
        file_put_contents($path, $content);

        // Clear the config cache
        \Artisan::call('config:clear');

        Notification::make()
            ->title("L'année de l'exercice a été mise à jour")
            ->success()
            ->send();

        // Redirect to the dashboard home page
        $this->redirect(filament()->getHomeUrl());
    }
}
