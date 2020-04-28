<?php

namespace App\Api;

use App\Category as CommonCategory;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Api\Branch;
use App\Api\Item;
use DB;

class Category extends CommonCategory
{
    public static function getCategories()
    {
        $category = new self();
        $category = $category::getList()->where([
            $category->getTable().".status" => ITEM_ACTIVE,
            $category->getTable().".is_main_category" => 1,
        ])->orderBy($category->getTable().".sort_no",'asc')->addSelect([
            DB::raw(" (SELECT COUNT(I.category_id) FROM item AS I LEFT JOIN branch AS B ON I.branch_id = B.branch_id WHERE I.status = ".ITEM_ACTIVE." and B.status = ".ITEM_ACTIVE." and B.deleted_at IS NULL AND I.deleted_at IS NULL and B.deleted_at IS NULL and I.category_id = category.category_id and B.branch_key = '".request()->branch_key."' ) as category_count")
        ])        
        ->havingRaw('category_count > 0');
        return $category;
    }
}
