<?php

namespace App\Facades;



use Illuminate\Support\Facades\Facade;



class FileHelper extends Facade{



    protected static function getFacadeAccessor() { return 'filehelper'; }

}