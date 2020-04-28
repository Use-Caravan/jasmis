<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LanguageScope;
use App\Ingredient;
use App;

class IngredientLang extends CModel
{

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'ingredient_lang';
    
    /**
	 * The database table that primary key.
	 *
	 * @var string
	 */
    protected $primaryKey = 'ingredient_lang_id';

    /**
	 * The database table that translation foreign key.
	 *
	 * @var string
	 */
    protected $langForeignKey = "ingredient_id";       

	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Table fillable column names
     *
     * @var array
     */
    protected $fillable = ['ingredient_id','language_code', 'ingredient_name'];

   
    /**
    * @param query  $query     
    * @param string $alias
    * @return $query
    */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $ingredient = new Ingredient();
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'INL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $ingredient) {
            $query->on($ingredient->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")    
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
}
