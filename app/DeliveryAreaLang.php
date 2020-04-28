<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DeliveryArea;
use Common;
use App;

class DeliveryAreaLang extends CModel
{

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'delivery_area_lang';

    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'delivery_area_lang_id';

    /**
     * The attributes that foreign key.
     *
     * @var string
     */
    protected $langForeignKey = 'delivery_area_id';
   
    /**     
      * Indicates if the model should be timestamped.
      *
      * @var bool
      */
    public $timestamps = false;

 
     /**
      * Table fillable column names first column should be foreign key
      *
      * @var array
      */
    protected $fillable = ['delivery_area_id','language_code','delivery_area_name'];      
    
    
    /**
    * @param query  $query     
    * @param string $alias
    * @return $query
    */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $deliveryArea = new DeliveryArea();
        $tableName = $self->table;        
        if($alias == ''){
            $alias = 'DAL';
        }

        $selectable = $self->fillable;        
        $langForeignKey = $self->langForeignKey;   
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
                $selects[] = "$alias.$value";
            }            
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $deliveryArea) {
            $query->on($deliveryArea->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);        
    }
}
