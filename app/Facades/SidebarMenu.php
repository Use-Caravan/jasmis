<?php

namespace App\Facades;


use Illuminate\Support\Facades\Facade;


class SidebarMenu extends Facade{


    protected static function getFacadeAccessor() { return 'sidebarmenu'; }

}