<?php

namespace Nksquare\Payu;

use Illuminate\Support\ServiceProvider;
use Nksquare\Payu\Console\Commands\PayuTable;
use Nksquare\Payu\Console\Commands\AlterPayuTable;

class PayuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('payu', function ($app) {
            return new Payu();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views','payu');
        
        $this->mergeConfigFrom(__DIR__ .'/config/payu.php', 'payu');

        $this->publishes([
            __DIR__.'/config/payu.php' => config_path('payu.php')
        ],'config');

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        if ($this->app->runningInConsole()) 
        {
            $this->commands([
                PayuTable::class,
                AlterPayuTable::class
            ]);
        }
    }
}
