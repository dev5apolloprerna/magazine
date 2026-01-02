<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load helper files early so functions are available everywhere
        $helpers = [
            app_path('Helpers/anvixa_paths.php'),
            app_path('Helpers/anvixa_uploads.php'),
        ];

        foreach ($helpers as $file) {
            if (is_file($file)) {
                require_once $file;
            }
        }

        /**
         * If you prefer to automatically include every file in app/Helpers,
         * replace the block above with this:
         *
         * foreach (glob(app_path('Helpers/*.php')) as $file) {
         *     require_once $file;
         * }
         */
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap pagination views
        // If you're on Bootstrap 5, you can switch to: Paginator::useBootstrapFive();
        Paginator::useBootstrap();
    }
}
