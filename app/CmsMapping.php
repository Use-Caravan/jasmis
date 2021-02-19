<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{
    Category,
    Scopes\LanguageScope
};
use Common;
use DB;
use App;


class CmsMapping extends CModel
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cms_mapping';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'cms_mapping_id';
        

    /**
     * Off timestampt to insert
     *
     * @var bool
     */
    public $timestamps = false;

	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['cms_mapping_id','cms_id','vendor_id','branch_id','status'];    
    

    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    }
}
