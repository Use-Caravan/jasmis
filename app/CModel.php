<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use App\Helpers\TranslationHelper;
use App\Scopes\LanguageScope;
use App\Language;
use FileHelper;
use Common;
use Input;
use App;
use DB;


class CModel extends Model
{	    
	public static function boot()
    {   
        parent::boot();            
        
        self::saving(function($model) {
                                    
            if(!$model->exists && property_exists($model, 'keyGenerate') == true && $model->keyGenerate == true){

                $key = Common::generateRandomString($model->getTable(),$model->uniqueKey);
                $model->{$model->uniqueKey} = $key;            
            }                        
		});
		self::saved(function($model) {
            if(method_exists($model,'transModel')){                
                self::saveOnLanguage($model->transModel(), $model->getKey(), Input::all());                
            }
		});
        self::creating(function($model){
            // ... code here
        });

        self::created(function($model){
            // ... code here
        });

        self::updating(function($model){
            //$model->updated_at = date('Y-m-d H:i:s');
        });

        self::updated(function($model){
            if(method_exists($model,'transModel')){
                self::saveOnLanguage($model->transModel(), $model->getKey(), Input::all());
            }
        });

        self::deleting(function($model){
            //$model->deleted_at = date('Y-m-d H:i:s');
        });
        self::deleted(function($model){
            // ... code here
        });	        
    }    


    /**
     * Load Translation attributes
     * @param object $modelLang
     * @param integer $primaryKey 
     */
    public static function loadTranslation($modelLang,$primaryKey)
    {                        
        $languages = Common::getLanguages();
        $fillables = $modelLang->fillable;        
        $tableData = $modelLang::where([$modelLang->langForeignKey => $primaryKey])->limit(count($languages))->get()->toArray();
        $transLang = [];
        foreach($languages as $lkey => $value){            
            foreach($fillables as $fkey => $fvalue){                                
                foreach($tableData as $tkey => $tvalue){
                    if($tvalue['language_code'] == $lkey){                    
                        $transLang[$fvalue][$lkey] = $tvalue[$fvalue];
                    }
                }                
            }
        }       
        return $modelLang->fill($transLang);        
    }   
    
    /**
     * Save Translations attributes on table
     * @param object $modelLang
     * @param integer $primarykey      
     * @param array|object  $request
     */
    public static function saveOnLanguage($modelLang, $primaryKey, $request)
    {
        
        $routeName = explode('.',Route::currentRouteName());        
        if(count($routeName) > 0 && ( $routeName[1] == 'store' || $routeName[1] == 'update' )) {
            $languages = Common::getLanguages();
            $langCount = 0;
            $fillables = $modelLang->fillable;            
            foreach($languages as $langKey => $value) {
                $exists = null;                
                $exists = $modelLang::where([$modelLang->langForeignKey => $primaryKey,'language_code' => $langKey])->first();

                if($exists === null){

                    $langTrans = [
                        $modelLang->langForeignKey =>  $primaryKey,
                        'language_code' => $langKey,
                    ];                
                    foreach($fillables as $Fkey => $Fvalue){
                        
                        if($Fvalue != $modelLang->langForeignKey && $Fvalue != 'language_code'){
                            if(isset($request[$Fvalue]) && isset($request[$Fvalue][$langKey])){
                                $langTrans[$Fvalue] = $request[$Fvalue][$langKey];
                            }   
                        }
                        if( property_exists($modelLang, 'fileInput') && !empty($modelLang->fileInput) ){
                            foreach($modelLang->fileInput as $filename => $value){
                                if (array_key_exists($Fvalue,$modelLang->fileInput)){
                                    if(isset($request[$filename]) && $request[$filename] != null){
                                        foreach ($request[$filename] as $key => $image){
                                            if($key == $langKey){
                                                $langTrans[$filename] = FileHelper::uploadFile($image,$value['path']);
                                            }                                    
                                        }
                                    }
                                }                            
                            }
                        }
                    }
                    DB::table($modelLang->getTable())->insert($langTrans);
                }else{                    
                    $langTrans = json_decode( json_encode($exists) , true);
                    
                    foreach($fillables as $Fkey => $Fvalue){
                        $existsFile = $langTrans[$Fvalue];    
                        if($Fvalue != $modelLang->langForeignKey && $Fvalue != 'language_code'){
                            if(isset($request[$Fvalue]) && isset($request[$Fvalue][$langKey])){                            
                                $langTrans[$Fvalue] = $request[$Fvalue][$langKey];
                            }
                        }
                        if( property_exists($modelLang, 'fileInput') && !empty($modelLang->fileInput) ){
                            foreach($modelLang->fileInput as $filename => $value){
                                if (array_key_exists($Fvalue,$modelLang->fileInput)){
                                    if(isset($request[$filename]) && $request[$filename] != null){
                                        foreach ($request[$filename] as $key => $image){                                        
                                            if($key == $langKey){
                                                FileHelper::deleteFile($existsFile);
                                                $langTrans[$filename] = FileHelper::uploadFile($image,$value['path']);
                                            }                                    
                                        }
                                    }
                                }                            
                            }
                        }
                    }                
                    DB::table($modelLang->getTable())->where([$modelLang->langForeignKey => $primaryKey,'language_code' => $langKey])->update($langTrans);                
                }            
            }
        }
        return true;           
    }    
    
    /**
	 *
	 * @return table name
	 */
    public static function tableName()
    {
        $self = new static();
        return $self->getTable();
    }    


    /**
	 *
	 * @return data by key
	 */
	public static function findByKey($key)
	{
		return static::where(static::uniqueKey(), $key)->first();
    }
}
