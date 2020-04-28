<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Database\Eloquent\Builder;
use App\Item;
use App\Scopes\LanguageScope;

class ItemLang extends CModel
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'item_lang';

    /**
     * The database table primary key
     *
     * @var string
     */
    protected $primaryKey = 'item_lang_id';

    /**
     * The database table translation foreign key
     *
     * @var string
     */
    protected $langForeignKey = "item_id";


    protected $keyGenerate = false;

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
    protected $fillable = ['item_id','language_code','item_name','item_image','item_description','allergic_ingredient'];

    /**
     * Table fillable file columns and path
     *
     * @var array
     */
    protected $fileInput = [ 'item_image' => [ 'path' => APP_ITEM_PATH ] ];


     public function vendor()
    {
        return $this->belongsTo('App\Item',$this->$langForeignKey,$this->$langForeignKey);
    }

    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $item = new Item();
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'IL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias,$langForeignKey, $item) {
            $query->on($item->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
   
}
