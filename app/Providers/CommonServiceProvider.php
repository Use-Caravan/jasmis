<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class CommonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('common', function()
        {

            return new \App\Helpers\Common;

        });
    }
}
