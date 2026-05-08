<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <--- ADD THIS IMPORT

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * OBJECTIVE: Force Laravel to use Bootstrap 5 styling for pagination links.
         * OUTCOME: Transforms plain text links into professional, themed buttons.
         */
        Paginator::useBootstrapFive();
    }
}