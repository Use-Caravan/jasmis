<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AdminUser;
use Common;
use App;

class AdminUserLang extends CModel
{
      /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'admin_user_lang';

    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'admin_user_lang_id';

    /**
     * The attributes that foreign key.
     *
     * @var string
     */
    protected $langForeignKey = 'admin_user_id';
   
    /**     
      * Indicates if the model should be timestamped.
      *
      * @var bool
      */
    public $timestamps = false;

 
     /**
      * Table fillable column names first column should be foreign key
      *
      * @var array
      */
    protected $fillable = ['admin_user_id','language_code','first_name','last_name','address'];      
    
    
    /**
    * @param query  $query     
    * @param string $alias
    * @return $query
    */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $adminuser = new AdminUser();
        $tableName = $self->table;        
        if($alias == ''){
            $alias = 'AUL';
        }

        $selectable = $self->fillable;        
        $langForeignKey = $self->langForeignKey;   
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
                $selects[] = "$alias.$value";
            }            
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $adminuser) {
            $query->on($adminuser->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);        
    }
}
