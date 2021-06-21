<?php

namespace Ardi7923\Laravelcms;

use Illuminate\Support\ServiceProvider;

class LaravelcmsServiceProvider extends ServiceProvider
{

    /**
     * Boot the instance, add macros for datatable engines.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/en'),
        ]);

        $this->publishes([
            __DIR__.'/../public/assets/js/obs-main.js' => public_path('js'),
        ], 'main.js');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
