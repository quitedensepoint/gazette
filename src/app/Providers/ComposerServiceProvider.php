<?php

namespace Playnet\WwiiOnline\Gazette\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services for view composting
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Instruct the index to use a different shell around the newspaper
         * if a 'frame' parameter is passed in.
         * 
         * e.g. if you pass ?frame=bge, the master template used by index will
         * be views/layouts/master-bge.blade.php
         */
        $this->app['view']->composer( ['index', 'allied', 'axis'],function($view){
            
            // By default, put it in an iframe
            $view->frame = 'iframe';
            
            // if the input has a parameter called frame, use it
            if(request()->input('frame', '') != '')
            {
                $view->frame = request()->input('frame');
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
