<?php

namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Forcing SSL
        if (env('APP_ENV') == 'production') {
           $this->app['request']->server->set('HTTPS', true);
        
}        
        //Sharing Data to Views
        View::share([
            'pF' => '',
            'appName' => 'Dove SMS',
            'copyright' => 'Aifrruis LABS'
            ]);
    }
}
