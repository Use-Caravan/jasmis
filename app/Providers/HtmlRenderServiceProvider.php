<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class HtmlRenderServiceProvider extends ServiceProvider
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
        App::bind('htmlrender', function()

        {

            return new \App\Helpers\HtmlRender;

        });
    }
}
