<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\Controller;
use App\Http\Resources\Api\V1\OfferResource;
use App\Api\{
    Offer,
    OfferLang,    
    OfferItem,
    Item,
    ItemLang,
    Vendor,
    Branch,
    BranchLang,
    Category    
};
use DB;


class OfferController extends Controller
{
    public function getOffers()
    {   
        $offerItem = OfferItem::addSelect([
                Offer::tableName().".*",
                Item::tableName().".*",
                Vendor::tableName().".color_code",
                Branch::tableName().".branch_key",
                Branch::tableName().".availability_status",
                Branch::tableName().".branch_slug",
                Category::tableName().".category_key",
                DB::raw("(SELECT COUNT(*) from branch_timeslot as BT where BT.branch_id = branch.branch_id and status = 1) as timeslotcount")
            ])
            ->leftJoin(Item::tableName(),OfferItem::tableName().".item_id",'=',Item::tableName().".item_id")
            ->leftJoin(Offer::tableName(),OfferItem::tableName().".offer_id",'=',Offer::tableName().".offer_id")
            ->leftJoin(Vendor::tableName(),Offer::tableName().".vendor_id",'=',Vendor::tableName().".vendor_id")
            ->leftJoin(Branch::tableName(),Offer::tableName().".branch_id",'=',Branch::tableName().".branch_id")
            ->leftJoin(Category::tableName(),Item::tableName().".category_id",'=',Category::tableName().".category_id")
            ->where([
                Offer::tableName().'.status' => ITEM_ACTIVE,
                Item::tableName().'.status' => ITEM_ACTIVE,
                Branch::tableName().'.status' => ITEM_ACTIVE,
                Vendor::tableName().'.status' => ITEM_ACTIVE,
            ])
            ->whereNull(Offer::tableName().".deleted_at")
            ->whereNull(Item::tableName().".deleted_at")
            ->whereNull(Branch::tableName().".deleted_at")
            ->whereNull(Vendor::tableName().".deleted_at")
            ->where('start_datetime', '<', date('Y-m-d H:i:s'))
            ->where('end_datetime', '>', date('Y-m-d H:i:s'))
             ->havingRaw("timeslotcount > 0");
        if(request()->display_in_home != null) {
            $offerItem = $offerItem->where(Offer::tableName().'.display_in_home',request()->display_in_home);
        }
        OfferLang::selectTranslation($offerItem);
        BranchLang::selectTranslation($offerItem);
        ItemLang::selectTranslation($offerItem);        
        $offerItem = $offerItem->get();
        $data = OfferResource::collection($offerItem);
        $this->setMessage( __("apimsg.Offers are fetcted") );
        return $this->asJson($data);
    }
}
