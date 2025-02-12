<?php

namespace App\Providers;

use App\Models\Page;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use View;

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
        View::composer('layouts.app', function ($view) {
            $pages = Page::where("status", "public")->get();
            $view->with('pages', $pages);
        });


        Paginator::useBootstrapFive();
        Paginator::useBootstrapFour();
    }
}
