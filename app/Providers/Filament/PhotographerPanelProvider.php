<?php

namespace App\Providers\Filament;

use App\Filament\Photographer\Pages\Dashboard;
use App\Http\Middleware\EnsurePhotographerHasProfile;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PhotographerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('photographer')
            ->path('photographer')
            ->brandLogo(fn () => view('filament.logo'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('favicon.svg'))
            ->colors([
                'primary' => Color::hex('#d946ef'),
            ])
            ->font('Inter')
            ->discoverResources(in: app_path('Filament/Photographer/Resources'), for: 'App\\Filament\\Photographer\\Resources')
            ->discoverPages(in: app_path('Filament/Photographer/Pages'), for: 'App\\Filament\\Photographer\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Photographer/Widgets'), for: 'App\\Filament\\Photographer\\Widgets')
            ->widgets([])
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
                EnsurePhotographerHasProfile::class,
            ]);
    }
}
