<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LanguageScope;
use App\Area;
use App;	

class AreaLang extends CModel
{
     /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'area_lang';
    
    /**
	 * The database table primary key.
	 *
	 * @var string
	 */
    protected $primaryKey = 'area_lang_id';

    /**
	 * The database table translation foreign key.
	 *
	 * @var string
	 */
    protected $langForeignKey = "area_id";
 
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
    protected $fillable = ['area_id','language_code','area_name'];

    

     /**
     * @param query  $query     
     * @param string $alias
     * @return $query
     */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $area = new Area();
        $tableName = $self->table;
        if($alias == ''){
        	$alias = 'AL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey  && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $area) {
            $query->on($area->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    } 
}
