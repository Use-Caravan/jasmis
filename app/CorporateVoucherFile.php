<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LanguageScope;
use App\City;
use App;

class CorporateVoucherFile extends CModel
{

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'corporate_voucher_file';
    
    /**
	 * The database table primary key
	 *
	 * @var string
	 */
    protected $primaryKey = 'corporate_voucher_file_id';
    
   
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
    protected $fillable = ['order_id','user_corporate_id','file_path'];

}
