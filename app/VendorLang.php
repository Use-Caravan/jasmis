<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Database\Eloquent\Builder;
use App\Vendor;
use App\CAuthModel;
use App\Scopes\LanguageScope;

class VendorLang extends CAuthModel
{

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'vendor_lang';
    
    /**
	 * The database table primary key.
	 *
	 * @var string
	 */
    protected $primaryKey = 'vendor_lang_id';

    /**
	 * The database table translation foreign key.
	 *
	 * @var string
	 */
    protected $langForeignKey = "vendor_id";
       

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
    protected $fillable = ['vendor_id','language_code','vendor_name','vendor_logo','vendor_description','vendor_address'];   

    /**
     * Table file column names and path
     *
     * @var array
     */
    protected $fileInput = [ 'vendor_logo' => [ 'path' => VENDOR_LOGO_PATH ] ];

    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $vendor = new Vendor();
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'VL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
                $selects[] = "$alias.$value";
            }
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $vendor) {
            $query->on($vendor->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
}
