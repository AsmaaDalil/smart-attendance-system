<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->viteTheme('resources/css/filament/admin/theme.css')

            /*
            |--------------------------------------------------------------------------
            | Branding
            |--------------------------------------------------------------------------
            */

            ->brandName('Smart Attendance')
            ->brandLogo(fn () => view('filament.admin.logo'))
            ->brandLogoHeight('3.5rem')
            ->sidebarWidth('15rem')

            /*
            |--------------------------------------------------------------------------
            | Disable Global Search
            |--------------------------------------------------------------------------
            */

            ->globalSearch(false)

            /*
            |--------------------------------------------------------------------------
            | Colors
            |--------------------------------------------------------------------------
            */

            ->colors([
                'primary' => [
                    50 => '#f1f7f5',
                    100 => '#dcebe6',
                    200 => '#b9d7ce',
                    300 => '#8bb9ac',
                    400 => '#599586',
                    500 => '#367568',
                    600 => '#1a4a40',
                    700 => '#173f37',
                    800 => '#14352f',
                    900 => '#102b27',
                    950 => '#081a17',
                ],

                'warning' => [
                    50 => '#fdf8f3',
                    100 => '#faeee2',
                    200 => '#f3dac2',
                    300 => '#ebc19b',
                    400 => '#dda375',
                    500 => '#d4a373',
                    600 => '#b77d51',
                    700 => '#965f3f',
                    800 => '#7c4e38',
                    900 => '#663f30',
                    950 => '#361f17',
                ],
            ])

            /*
            |--------------------------------------------------------------------------
            | Custom navigation
            |--------------------------------------------------------------------------
            */

            ->navigationItems([
                NavigationItem::make('Back to Main Dashboard')
                    ->url(fn (): string => route('admin.dashboard'))
                    ->icon('heroicon-o-arrow-left')
                    ->sort(-100),
            ])

            /*
            |--------------------------------------------------------------------------
            | Sidebar logout
            |--------------------------------------------------------------------------
            */

            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_END,
                fn () => view('filament.admin.sidebar-footer'),
            )

            /*
            |--------------------------------------------------------------------------
            | External dark mode button
            |--------------------------------------------------------------------------
            */

            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn () => view('filament.admin.theme-toggle'),
            )

            /*
            |--------------------------------------------------------------------------
            | Resources and pages
            |--------------------------------------------------------------------------
            */

            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources',
            )

            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages',
            )

            ->pages([
                Dashboard::class,
            ])

            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets',
            )

            ->widgets([])

            /*
            |--------------------------------------------------------------------------
            | Middleware
            |--------------------------------------------------------------------------
            */

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}