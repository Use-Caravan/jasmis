<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Deliveryboy;
use Common;
use App;

class DeliveryboyLang extends CModel
{
     /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'deliveryboy_lang';

    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'deliveryboy_lang_id';

    /**
     * The attributes that foreign key.
     *
     * @var string
     */
    protected $langForeignKey = 'deliveryboy_id';
   
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
    protected $fillable = ['deliveryboy_id','language_code','deliveryboy_name','address'];      
    
    
    /**
    * @param query  $query     
    * @param string $alias
    * @return $query
    */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $deliveryBoy = new Deliveryboy();
        $tableName = $self->table;        
        if($alias == ''){
            $alias = 'DBL';
        }

        $selectable = $self->fillable;        
        $langForeignKey = $self->langForeignKey;   
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
                $selects[] = "$alias.$value";
            }            
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $deliveryBoy) {
            $query->on($deliveryBoy->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);        
    }
}
