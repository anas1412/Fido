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
                    ->minValue(1900) // Allow historical data if needed
                    ->maxValue(2100) // Future-proof for a while
                    ->rules(['digits:4', 'integer']),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $newFiscalYear = $data['fiscalYear'];

        try {
            // Construct the content for the config file
            $content = "<?php\n\nreturn [\n    'current_year' => '{$newFiscalYear}',\n];";

            // Get the path to the config file
            $path = config_path('fiscal_year.php');

            // Attempt to write to the file
            if (file_put_contents($path, $content) === false) {
                throw new \Exception('Failed to write to fiscal_year.php. Check file permissions.');
            }

            // Clear the config cache
            \Artisan::call('config:clear');

            Notification::make()
                ->title("L'année de l'exercice a été mise à jour avec succès.")
                ->success()
                ->send();

            // Redirect to the dashboard home page
            $this->redirect(filament()->getHomeUrl());

        } catch (\Exception $e) {
            Notification::make()
                ->title("Erreur lors de la mise à jour de l'année de l'exercice.")
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
