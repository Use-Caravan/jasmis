<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;
use Session;

class Language extends Model
{    
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'language';
	
    protected $primaryKey = 'language_id';

	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function getList()
    {
    	return self::where('status', ITEM_ACTIVE)->get();
    }

    public static function getActiveCount()
    {
    	return self::where('status', ITEM_ACTIVE)->count();
    }


    public static function setupLanguage()
    {
        /**
         * Setup Lanugage for global
         */
        
        view()->composer('*', function($view)
        {
            $defaultLanguage = Language::where(['status' => ITEM_ACTIVE,'is_default' => 1])->first();            
            $isAdmin = false;
            $isUser = false;
            if (Auth::guard(APP_GUARD)->check()) {
                
                foreach (APP_GUARDS as $route => $guard) {
                    if (request()->is("$route/*")) {
                        $isAdmin = true;
                    }
                }
                if($isAdmin) {                
                    if(auth()->guard(APP_GUARD)->user()->default_language === null) {
                        if($defaultLanguage === null) {
                            App::setLocale('en');
                        } else {
                            App::setLocale($defaultLanguage->language_code);
                        }
                    } else {
                        App::setLocale(auth()->guard(APP_GUARD)->user()->default_language);
                    }
                }
            }

            if(Auth::guard(GUARD_USER)->check() && $isAdmin === false) {                
                
                if(auth()->guard(GUARD_USER)->user()->default_language === null) {
                    if($defaultLanguage === null) {
                        App::setLocale('en');
                    } else {
                        App::setLocale($defaultLanguage->language_code);
                    }
                } else {
                    App::setLocale(auth()->guard(GUARD_USER)->user()->default_language);
                }
            } 

            if($isUser === false && $isAdmin === false) {
                if(Session::has(SESSION_LANGUAGE)) {
                    App::setLocale(Session::get(SESSION_LANGUAGE));
                } else {
                    if($defaultLanguage === null) {
                        App::setLocale('en');
                    } else {
                        App::setLocale($defaultLanguage->language_code);
                    }
                }
            }
        });
        
        if(request()->wantsJson()) {            
            if(request()->server('HTTP_ACCEPT_LANGUAGE') !== null && request()->server('HTTP_ACCEPT_LANGUAGE') !== ''){
                App::setLocale(  request()->server('HTTP_ACCEPT_LANGUAGE') );
            }
        }  
    }
}
