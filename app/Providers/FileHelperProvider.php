<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class FileHelperProvider extends ServiceProvider
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
        App::bind('filehelper', function()
        {
            return new \App\Helpers\FileHelper;

        });
    }
}
