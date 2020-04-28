<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Database\Eloquent\Builder;
use App\Branch;
use App\Scopes\LanguageScope;

class BranchLang extends CModel
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'branch_lang';
    
    /**
	 * The database table primary key
	 *
	 * @var string
	 */
    protected $primaryKey = 'branch_lang_id';

    /**
	 * The database table foreign key
	 *
	 * @var string
	 */
    protected $langForeignKey = "branch_id";

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
    protected $fillable = ['branch_id','language_code','branch_name','branch_logo','branch_address'];


    /**
     * @param query  $query     
     * @param string $alias
     * @return $query
     */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $branch = new Branch();
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'BL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $branch) {
            $query->on($branch->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
        
    }
}
