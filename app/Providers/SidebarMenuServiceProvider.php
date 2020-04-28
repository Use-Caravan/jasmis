<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class SidebarMenuServiceProvider extends ServiceProvider
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
        App::bind('sidebarmenu', function()
        {

            return new \App\Helpers\SidebarMenu;

        });
    }
}
