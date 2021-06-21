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
            __DIR__.'/../resources/lang' => resource_path('views/vendor/laravelcms'),
        ]);
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
