<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LanguageScope;
use App\Faq;
use App;

class FaqLang extends CModel
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'faq_lang';
    
    /**
	 * The database table that primary key.
	 *
	 * @var string
	 */
    protected $primaryKey = 'faq_lang_id';

    /**
	 * The database table that translation foreign key.
	 *
	 * @var string
	 */
    protected $langForeignKey = "faq_id";       

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
    protected $fillable = ['faq_id','language_code', 'question','answer'];

   
    /**
    * @param query  $query     
    * @param string $alias
    * @return $query
    */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $faq = new Faq();
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'FL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $faq) {
            $query->on($faq->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")    
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
}
