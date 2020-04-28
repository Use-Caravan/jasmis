<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\CmsLang;
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;

class Cms extends CModel
{
    /**
     * Enable the softdelte 
     *
     * @var class
     */
    use SoftDeletes;


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cms';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'cms_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'cms_key';
    
    /**
     * The attributes that enable unique key generation.
     *
     * @var string
     */
    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['title','keywords','description','cms_content','csrf-token'];    
    
    /**
	 * Get Unique key to generate key
	 * @return string
	*/
    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    }    
    
    /**
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new CmsLang();
    }
    
        
	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{
		return self::where(self::uniqueKey(), $key)->first();
    }


    /**	 
	 *
	 * @var query
	 */
	public static function getList()
	{
        $self = new self();
        $query = self::select($self->getTable().'.*');
        CmsLang::selectTranslation($query);        
        return $query;
	}

    public function selectsections($values = null)
    {
        $sections = [ 
            SEC_1 => __('admincrud.Section 1'),
            SEC_2 => __('admincrud.Section 2'),
            SEC_3 => __('admincrud.Section 3'),
            SEC_4 => __('admincrud.Section 4'),
            SEC_5 => __('admincrud.Section 5'),
            SEC_6 => __('admincrud.Section 6'),

        ];
        if($values != null) {            
            $data  = explode(',',$values);            
            $section = '';
            foreach ($data as $key => $value) {
                $section .= (isset($section[$value])) ? ((count($data) == $key+1) ? $section[$value] : $section[$value].', ' ) : '';
            }            
            return $section;
        }
        return $sections;
    }

}
