<?php

namespace App\Api;

use App\{    
    Item as CommonItem,
    Api\ItemLang,
    Api\Branch,
    Api\BranchReview,
    Api\BranchLang,
    Api\Vendor,
    Api\VendorLang,
    Api\Cuisine,
    Api\CusineLang,
    Api\Category,
    Api\CategoryLang
};
use DB;
use App\OfferItem;

class Item extends CommonItem
{

    /**
     * @param string $itemKey for only show
     * @param string $branchKey for list of particular branch items 
     * @param integer $categoryId for list of particular category items     
     * @return json collection
     */
    public static function getItems($itemId = null, $branchId = null,$categoryId = null)
    {   
        $item = new Item();
        $branch = new Branch();        

        $current = date('Y-m-d H:i:s');        
        $offerTypeAmount = VOUCHER_DISCOUNT_TYPE_AMOUNT;
        $offerTypeAmount = VOUCHER_DISCOUNT_TYPE_AMOUNT;

        /* $offer_price = "SELECT IF(`O`.`offer_type` = $offerTypeAmount, (`item`.`item_price` - `O`.`offer_value`), ( `item`.`item_price` - (`item`.`item_price` / 100 ) * `O`.`offer_value`) ) FROM `offer_item` AS `OI`
        LEFT JOIN `offer` as `O` ON `OI`.`offer_id` = `O`.`offer_id` 
        WHERE `OI`.`item_id` = `item`.`item_id` AND `O`.`branch_id` = `item`.`branch_id` AND `O`.`start_datetime` < '$current' AND `O`.`end_datetime` > '$current' LIMIT 1";

        */

        /* $offer_value = "SELECT `O`.`offer_value` FROM `offer_item` AS `OI`
        LEFT JOIN `offer` as `O` ON `OI`.`offer_id` = `O`.`offer_id` 
        WHERE `OI`.`item_id` = `item`.`item_id` AND `O`.`branch_id` = `item`.`branch_id` AND `O`.`start_datetime` < '$current' AND `O`.`end_datetime` > '$current' LIMIT 1";

        $offer_type = "SELECT `O`.`offer_type` FROM `offer_item` AS `OI`
        LEFT JOIN `offer` as `O` ON `OI`.`offer_id` = `O`.`offer_id` 
        WHERE `OI`.`item_id` = `item`.`item_id` AND `O`.`branch_id` = `item`.`branch_id` AND `O`.`start_datetime` < '$current' AND `O`.`end_datetime` > '$current' LIMIT 1"; */

        $subQuery = "SELECT `OI`.`item_id`,`O`.`offer_value`,`O`.`offer_type` FROM `offer_item` AS `OI`
        LEFT JOIN `offer` as `O` ON `OI`.`offer_id` = `O`.`offer_id` 
        WHERE `O`.`status` = ".ITEM_ACTIVE." AND `O`.`deleted_at` IS NULL AND `O`.`branch_id` = `branch_id` AND `O`.`start_datetime` < '$current' AND `O`.`end_datetime` > '$current'";

        $query = Item::getAll()
        ->addSelect([            
            'offer_item.offer_type',
            'offer_item.offer_value',
        ])

        # SELECT qO.offer_type, qO.offer_value
        # LEFT JOIN (query) AS qO ON 
        ->leftJoin(DB::raw("($subQuery) as offer_item"), function($query) {
            $query->on(Item::tableName().'.item_id','=', OfferItem::tableName().'.item_id');
        })        
        ->where([
            Item::tableName().'.status' => ITEM_ACTIVE,
            //Cuisine::tableName().'.status' => ITEM_ACTIVE,
            Item::tableName().'.approved_status' => BRANCH_APPROVED_STATUS_APPROVED
        ])
        ->where(function($query) use($branchId, $itemId, $categoryId) {
            if ($branchId !== null) {
                if(is_integer($branchId)) {
                    $query->where([Branch::tableName().'.branch_id' => $branchId]);
                } else if(is_string($branchId)) {
                    $query->where([Branch::tableName().'.branch_key' => $branchId]);
                }
            }
            
            if($itemId !== null) {
                if(is_integer($itemId)) {
                    $query->where([Item::tableName().'.item_id' => $itemId]);
                } else if(is_string($itemId)) { 
                    $query->where([Item::tableName().'.item_key' => $itemId]);
                }            
            }
            if($categoryId !== null) {
                $query->where([Item::tableName().'.category_id' => $categoryId]);
            }
            /* for Web filter */
            if(request()->category_id !== null) {
                $query->where([Item::tableName().'.category_id' => request()->category_id]);
            }    
            if(request()->item_name !== null && request()->vendor_id !== null) {
                $query->orwhere("IL.item_name", 'like' , "%".request()->item_name."%")->whereIn(Vendor::tableName().".vendor_id", request()->vendor_id);
                $query->orwhere("VL.vendor_name", 'like' , "%".request()->item_name."%")->whereIn(Vendor::tableName().".vendor_id", request()->vendor_id);;
            } 
            /* for Web filter */
        });
              
        /**
         * Order by
         */
        if(request()->orderby_delivery_time  == 1) {
            $orderBy = Branch::tableName().'.delivery_time';
        }
        else if(request()->orderby_rating  == 1) {
            // $orderBy = BranchReview::tableName().'.rating';
            $orderBy = DB::raw("(SELECT AVG(rating) from branch_review)");
            $sortBy = 'asc';
        }
        else if(request()->orderby_min_order_value == 1) {
            $orderBy = Vendor::tableName().".min_order_value";
            $sortBy = 'asc';
        }
        else if(request()->orderby_popularity == 1) {
            $orderBy = Vendor::tableName().".popular_status";
            $sortBy = 'desc';
        }
        else{
            $orderBy = Item::tableName().'.sort_no';
            $sortBy = 'asc';
        }


        $query = $query->groupBy(Item::tableName().'.item_id')->orderBy($orderBy,$sortBy);
        
        //echo $query->toSql();

        return $query;
    }    
}
