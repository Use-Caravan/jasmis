<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Common;
use DB;
use App;
use App\CategoryLang;
use App\Category;
use App\Branch;

class BranchCategory extends CModel
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'branch_category';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'category_id';
   
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['category_id','branch_id'];    
    

    public static function getList()
    {
        $self = new self();
        $category = new Category();
        $query = self::select($self->getTable().'.*')->leftJoin(
            $category->getTable(),
            $self->getTable().'.category_id',
            '=',
            $category->getTable().'.category_id'
        );
        return $query;
    }
    public static function selectCategory($branchId)
    {   
        $self = new self();
        $category = new Category();
        $editCuisine = self::getList()->where(
        [ 
            $self->getTable().'.branch_id' => $branchId,
            $category->getTable().'.status' => ITEM_ACTIVE, 
        ])->get()->toArray();
        
        $data = [];
        foreach ($editCuisine as $key => $value) {
            $data[] = $value['category_id'];
        }   
            return $data;
    }
    public static function getBranchCategory($branchId)
    {     
        $self = new self();
        $category = new Category();     
        $branch = new Branch();
        $branchCategory = new BranchCategory();     
        $query = self::select($category->getTable().'.category_id')
            ->leftJoin($branch->getTable(),$self->getTable().'.branch_id','=', $branch->getTable().'.branch_id')
            ->leftjoin($category->getTable(),$self->getTable().'.category_id','=', $category->getTable().'.category_id');
        CategoryLang::selectTranslation($query);
        $query = $query->where([$branch->getTable().'.branch_id' => $branchId])->whereNUll($category->getTable().'.deleted_at')->get()->toArray();
        return array_column($query,'category_name','category_id');
    }
}
