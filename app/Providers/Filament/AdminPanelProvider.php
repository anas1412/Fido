<?php

namespace App\Providers\Filament;

use Filament\Pages\Dashboard;
use App\Filament\Pages\EditCompanySettings;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\StatsOverviewPart2;
use Filament\Widgets\AccountWidget;
use App\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use App\Filament\Pages\EditTaxes;
use App\Filament\Pages\ModifyFiscalYear;
use App\Filament\Resources\ClientResource;
use App\Filament\Resources\CompanySettingResource;
use App\Filament\Resources\HonoraireResource;
use App\Filament\Resources\HonoraireReportResource;
use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\NoteDeDebitResource;
use App\Filament\Resources\NoteDeDebitReportResource;
use App\Filament\Resources\RetenueSourceResource;
use App\Filament\Resources\UserResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('dashboard')
            ->login() 
            ->profile(isSimple: false)
            ->userMenuItems([
                MenuItem::make('Edit Company')
                    ->url(fn () => EditCompanySettings::getUrl() ?? '#') // fallback
                    ->label(__('Edit Company')) // ensure label is set
                    ->icon('heroicon-o-building-office'),

                MenuItem::make('Edit Tax')
                    ->url(fn () => EditTaxes::getUrl() ?? '#')
                    ->label(__('Edit Tax'))
                    ->icon('heroicon-o-calculator'),

                MenuItem::make('Edit Fiscal Year')
                    ->url(fn () => ModifyFiscalYear::getUrl() ?? '#')
                    ->label(__('Edit Fiscal Year'))
                    ->icon('heroicon-o-calendar'),
            ])
            /* ->registration() */
            /* ->passwordReset() */

            ->brandName('Fido')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/logo.png'))
            ->colors([
                'primary' => Color::Green,
            ])
            
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->resources([
                // Conditionally register resources based on feature flags
                ...(config('features.honoraires') ? [HonoraireResource::class] : []),
                ...(config('features.note_de_debit') ? [NoteDeDebitResource::class] : []),
                ...(config('features.invoices') ? [InvoiceResource::class] : []),
                ...(config('features.honoraire_reports') ? [HonoraireReportResource::class] : []),
                ...(config('features.retenue_a_la_source_report') ? [RetenueSourceResource::class] : []),
                ...(config('features.note_de_debit_report') ? [NoteDeDebitReportResource::class] : []),
                // Other resources that should always be enabled or have their own flags
                ClientResource::class,
                CompanySettingResource::class,
                UserResource::class,
            ])
            ->pages([
                Dashboard::class,
                EditCompanySettings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StatsOverview::class,
                StatsOverviewPart2::class,
                AccountWidget::class,
                /* Widgets\FilamentInfoWidget::class, */
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css');
    }

    public function boot(): void
    {
         FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            fn (): string => Blade::render('<x-demo-banner />')
        );
    }
            
}
