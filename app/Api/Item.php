<?php

namespace App\Api;

use App\{    
    Item as CommonItem,
    Api\ItemLang,
    Api\Branch,
    Api\BranchReview,
    Api\BranchTimeslot,
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
    public static function getItems($itemId = null, $branchId = null,$categoryId = null,$new_items = null)
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
        ->where(function($query) use($branchId, $itemId, $categoryId, $new_items) {
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
                $category_det = CategoryLang::where("category_id", $categoryId)->get();
                $category_name = ( isset( $category_det[0] ) && $category_det[0]->category_name ) ? $category_det[0]->category_name : "";
                if( $category_name == "New Items" ) {
                    //$query->where([Item::tableName().'.newitem_status' => ITEM_ACTIVE])
                          //->orWhere([Item::tableName().'.category_id' => $categoryId]);
                    $query->where([Item::tableName().'.category_id' => $categoryId]);
                }
                else if( $categoryId == 0 ) {
                    $query->where([Item::tableName().'.newitem_status' => ITEM_ACTIVE]);
                }
                else
                    $query->where([Item::tableName().'.category_id' => $categoryId]);
            }
            if($new_items !== null) {
                $query->where([Item::tableName().'.newitem_status' => ITEM_ACTIVE]);
            }
            /* for Web filter */

            $deliveryBranchIDs = [];
            if(request()->latitude !== null &&  request()->longitude !== null) {

                /**
                 * Circle contains point reference link
                 * https://developers.google.com/maps/solutions/store-locator/clothing-store-locator
                 */            
                $deliveryAreasCircle = BranchDeliveryArea::select([
                    BranchDeliveryArea::tableName().".branch_id",
                    DeliveryArea::tableName().".zone_radius",
                    DB::raw(" ( 6371000 * acos( cos( radians(".request()->latitude.") ) * cos( radians( circle_latitude ) )
                    * cos( radians( circle_longitude ) - radians(".request()->longitude.") ) + sin( radians(".request()->latitude.") )
                    * sin( radians( circle_latitude ) ) ) ) as distance"),
                ])
                ->leftJoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().".delivery_area_id",DeliveryArea::tableName().".delivery_area_id")
                ->leftJoin(Branch::tableName(),Branch::tableName().".branch_id",BranchDeliveryArea::tableName().".branch_id")
                ->havingRaw("distance <=  ".DeliveryArea::tableName().".zone_radius")
                ->where([
                    DeliveryArea::tableName().".zone_type" => DELIVERY_AREA_ZONE_CIRCLE,
                    DeliveryArea::tableName().".status" => ITEM_ACTIVE,
                ])
                ->groupBy(BranchDeliveryArea::tableName().".branch_id")
                ->whereNull(DeliveryArea::tableName().".deleted_at")
                ->whereNull(Branch::tableName().".deleted_at")->get();

                //print_r($deliveryAreasCircle);exit;
                if($deliveryAreasCircle !== null) {                
                    $deliveryAreasCircle = $deliveryAreasCircle->toArray();
                    $deliveryBranchCircle = array_column($deliveryAreasCircle,'branch_id');
                    $deliveryBranchIDs = array_merge($deliveryBranchIDs, $deliveryBranchCircle);
                }
                
                /**
                 * Polygon contains point reference link
                 * https://gis.stackexchange.com/questions/79311/how-to-find-points-inside-each-polygon-in-mysql
                 * https://marcgg.com/blog/2017/03/13/mysql-viewport-gis/
                 */
                $deliveryAreasPolygon = BranchDeliveryArea::select([
                    BranchDeliveryArea::tableName().".branch_id",                
                ])
                ->leftJoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().".delivery_area_id",DeliveryArea::tableName().".delivery_area_id")
                ->leftJoin(Branch::tableName(),Branch::tableName().".branch_id",BranchDeliveryArea::tableName().".branch_id")
                ->where([
                    DeliveryArea::tableName().".zone_type" => DELIVERY_AREA_ZONE_POLYGON,
                    DeliveryArea::tableName().".status" => ITEM_ACTIVE,
                ])
                ->whereNull(DeliveryArea::tableName().".deleted_at")
                ->whereNull(Branch::tableName().".deleted_at")
                ->whereRaw("ST_CONTAINS(".DeliveryArea::tableName().".zone_latlng, Point(".request()->latitude.", ".request()->longitude."))")
                ->groupBy(BranchDeliveryArea::tableName().".branch_id")->get(); 
                            
                //print_r($deliveryAreasPolygon);exit;
                if($deliveryAreasPolygon !== null) {
                    $deliveryAreasPolygon = $deliveryAreasPolygon->toArray();
                    $deliveryBranchPolygons = array_column($deliveryAreasPolygon,'branch_id');
                    $deliveryBranchIDs = array_merge($deliveryBranchIDs,$deliveryBranchPolygons);
                }
                $currentTime = date('H:i:s');
                $deliveryBranchIDs = BranchTimeslot::where([
                                    'status' => ITEM_ACTIVE,
                                    'day_no' => date("N", strtotime(date('Y-m-d H:i:s'))),
                                    ])
                                    ->whereIn('branch_id',$deliveryBranchIDs)
                                    ->whereRaw('start_time < "'.$currentTime.'" and end_time > "'.$currentTime.'"')
                                    ->pluck('branch_id');  
                 

                $distance_array = [];
                $nearest = [];
                foreach ($deliveryBranchIDs as $distances) {
                    //find distances
                   $branch_nearest = Branch::where('branch_id',$distances)->first();

                   // $branch_distance = $this->twopoints_on_earth(request()->latitude, request()->longitude, 
                   //                      $branch_nearest->latitude,  $branch_nearest->longitude);

                   $lat1 = deg2rad(request()->latitude); 
                   $lon1 = deg2rad(request()->longitude); 
                   $lat2 = deg2rad($branch_nearest->latitude); 
                   $lon2 = deg2rad($branch_nearest->longitude); 
                   $unit = "K";

                      if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                        $distance =  0;
                      }
                      else {
                        $theta = $lon1 - $lon2;
                        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                        $dist = acos($dist);
                        $dist = rad2deg($dist);
                        $miles = $dist * 60 * 1.1515;
                        $unit = strtoupper($unit);

                        if ($unit == "K") {
                          $distance = ($miles * 1.609344);
                        } else if ($unit == "N") {
                          $distance = ($miles * 0.8684);
                        } else {
                          $distance = $miles;
                        }
                      }

                      $branch_arry = ['branch_id' => $distances, 'distance' => $distance,'vendor_id' => $branch_nearest->vendor_id];
                      array_push($distance_array, $branch_arry);

                }


                // $sort_by_distance = collect($distance_array)->sortBy('distance');
                // $unique_vendors = $sort_by_distance->unique('vendor_id');
                /*$nearest = [];
                foreach ($distance_array as $sort) {
                    array_push($nearest,$sort['branch_id']);
                }*/
                $sort_by_distance = collect($distance_array)->sortBy('distance');
                $unique_vendors = $sort_by_distance->unique('vendor_id');
                $nearest = [];
                foreach ($unique_vendors as $sort) {
                    array_push($nearest,$sort['branch_id']);
                }
                //print_r($nearest);exit;
                //echo '<pre>'; var_dump($query->toSql()); exit;
                $query = $query->whereIn(Branch::tableName().".branch_id", $nearest);
                //echo '<pre>'; var_dump($query->toSql()); exit;
                //print_r($query->get());exit;

                /*if(request()->item_name !== null) {
                    $query->orwhere("IL.item_name", 'like' , "%".request()->item_name."%")->whereIn(Branch::tableName().".branch_id", $nearest);
                    $query->orwhere("VL.vendor_name", 'like' , "%".request()->item_name."%")->whereIn(Branch::tableName().".branch_id", $nearest);
                }*/

            }
            else
            {
                $query->whereNull(Branch::tableName().".deleted_at");
            }
            //echo '<pre>'; var_dump($query->toSql()); exit;
            
            ItemLang::selectTranslation($query,'IL');
            VendorLang::selectTranslation($query,'VL');
            BranchLang::selectTranslation($query,'BRL');
            if(request()->item_name !== null) {
                $query = $query->Where("IL.item_name", 'like' , "%".request()->item_name."%")
                               //->orWhere("branch_name", 'like' , "%".request()->item_name."%")
                               //->orWhere("VL.vendor_name", 'like' , "%".request()->item_name."%")
                               ->where([Vendor::tableName().".approved_status" => BRANCH_APPROVED_STATUS_APPROVED,Vendor::tableName().".approved_status" => ITEM_ACTIVE]);

                //$query = $query->where([Vendor::tableName().".approved_status" => BRANCH_APPROVED_STATUS_APPROVED,Vendor::tableName().".approved_status" => ITEM_ACTIVE]);
            }
            //echo '<pre>'; var_dump($query->toSql()); exit;
            //print_r($deliveryBranchIDs);exit;
                
            if(request()->category_id !== null) {
                $query->where([Item::tableName().'.category_id' => request()->category_id]);
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
