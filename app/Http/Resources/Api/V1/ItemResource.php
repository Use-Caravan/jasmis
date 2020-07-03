<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\V1\IngredientGroupResource;
use App\Api\IngredientGroup;
use App\Api\Cart;
use App\Api\CartItem;
use App\Api\Vendor;
use App\Api\VendorLang;
use App\Api\CategoryLang;
use App\Api\CuisineLang;
use App\Api\Branch;
use App\Api\BranchReview;
use App\Api\ItemLang;
use FileHelper;
use Common;
use DB;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);    

        $vendor = new Vendor();

        return [
            'item_id' => $this->item_id,            
            'item_key' => $this->item_key,
            'branch_id' => $this->branch_id,
            'vendor_id' => $this->vendor_id,
            'vendor_name' => $this->vendor_name,
            'arabic_vendor_name' => VendorLang::where('vendor_id',$this->vendor_id)->where('language_code','ar')->value('vendor_name'),
            'vendor_key' => Vendor::where('vendor_id',$this->vendor_id)->value('vendor_key'),
            'offer_enable' => $this->when(true,function() {
                return ($this->offer_type !== null && $this->offer_type != '') ? true : false;
            }),
            'offer_type' => $this->offer_type,
            'offer_value' => $this->when(true,function() {
                if($this->offer_type == VOUCHER_DISCOUNT_TYPE_AMOUNT) {
                    return Common::currency($this->offer_value);
                } else {
                    return $this->offer_value." %";
                }
            }),
            'offer_price' => $this->when(true,function() {
                if($this->offer_type == VOUCHER_DISCOUNT_TYPE_AMOUNT) {
                    return Common::currency($this->item_price - $this->offer_value);
                } else {
                    return Common::currency( $this->item_price - (($this->item_price/100) * $this->offer_value) );
                }
            }),
            'flat_offer_price' => $this->when(true,function() {
                if($this->offer_type == VOUCHER_DISCOUNT_TYPE_AMOUNT) {
                    return $this->item_price - $this->offer_value;
                } else {
                    return $this->item_price - (($this->item_price/100) * $this->offer_value) ;
                }
            }),
            'branch_key' => $this->branch_key,
            'delivery_time' => Branch::where('branch_key',$this->branch_key)->value('delivery_time'),
            'rating' => BranchReview::where('branch_id',$this->branch_id)->value('rating'),
            'min_order_value' => Vendor::where('vendor_id',$this->vendor_id)->value('min_order_value'),
            'item_name' => $this->item_name,
            'arabic_item_name' => ItemLang::where('item_id',$this->item_id)->where('language_code','ar')->value('item_name'),
            'item_image' => FileHelper::loadImage($this->item_image),
            'item_price' => Common::currency($this->item_price),
            'flat_item_price' => $this->item_price,
            'item_description' => $this->item_description,
            'arabic_item_description' => ItemLang::where('item_id',$this->item_id)->where('language_code','ar')->value('item_description'),
            'category_name' => $this->category_name,
            'arabic_category_name' => CategoryLang::where('category_id',$this->item_id)->where('language_code','ar')->value('category_name'),
            'cuisine_name' => $this->cuisine_name,    
            'arabic_cuisine_name' => CuisineLang::where('cuisine_id',$this->item_id)->where('language_code','ar')->value('cuisine_name'),
            'ingrdient_groups' =>  $this->when($this->item_id, function () {   

                if(!isset(request()->auto_suggestion)){
                    $ingredientGroupQuery = IngredientGroup::getIngredientGroups($this->item_id)->get();
                    return IngredientGroupResource::collection($ingredientGroupQuery);
                }
            }),
            'in_cart' => $this->when(true,function() {
                
                if( (!auth()->guard(GUARD_USER_API)->check()) && (!auth()->guard(GUARD_USER)->check())) {
                    return 0;
                } else {
                    if(auth()->guard(GUARD_USER_API)->check()) {                        
                        $userID = request()->user(GUARD_USER_API)->user_id;
                    }
                    if(auth()->guard(GUARD_USER)->check()) {
                        $userID = request()->user(GUARD_USER)->user_id;
                    }

                    $cart = Cart::where(['user_id' => $userID])->withTrashed(false)->first();                    
                    if($cart === null) {
                        return 0;
                    }
                    $cartItem = CartItem::where([
                        'cart_id' => $cart->cart_id,
                        'item_id' => $this->item_id,
                        // 'is_ingredient' => 0
                    ])->sum('quantity');
                    if($cartItem == 0){
                        return 0;
                    } 
                    return (int)$cartItem;
                }                
            }),
            'cart_item_key' => $this->when(true,function() {
                
                if( (!auth()->guard(GUARD_USER_API)->check()) && (!auth()->guard(GUARD_USER)->check())) {
                    $cart_item_key = [];
                } else {
                    if(auth()->guard(GUARD_USER_API)->check()) {                        
                        $userID = request()->user(GUARD_USER_API)->user_id;
                    }
                    if(auth()->guard(GUARD_USER)->check()) {
                        $userID = request()->user(GUARD_USER)->user_id;
                    }

                    $cart = Cart::where(['user_id' => $userID])->withTrashed(false)->first();                    
                    if($cart === null) {
                        return $cart_item_key = [];
                    }
                    $cart_item = CartItem::where([
                        'cart_id' => $cart->cart_id,
                        'item_id' => $this->item_id,
                        // 'is_ingredient' => 0
                    ])->pluck('cart_item_key')->toArray();
                    if($cart_item === null){
                        return $cart_item_key = [];
                    }else{
                        $cart_item_key = $cart_item;   
                    }
                    return $cart_item_key;
                    
                }                
            }),
            /* $this->mergeWhen($request->item, [
                'ingrdient_groups' =>  $this->when($this->item_id, function () {                    
                    $ingredientGroupQuery = IngredientGroup::getIngredientGroups($this->item_id)->get();
                    return IngredientGroupResource::collection($ingredientGroupQuery);
                }),
            ]), */
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function  with($request)
    {
        return [
            'status' => Response::HTTP_OK,
            'time' => strtotime(date('Y-m-d H:i:s')),
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->header('X-Value', 'kjh');
    }
}
