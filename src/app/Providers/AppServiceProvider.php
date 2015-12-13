<?php

namespace Playnet\WwiiOnline\Gazette\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Override the public path to conform to the server web root path
         * 
         * Current path at time of writing is /srv/gazette/web
         */
        $this->app->bind('path.public', function() {
          return base_path().'/web';
        });
    }
}
