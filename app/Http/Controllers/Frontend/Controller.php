<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Configuration;
use App\Cms;
use App\Language;
use App;
use Auth;
use Session;
use Common;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public $guard = GUARD_USER;    

    public function __construct()
    {                    
       $cms = Cms::getList()->where(['status' => ITEM_ACTIVE])->orderBy('sort_no','asc')->limit(6)->get();
       View::share('cms',$cms);               
       
    }

    /**
     * Just Reference => Call another controller function
     * $method1 = app('App\Http\Controllers\Api\V1\UserAddressController')->index();  Its not a good method according to PSR 
     * $method2 = (new APIUserAddressController)->index();
    */          
}
