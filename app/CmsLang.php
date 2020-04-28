<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LanguageScope;
use App\Cms;
use App;

class CmsLang extends CModel
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'cms_lang';
    
    /**
	 * The database table that primary key.
	 *
	 * @var string
	 */
    protected $primaryKey = 'cms_lang_id';

    /**
	 * The database table that translation foreign key.
	 *
	 * @var string
	 */
    protected $langForeignKey = "cms_id";       

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
    protected $fillable = ['cms_id','language_code', 'title','keywords','description','cms_content'];

   
    /**
    * @param query  $query     
    * @param string $alias
    * @return $query
    */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $cms = new Cms();
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
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $cms) {
            $query->on($cms->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")    
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
}
