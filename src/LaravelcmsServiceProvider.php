<?php

namespace Ardi7923\Laravelcms;

use Illuminate\Support\ServiceProvider;
use Ardi7923\Laravelcms\Console\Commands\CrudAjax;
use Ardi7923\Laravelcms\Console\Commands\CrudAjaxBladeCompiler;
use Ardi7923\Laravelcms\Console\Commands\CrudAjaxSetCommand;

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
            __DIR__.'/assets/js/share' => public_path('js'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CrudAjax::class,
                CrudAjaxBladeCompiler::class,
                CrudAjaxSetCommand::class
            ]);
        }
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
