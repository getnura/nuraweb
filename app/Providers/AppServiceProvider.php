<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades;
use Illuminate\View\View;
use App\Models\Language;
use App\View\Composers\AdminPageComposer;

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
        Facades\View::composer('*', function (View $view) {
            $view->with('languages', Language::get_languages());
            $view->with('active_languages', Language::get_active_languages());
            $view->with('site_text_dir', Language::get_active_language()->dir);
        });

    }
}
