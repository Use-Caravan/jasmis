<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CModel;
use App\OrderIngredient;
use App;

class OrderIngredientLang extends CModel
{    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'order_ingredient_lang';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'order_ingredient_lang_id';        
       
    
    /**
	 * The database table translation foreign key.
	 *
	 * @var string
	 */
    protected $langForeignKey = "order_ingredient_id";

	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['order_ingredient_id','language_code','ingredient_name'];
       
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();        
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'OIGL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey) {
            $query->on(OrderIngredient::tableName().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
}
