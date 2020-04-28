<?php

namespace App\Api;

use App\Http\Resources\Api\V1\BranchResource;
use Illuminate\Pagination\Paginator;
use App\{
    Branch as CommonBranch,
    Api\BranchLang,
    Api\Vendor,
    Api\VendorLang,
    Api\Country,
    Api\CountryLang,
    Api\City,
    Api\CityLang,
    Api\Area,
    Api\AreaLang,
    Api\BranchCuisine,
    Api\BranchTimeslot,
    Api\BranchReview
};
use DB;
use App;


class Branch extends CommonBranch
{    
	
    public static function getBranches()
    {
        /** Base Query End */
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
            ->havingRaw("distance <=  ".DeliveryArea::tableName().".zone_radius")
            ->where([
                DeliveryArea::tableName().".zone_type" => DELIVERY_AREA_ZONE_CIRCLE,
                DeliveryArea::tableName().".status" => ITEM_ACTIVE,
            ])
            ->groupBy(BranchDeliveryArea::tableName().".branch_id")
            ->whereNull(DeliveryArea::tableName().".deleted_at")->get();

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
            ->where([
                DeliveryArea::tableName().".zone_type" => DELIVERY_AREA_ZONE_POLYGON,
                DeliveryArea::tableName().".status" => ITEM_ACTIVE,
            ])
            ->whereNull(DeliveryArea::tableName().".deleted_at")
            ->whereRaw("ST_CONTAINS(".DeliveryArea::tableName().".zone_latlng, Point(".request()->latitude.", ".request()->longitude."))")
            ->groupBy(BranchDeliveryArea::tableName().".branch_id")->get(); 
                        
            if($deliveryAreasPolygon !== null) {
                $deliveryAreasPolygon = $deliveryAreasPolygon->toArray();
                $deliveryBranchPolygons = array_column($deliveryAreasPolygon,'branch_id');
                $deliveryBranchIDs = array_merge($deliveryBranchIDs,$deliveryBranchPolygons);
            }
        }

        /** Base Query Start */
        $voucherSubQuery = "SELECT 
        `voucher`.`voucher_id` 
        FROM `voucher` 
        LEFT JOIN `voucher_beneficiary` ON `voucher`.`voucher_id` = `voucher_beneficiary`.`voucher_id` 
        WHERE `voucher`.`status` = ".ITEM_ACTIVE."
        AND `voucher`.`deleted_at` IS NULL
        AND FIND_IN_SET('".request()->app_type."', `voucher`.`app_type`)
        AND (`apply_promo_for` = '".VOUCHER_APPLY_PROMO_SHOPS."' OR `apply_promo_for` = '".PROMO_FOR_BOTH."')
        AND DATE(`expiry_date`) >= CURDATE()
        AND (
            `voucher_beneficiary`.`beneficiary_id` = `branch`.`branch_id` OR `promo_for_shops` = ".ITEM_ACTIVE."
        )
        LIMIT 1";
              
        $query = Branch::getList()->addSelect([
                Vendor::tableName().".*",
                Cuisine::tableName().".cuisine_id",
                DB::raw('(
                    SELECT group_concat(cuisine_name) FROM cuisine_lang AS CL
                    LEFT JOIN branch_cuisine AS BC ON BC.cuisine_id = CL.cuisine_id
                    WHERE branch_id = branch.branch_id and language_code = ? ) as branch_cuisine'),
                DB::raw("(SELECT AVG(branch_review.rating) from branch_review where branch_id = branch.branch_id and approved_status = 1 and deleted_at IS NULL) as branch_avg_rating"),
                DB::raw("(SELECT COUNT(branch_review.branch_review_id) from branch_review where branch_id = branch.branch_id and approved_status = 1 and deleted_at IS NULL) as branch_rating_count"),                
                DB::raw("(SELECT COUNT(*) from branch_timeslot as BT where BT.branch_id = branch.branch_id and status = 1) as timeslotcount"),
                DB::raw("(SELECT COUNT(branch.branch_id) from branch  where branch.vendor_id = vendor.vendor_id and branch.status = 1 and branch.approved_status = 1  and branch.deleted_at IS NULL and ((SELECT COUNT(*) from branch_timeslot as BT where branch.branch_id = BT.branch_id and BT.status = 1) > 0) ) as branch_count") 
            ])
        ->setBindings([App::getLocale()])
        ->leftjoin(BranchCuisine::tableName(),self::tableName().".branch_id",BranchCuisine::tableName().".branch_id")
        ->leftjoin(Cuisine::tableName(),BranchCuisine::tableName().".cuisine_id",Cuisine::tableName().".cuisine_id")
        ->leftjoin(Vendor::tableName(),self::tableName().".vendor_id",Vendor::tableName().".vendor_id")
        ->leftjoin(Country::tableName(),self::tableName().".country_id",Country::tableName().".country_id")
        ->leftjoin(City::tableName(),self::tableName().".city_id",City::tableName().".city_id")
        ->leftjoin(Area::tableName(),self::tableName().".area_id",Area::tableName().".area_id")
        ->leftjoin(BranchReview::tableName(), function($join) {
            $join->on(self::tableName().".branch_id", '=', BranchReview::tableName().".branch_id");
            $join->whereNull(BranchReview::tableName().".deleted_at");
        });

        if(!empty($deliveryBranchIDs) && request()->latitude !== null &&  request()->longitude !== null) {
            $query = $query->whereIn(Branch::tableName().".branch_id", $deliveryBranchIDs);
        }


        if(request()->voucher_branch !== null && request()->voucher_branch == true) {
            $query = $query->addSelect(DB::Raw("($voucherSubQuery) AS voucher_id"))
            ->groupBy(Branch::tableName().".branch_id")->havingRaw('voucher_id IS NOT NULL');
        }

       
        VendorLang::selectTranslation($query);        
        BranchLang::selectTranslation($query,'BRL');
        CuisineLang::selectTranslation($query);
        CountryLang::selectTranslation($query,'CYL');
        CityLang::selectTranslation($query,'CTL');
        AreaLang::selectTranslation($query);
        $query = $query->where([
            self::tableName().".status" => ITEM_ACTIVE,
            Vendor::tableName().".status" => ITEM_ACTIVE,
            Cuisine::tableName().".status" => ITEM_ACTIVE,
            City::tableName().".status" => ITEM_ACTIVE,
            Area::tableName().".status" => ITEM_ACTIVE,
            Country::tableName().".status" => ITEM_ACTIVE,
            self::tableName().".approved_status" => BRANCH_APPROVED_STATUS_APPROVED,
            Vendor::tableName().".approved_status" => BRANCH_APPROVED_STATUS_APPROVED
        ]);

        if(request()->latitude !== null &&  request()->longitude !== null) {
            
        }

        /* DB::raw(" (select price from delivery_charge where from_km <= (distance/1000) && to_km >= (distance/1000) LIMIT 1 OFFSET 0) as delivery_cost "), */

        if(request()->vendor_key != null) {
            $query = $query->where(Vendor::tableName().".vendor_key",request()->vendor_key)
            ->groupBy(self::tableName().".branch_id");
        } else {
            $query = $query->groupBy(Vendor::tableName().".vendor_id");
        }

        $query = $query->havingRaw("timeslotcount > 0");

        if(request()->order_type !== null) {
            $query = $query->whereIn(Branch::tableName().".order_type", [request()->order_type,ORDER_TYPE_BOTH]);
        }

        if(request()->branch_name !== null) {
            $query = $query->where("BRL.branch_name", 'like' , "%".request()->branch_name."%")
                           ->orWhere("VL.vendor_name", 'like' , "%".request()->branch_name."%")
                           ->where([Vendor::tableName().".approved_status" => BRANCH_APPROVED_STATUS_APPROVED,Vendor::tableName().".approved_status" => ITEM_ACTIVE]);
        }

        /**
         * Cuisine Filter
         */
        if(request()->cuisine !== null) {
            $query = $query->WhereIn(Cuisine::tableName().".cuisine_id", explode(',',request()->cuisine));
        }
    
        /**
         * Order by
         */
        if(request()->orderby_delivery_time  == 1) {
            $query = $query->orderBy(self::tableName().".delivery_time","asc");
        }
        else if(request()->orderby_rating  == 1) {
            $query = $query->orderBy("branch_avg_rating","asc");
        }
        else if(request()->orderby_min_order_value == 1) {
            $query = $query->orderBy(Vendor::tableName().".min_order_value","asc");
        }else if(request()->orderby_popularity == 1) {
            $query = $query->orderBy(Vendor::tableName().".popular_status","desc");
        }else{
            $query = $query->orderBy(self::tableName().".branch_id","asc");
        }
        
        /**
         * Limit Offset for paginate
         */
        if(request()->limit !== null) {
            $query = $query->limit(request()->limit);
        }
        if(request()->offset !== null) {
            $query = $query->offset(request()->offset);
        }        
        
    
        /**
         * Get One branch by key
         */
        if(request()->branch !== null) {
            $query = $query->Where([
                self::tableName().".branch_key" => request()->branch
            ]);
            $query = $query->orWhere([
                self::tableName().".branch_slug" => request()->branch,
            ]);
        } 
           
        /* echo '<pre>'; var_dump($query->toSql()); exit; */
        return $query;        
        /**  $data = new Paginator($query->get(), PER_PAGE); For manual paginate */
        
    }

}
