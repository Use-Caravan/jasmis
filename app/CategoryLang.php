<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Database\Eloquent\Builder;
use App\Category;
use App\Scopes\LanguageScope;

class CategoryLang extends CModel
{

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'category_lang';
    
    /**
	 * The database table primary key
	 *
	 * @var string
	 */
    protected $primaryKey = 'category_lang_id';

    /**
	 * The database table translation foreign key
	 *
	 * @var string
	 */
    protected $langForeignKey = "category_id";

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
    protected $fillable = ['category_id','language_code','category_name'];


     /**
     * @param query  $query     
     * @param string $alias
     * @return $query
     */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $category = new Category();
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'CL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }

        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $category) {
            $query->on($category->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
}
