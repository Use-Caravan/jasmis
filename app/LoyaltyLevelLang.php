<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LanguageScope;
use App\LoyaltyLevel;
use App;

class LoyaltyLevelLang extends CModel
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'loyalty_level_lang';
    
    /**
	 * The database table primary key
	 *
	 * @var string
	 */
    protected $primaryKey = 'loyalty_level_lang_id';

    /**
	 * The database table foreign key
	 *
	 * @var string
	 */
    protected $langForeignKey = "loyalty_level_id";

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
    protected $fillable = ['loyalty_level_id','language_code','loyalty_level_name'];

    /**
    * @param query  $query     
    * @param string $alias
    * @return $query
    */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $loyaltyLevel = new LoyaltyLevel();
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'LL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias,$langForeignKey ,$loyaltyLevel) {
            $query->on($loyaltyLevel->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")    
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
}
