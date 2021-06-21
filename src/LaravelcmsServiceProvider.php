<?php

namespace Ardi7923\Laravelcms;

use Illuminate\Support\ServiceProvider;

class Laravel extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot the instance, add macros for datatable engines.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../transations', 'main');
    }
}
