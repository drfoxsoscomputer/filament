<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use BezhanSalleh\PanelSwitch\PanelSwitch;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                // ->circular()
                ->locales(['es', 'en']);
        });
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch
                ->simple()
                // ->modalWidth('sm')
                ->visible(fn():bool => auth()->user()?->hasAnyRole([
                    'super_admin'
                ]));
        });
    }
}
