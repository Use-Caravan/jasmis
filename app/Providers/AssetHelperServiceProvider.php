<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class AssetHelperServiceProvider extends ServiceProvider
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
        App::bind('assethelper', function()
        {

            return new \App\Helpers\AssetHelper;

        });
    }
}
