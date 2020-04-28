<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Database\Eloquent\Builder;
use App\Banner;
use App\Scopes\LanguageScope;

class BannerLang extends CModel
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'banner_lang';
    
    /**
	 * The database table primary key
	 *
	 * @var string
	 */
    protected $primaryKey = 'banner_lang_id';

    /**
	 * The database table translation foreign key
	 *
	 * @var string
	 */
    protected $langForeignKey = "banner_id";    
   
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
    protected $fillable = ['banner_id','language_code','banner_name','banner_file'];

    /**
     * Table fillable file columns and path
     *
     * @var array
     */
    protected $fileInput = [ 
        'banner_file' => [ 'path' => APP_BANNER_PATH ],        
    ];

     /**
     * @param query  $query     
     * @param string $alias
     * @return $query
     */
    public static function selectTranslation($query,$alias = '')
    {
        $banner = new Banner();
        $self = new self();
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'BL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey  && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $banner) {
            $query->on($banner->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
}
