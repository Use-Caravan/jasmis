<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Language;
use App\Configuration;
use App;
use Input;
use View;
use Route;
use Common;
use Session;
use Validator;

use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {        
        Schema::defaultStringLength(191);        
        $languages = Common::getLanguages(true);
        View::share('languages', $languages);
        

        Configuration::setConfiguration();                

        /**
         * Validation 
         */
        Validator::extend('logo_validate', function($attribute, $value, $parameters) {            
            $existsConfig = Configuration::where('configuration_name',$attribute)->first();
            if($value == '' && ($existsConfig == null || $existsConfig->configuration_value == null)){
                return false;
            }
            return true;
        });

        /**
         * End date time should be greater than start date time
         */
        Validator::extend('greater_than', function($attribute, $value, $parameters, $validator) {

            $startDateTime = strtotime(Input::get($parameters[0]));
            $endDateTime = strtotime($value);
            if($endDateTime < $startDateTime){
                return false;
            }            
            return true;
        });

        Validator::extend('weburl', function($attribute, $value, $parameters, $validator) {
            
            if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value)) {
                return true;
              }
              else {
                return false;
              }
        });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
