<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LanguageScope;
use App\Cuisine;
use App;

class CuisineLang extends CModel
{

    /**
	 * The database table used by the model.
	 *
	 * @var string
	*/
    protected $table = 'cuisine_lang';
    
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'cuisine_lang_id';

    /**
     * The attributes that translation foreign key.
     *
     * @var string
     */
    protected $langForeignKey = "cuisine_id";

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
    protected $fillable = ['cuisine_id','language_code','cuisine_name'];


    public static function selectTranslation($query, $alias = '')
    {
        $self = new self();
        $cuisine = new Cuisine();
        if($alias == ''){
            $alias = 'CL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value) {
            if($value != $langForeignKey && $value != 'language_code') {
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$self->table as $alias" ,function($query) use ($alias, $langForeignKey, $cuisine) {
            $query->on($cuisine->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());

        })->addSelect($selects);
    }    
}
