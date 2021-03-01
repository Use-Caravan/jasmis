<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\Controller;
use App\Http\Controllers\Api\V1\OrderController;
use App\{
    Api\Cart,
    Api\CartItem,
    Api\Branch,
    Api\BranchLang,
    Api\Vendor,
    Api\VendorLang,
    Api\Item,
    Api\CuisineLang,
    Api\BranchCuisine,
    Api\IngredientGroupLang,
    Api\IngredientGroup,
    Api\Ingredient,
    Api\IngredientLang,
    Api\IngredientGroupMapping
};
use App;
use Validator;
use Common;
use DB;
use FileHelper;
use Storage;

class CartController extends Controller
{

    public function userCart($rawData = [])
    {
        DB::beginTransaction();
        try{
            
            if(empty($rawData)) {
                $rawData = json_decode(request()->getContent(), true);
            }
            
            $userID = request()->user()->user_id;

            $validator = Validator::make($rawData,[
                'branch_key' => 'required|exists:branch,branch_key',
                'item_key' => 'required|exists:item,item_key',
                'quantity' => 'required|numeric',
                'price_on_selection' => 'required|numeric|in:1,0',
                'sub_items' => 'required_unless:price_on_selection,0',
            ]);
            
            if($validator->fails()) {
                return $this->validateError($validator->errors());
            }

            $branchDetails = Branch::findByKey($rawData['branch_key']);
            if($branchDetails === null) {
                return $this->commonError( __("apimsg.Branch Not Found") );
            }
            
            /** Delete if branch key is mismatch */
            $exists = Cart::where(['user_id' => $userID])->first();
            if($exists !== null && ( $exists->branch_id != $branchDetails->branch_id )) {
                $exists->delete();
            }
            /** Delete if branch key is mismatch */
            
            $item = Item::findByKey($rawData['item_key']);
            if($item === null) {
                return $this->commonError( __("apimsg.Item Not Found") );
            }

            
            if($item->price_on_selection == 1) {
                if( !isset( $rawData['sub_items'] ) )
                    return $this->commonError( __("apimsg.The sub items field is required unless price on selection is in 0.") );
            }

            //print_r($rawData['sub_items']);exit;
            
            $sub_items = isset( $rawData['sub_items'] ) && !empty( $rawData['sub_items'] ) ? $rawData['sub_items'] : array();
            //print_r($sub_items);exit;

            $cart = Cart::where(['branch_id' => $branchDetails->branch_id,'user_id' => $userID])->first();
            
            if($cart === null) {               
                $cart = new Cart();
                $cart = $cart->fill([
                    'user_id' => $userID,
                    'branch_id' => $branchDetails->branch_id,
                ]);
                $cart->save();                
                goto cartItemAdd;
            } else {
                
                $getThisItem = CartItem::where(['cart_id' => $cart->cart_id,'item_id' => $item->item_id])->get();
                if($getThisItem === null) {
                    goto cartItemAdd;
                }                
                            
                $cart_item_same_pos_item = 0;    
                foreach($getThisItem as $key => $value) {
                    // if($value->is_ingredient == 0) {                        
                    //     $catItemID = $value->cart_item_id;
                    //     goto cartItemUpdate;
                    // } // code commented for item add issue with and without ingredient 
                    if( empty( $sub_items ) ) {
                        $existsIngredients = json_decode($value->ingredients,true);
                        $currentIngredients = $rawData['ingrdient_groups'];
                        if($existsIngredients == $currentIngredients) {
                            $catItemID = $value->cart_item_id;
                        
                            goto cartItemUpdate;                                                
                        }
                    }
                    else {
                        /*$existsSubItems = json_decode($value->price_on_selection_options,true);
                        //print_r($existsSubItems);exit;
                        $currentSubItems = $sub_items;
                        if($existsSubItems == $currentSubItems) {
                            $catItemID = $value->cart_item_id;
                        
                            goto cartItemUpdate;                                                
                        }*/

                        $item_price_on_selection_options = !empty( $item->price_on_selection_options ) ? json_decode($item->price_on_selection_options) : array();
                        $sub_item_exists_count = 0;
                        $cnt = 0;
                        foreach( $item_price_on_selection_options as $item_price_on_selection_option ) {
                            foreach( $sub_items as $sub_item ) {
                                //echo $sub_item['sub_item_name'];exit;
                                if( $item_price_on_selection_option->option_name == $sub_item['sub_item_name'] ) {
                                    $sub_item_exists_count++;
                                    $sub_items[$cnt]['sub_item_price'] = $item_price_on_selection_option->option_price;
                                    $sub_items[$cnt]['sub_item_sub_total'] = $item_price_on_selection_option->option_price * $sub_item['quantity'];
                                    $cnt++;
                                }                                
                            }   
                        }
                        //print_r($sub_items);exit;

                        $price_on_selection = isset($rawData['price_on_selection']) ? $rawData['price_on_selection'] : 0;
                        if( ( $sub_item_exists_count < count( $sub_items ) ) && $price_on_selection == 1 ) {
                            return $this->commonError( __("apimsg.Invalid sub items selected.") );
                        }

                        $existsPriceOnSelectionOptions = $value->price_on_selection_options;
                        $existsPriceOnSelectionOptions = json_decode($existsPriceOnSelectionOptions);
                        //print_r($existsPriceOnSelectionOptions[0]->ingrdient_groups);exit;
                        $existsIngredientsPOS = isset( $existsPriceOnSelectionOptions[0]->ingrdient_groups ) ? $existsPriceOnSelectionOptions[0]->ingrdient_groups : array();

                        $currentIngredientsPOS = $sub_items[0]['ingrdient_groups'];
                        $currentIngredientsPOS = $currentIngredientsPOS;
                        //print_r($currentIngredientsPOS);exit;

                        $existsSubItemId = isset( $existsPriceOnSelectionOptions[0]->sub_item_id ) ? $existsPriceOnSelectionOptions[0]->sub_item_id : 0;
                        $currentSubItemId = isset( $sub_items[0]['sub_item_id'] ) ? $sub_items[0]['sub_item_id'] : 0;

                        /** If POS item ingredients is same update the items otherwise add the items **/
                        if( ( $existsIngredientsPOS == $currentIngredientsPOS ) && ( $existsSubItemId == $currentSubItemId ) ) {
                            $catItemID = $value->cart_item_id;
                            $cart_item_same_pos_item++;
                            goto cartItemUpdate;                                                
                        }
                        //else
                            //$cart_item_same_pos_item++;
                            //goto cartItemAdd;

                        //$catItemID = $value->cart_item_id;
                        
                        //goto cartItemUpdate;
                    }
                }    
                //echo $cart_item_same_pos_item;exit;
                if( $cart_item_same_pos_item == 0 )            
                    goto cartItemAdd;
            }
            
            cartItemAdd:
            
                if( $rawData['quantity'] == 0 && empty( $sub_items ) ) {
                    goto cartDelete;
                }
                
                if( empty( $sub_items ) ) {
                    $isIngredient = ($rawData['ingrdient_groups'] === null || empty($rawData['ingrdient_groups']) || (count($rawData['ingrdient_groups']) == 0) ) ? 0 : 1;
                    $cartItem = new CartItem();
                    $cartItem = $cartItem->fill([
                        'cart_id' => $cart->cart_id,
                        'item_id' => $item->item_id,
                        'quantity' => $rawData['quantity'],
                        'is_ingredient' => $isIngredient,
                        'ingredients' => json_encode($rawData['ingrdient_groups']),
                        'item_instruction' => isset($rawData['item_instruction']) ? $rawData['item_instruction'] : "",
                    ]);
                    $cartItem->save();
                }
                else {
                    $isIngredient = ($rawData['ingrdient_groups'] === null || empty($rawData['ingrdient_groups']) || (count($rawData['ingrdient_groups']) == 0) ) ? 0 : 1;

                    $item_price_on_selection_options = !empty( $item->price_on_selection_options ) ? json_decode($item->price_on_selection_options) : array();
                    //print_r($item_price_on_selection_options);exit;

                    $sub_item_exists_count = 0;
                    $cnt = 0;
                    foreach( $item_price_on_selection_options as $item_price_on_selection_option ) {
                        foreach( $sub_items as $sub_item ) {
                            //echo $sub_item['sub_item_name'];exit;
                            if( $item_price_on_selection_option->option_name == $sub_item['sub_item_name'] ) {
                                $sub_item_exists_count++;
                                $sub_items[$cnt]['sub_item_price'] = $item_price_on_selection_option->option_price;
                                $sub_items[$cnt]['sub_item_sub_total'] = $item_price_on_selection_option->option_price * $sub_item['quantity'];
                                $cnt++;
                            }
                        }   
                    }

                    $price_on_selection = isset($rawData['price_on_selection']) ? $rawData['price_on_selection'] : 0;
                    if( $sub_item_exists_count == 0 && $price_on_selection == 1 ) {
                        return $this->commonError( __("apimsg.Invalid sub items selected.") );
                    }
            
                    $cartItem = new CartItem();
                    $cartItem = $cartItem->fill([
                        'cart_id' => $cart->cart_id,
                        'item_id' => $item->item_id,
                        'quantity' => $rawData['quantity'],
                        'is_ingredient' => $isIngredient,
                        'ingredients' => json_encode($rawData['ingrdient_groups']),
                        'item_instruction' => isset($rawData['item_instruction']) ? $rawData['item_instruction'] : "",
                        'price_on_selection' => isset($rawData['price_on_selection']) ? $rawData['price_on_selection'] : 0,
                        'price_on_selection_options' => ( isset($sub_items) && $price_on_selection == 1 ) ? json_encode($sub_items) : ""
                    ]);
                    $cartItem->save();
                }
                
                goto response;

            cartItemUpdate:
            
                if($rawData['quantity'] == 0 && empty( $sub_items )) {
                    goto cartDelete;
                }     
                                
                $cartItem = new CartItem();
                $cartItem = $cartItem->find($catItemID);

                $exist_price_on_selection = $cartItem->price_on_selection;
                if( $exist_price_on_selection == 1 ) {
                    $exist_price_on_selection_options = $cartItem->price_on_selection_options;
                    $exist_price_on_selection_options = json_decode( $exist_price_on_selection_options );

                    $exist_count = 0;
                    if( isset( $exist_price_on_selection_options ) && !empty( $exist_price_on_selection_options ) ) {
                        $exist_sub_item_count = 0;
                        //print_r($exist_price_on_selection_options);exit;
                        foreach( $exist_price_on_selection_options as $exist_price_on_selection_option ) {
                            foreach( $sub_items as $sub_item ) {
                                if( $exist_price_on_selection_option->sub_item_name == $sub_item["sub_item_name"] ) {
                                    $exist_price_on_selection_options[$exist_count] = (object)$sub_items;
                                    //array_push( (array)$exist_price_on_selection_options, $sub_items );
                                    $exist_count++;
                                    $exist_sub_item_count++;
                                }
                            }
                        }

                        if( $exist_sub_item_count == 0 ) {
                            $exist_price_on_selection_options_count = count( $exist_price_on_selection_options );
                            $exist_price_on_selection_options[$exist_price_on_selection_options_count + 1] = $sub_items;
                        }
                    }
                }
                
                /* $ingredients = json_decode($cartItem->ingredients);
                if(count($ingredients) > 0) {
                    $cartItem->quantity = $rawData['quantity'] + $cartItem->quantity;
                }
                else {
                    $cartItem->quantity = $rawData['quantity'];
                } */
                $cartItem->quantity = $rawData['quantity'];
                
                if(isset($rawData['item_instruction'])) {
                    $cartItem->item_instruction =  $rawData['item_instruction'];
                }

                $cartItem->price_on_selection = isset($rawData['price_on_selection']) ? $rawData['price_on_selection'] : 0;
                $cartItem->price_on_selection_options = ( isset($sub_items) && $rawData['price_on_selection'] == 1 ) ? json_encode($sub_items) : "";

                $cartItem->update();
                goto response;

            cartDelete:
                $getThisItem = CartItem::where(['cart_id' => $cart->cart_id,'item_id' => $item->item_id])->first();
                if($getThisItem !== null) {
                    $getThisItem->delete();
                    
                }                
                goto response;
            
            response:
            
            DB::commit();
            
            $this->setMessage( __("apimsg.Item has been updated in cart") );
            request()->request->add([
                'branch_key' =>  $rawData['branch_key']
            ]); 

            $cartitem_key = [
                'cart_item_key' => $cartItem['cart_item_key'],
            ];  
            $this->setData($cartitem_key);         
            return $this->asJson();
            
        } catch(Exception $e) {
            return $e->getMessage();
            DB::rollback();
            throw $e->getMessage();            
        }
    }

    public function updateQuantity()
    {
        $removeCartItem = CartItem::findByKey(request()->cart_item_key);
        //print_r($removeCartItem);exit;
        //echo $removeCartItem->price_on_selection;exit;
        if(request()->quantity == 0)
        {
            $updateCartItem = CartItem::where(['cart_item_key' => request()->cart_item_key])->delete();
        } else {
            if( $removeCartItem->price_on_selection == 1 ) {
                $price_on_selection_options_arr = array();
                //if( isset( request()->sub_item_id ) ) {
                    $price_on_selection_options = $removeCartItem->price_on_selection_options;
                    $price_on_selection_options = ( isset( $price_on_selection_options ) && !empty( $price_on_selection_options ) ) ? json_decode($price_on_selection_options) : array();
                    //print_r($price_on_selection_options);exit;
                    foreach ( $price_on_selection_options as $price_on_selection_option ) {
                        //if( $price_on_selection_option->sub_item_id == request()->sub_item_id ) {
                            $price_on_selection_option->quantity = request()->quantity;

                            $price_on_selection_option->sub_item_sub_total = $price_on_selection_option->sub_item_price * request()->quantity;
                            //print_r($price_on_selection_option);exit;
                            //break;
                        //}
                        $price_on_selection_options_arr[] = $price_on_selection_option;
                    }
                    //print_r($price_on_selection_options);exit;
                    //print_r($price_on_selection_options_arr);exit;

                    //$removeCartItem->price_on_selection_options = ( isset( $price_on_selection_options ) && !empty( $price_on_selection_options ) ) ? json_encode( $price_on_selection_options ) : "";
                    $removeCartItem->quantity = request()->quantity;
                    $removeCartItem->price_on_selection_options = ( isset( $price_on_selection_options_arr ) && !empty( $price_on_selection_options_arr ) ) ? json_encode( $price_on_selection_options_arr ) : "";
                    $removeCartItem->item_instruction = request()->item_instruction;
                    $removeCartItem->save();
                //}
            }
            else {
                $removeCartItem->quantity = request()->quantity;
                $removeCartItem->item_instruction = request()->item_instruction;
                $removeCartItem->save();
            }
        } 
        
        /** Write request data in text file **/
        $log_string = "Log Date Time - ".date("d-m-Y H:i:s").", "."Cart Item Key - ".request()->cart_item_key.", "."Cart Quantity - ".request()->quantity.", "."Item Instruction - ".request()->item_instruction."*********************";
        Storage::append('mobile_log.txt', $log_string);
        $this->setMessage( __("apimsg.Item have been updated") );
        return $this->asJson();
    }

    public function get_sub_item_ingrdients( $ingrdient_groups, $itemQuantity, $itemSubTotal )
    {
        $sub_items_ingredient_groups = array();
        if(isset($ingrdient_groups) && !empty($ingrdient_groups)) {
            foreach($ingrdient_groups as $igKey => $igValue) {
                $ingredientGroup = IngredientGroup::select(IngredientGroup::tableName().'.*');
                IngredientGroupLang::selectTranslation($ingredientGroup);
                //$igValue = (array)$igValue;
                //print_r($igValue);exit;
                $ingredientGroup = $ingredientGroup->where('ingredient_group_key',$igValue->ingredient_group_key)->first();
                if($ingredientGroup === null) {                    
                    return ['status'=> false, 'error' => __('apimsg.Invalid Ingredient group key')];
                }
                $sub_items_ingredient_groups[$igKey] = [

                    'ingredient_group_key' => $ingredientGroup->ingredient_group_key,
                    'ingredient_group_id' => $ingredientGroup->ingredient_group_id,
                    'ingredient_group_name' => $ingredientGroup->ingredient_group_name,
                    'arabic_ingredient_group_name' => IngredientGroupLang::where('ingredient_group_id',$ingredientGroup->ingredient_group_id)->where('language_code','ar')->value('ingredient_group_name'), 
                ];                
                $ingredientGroupSubTotal = 0;                
                if(!isset($igValue->ingredients)) {
                    return ['status'=> false, 'error' => __('apimsg.Ingredients are missing')];
                }
                foreach($igValue->ingredients as $iKey => $iValue) {
                    //$iValue = (array)$iValue;
                    $ingredients = IngredientGroupMapping::select(IngredientGroupMapping::tableName().".*",Ingredient::tableName().".*")
                    ->leftJoin(Ingredient::tableName(),IngredientGroupMapping::tableName().'.ingredient_id','=',Ingredient::tableName().'.ingredient_id');
                    IngredientLang::selectTranslation($ingredients);
                    $ingredients = $ingredients->where([
                        Ingredient::tableName().".ingredient_key" => $iValue->ingredient_key,
                        Ingredient::tableName().".status" => ITEM_ACTIVE,
                        'ingredient_group_mapping.ingredient_group_id' => $ingredientGroup->ingredient_group_id
                        ])->first();
                    if($ingredients === null) {
                        return ['status'=> false, 'error' => 'Invalid Ingredient key'];
                    }
                    $ingredientSubtotal = (int)$iValue->quantity * ( (float)$ingredients->price * $itemQuantity) ;
                    $sub_items_ingredient_groups[$igKey]['ingredient_name'] = $ingredients->ingredient_name;
                    $sub_items_ingredient_groups[$igKey]['arabic_ingredient_name'] = IngredientLang::where('ingredient_id',$ingredients->ingredient_id)->where('language_code','ar')->value('ingredient_name');
                    $sub_items_ingredient_groups[$igKey]['ingredients'][$iKey] = [
                        'ingredient_key' => $ingredients->ingredient_key,
                        'ingredient_id' => $ingredients->ingredient_id,
                        'price' => Common::currency($ingredients->price),
                        'cprice' => (float)$ingredients->price,
                        'quantity' => $iValue->quantity,
                        'ingredient_subtotal' => Common::currency($ingredientSubtotal),
                        'ingredient_csubtotal' => $ingredientSubtotal,
                    ];
                    $itemSubTotal += $ingredientSubtotal;
                    $ingredientGroupSubTotal += $ingredientSubtotal;
                }

                $sub_items_ingredient_groups[$igKey]['ingredient_group_subtotal'] = Common::currency($ingredientGroupSubTotal);
                $sub_items_ingredient_groups[$igKey]['ingredient_group_csubtotal'] = $ingredientGroupSubTotal;
                                
                $sub_items_ingredient_groups['subtotal'] = $itemSubTotal;                
            }
        }

        //print_r($sub_items_ingredient_groups);exit;

        return $sub_items_ingredient_groups;
    }

    public function getCart()
    {
        if(!auth()->check()){
            $this->commonError( __("apimsg.There is no items in your cart") );
            return $this->asJson();
        }        
        // if(request()->branch_key === null) {
        //     $this->commonError( __("apimsg.Branch key is empty") );
        //     return $this->asJson();
        // }

        $getcart_details = Cart::where('user_id',request()->user()->user_id)->where('deleted_at',NULL)->first();

        
        if(!$getcart_details) {
            $this->commonError( __("apimsg.There is no items in your cart") );
            return $this->asJson();
        }

        $branchDetails = Branch::where('branch_id',$getcart_details->branch_id)->first();
        

        if(!$branchDetails) {
            $this->commonError( __("apimsg.Brand not found") );
            return $this->asJson();
        }

        $branch_id = $branchDetails->branch_id;
        $userID = request()->user()->user_id;
        $cartDetails = Cart::where([
            'user_id' => $userID,
            'branch_id' => $branch_id
            ])->first();        
        if($cartDetails === null) {
            $this->commonError( __("apimsg.There is no items in your cart") );
            return $this->asJson();
        }        
        
        $cartItem = CartItem::where('cart_id',$cartDetails->cart_id)->get();
        if($cartItem === null) {
            $this->commonError( __("apimsg.There is no items in your cart") );
            return $this->asJson();
        }

        $itemArray['items'] = [];
        foreach($cartItem as $key => $value) {            
            $item = Item::find($value->item_id);
            if($item === null) {
                $getThisItem = CartItem::where(['cart_id' => $cartDetails->cart_id,'item_id' => $value->item_id])->first();
                if($getThisItem !== null) {
                    $getThisItem->delete();
                    
                }
                continue;
            }

            /** Get sub items details and price calculation while price on selection is 1 **/
            $price_on_selection_options = [];
            $cnt = 0;
            $sub_items_ingredient_group_total_price = 0;
            
            if( $value->price_on_selection == 1 && !empty( $value->price_on_selection_options ) ) {
                $price_on_selection_options = json_decode( $value->price_on_selection_options );
                
                $sub_items = [];
                $sub_items_total_price = 0;
                foreach( $price_on_selection_options as $key => $price_on_selection_option ) {
                    $itemSubTotal = $price_on_selection_option->sub_item_price * (int)$price_on_selection_option->quantity;
                    $price_on_selection_options[$cnt]->sub_item_subtotal = $itemSubTotal;

                    $sub_items[$key]['sub_item_id'] = $price_on_selection_option->sub_item_id;
                    $sub_items[$key]['sub_item_name'] = $price_on_selection_option->sub_item_name;
                    $sub_items[$key]['sub_item_price'] = $price_on_selection_option->sub_item_price;
                    $sub_items[$key]['quantity'] = $price_on_selection_option->quantity;    
                    $sub_items[$key]['sub_item_subtotal'] = $itemSubTotal;   

                    $sub_items[$key] = [
                        'ingredient_groups' => [],
                        'ingredient_name' => "",
                        'arabic_ingredient_name' => "",
                        'ingredient_group_subtotal' => 0,
                        'subtotal' => $itemSubTotal
                    ];

                    if(isset($price_on_selection_option->ingrdient_groups) && !empty($price_on_selection_option->ingrdient_groups)) {
                        foreach($price_on_selection_option->ingrdient_groups as $igKey => $igValue) {
                            $ingredientGroup = IngredientGroup::select(IngredientGroup::tableName().'.*');
                            IngredientGroupLang::selectTranslation($ingredientGroup);
                            $ingredientGroup = $ingredientGroup->where('ingredient_group_key',$igValue->ingredient_group_key)->first();
                            if($ingredientGroup === null) {                    
                                return ['status'=> false, 'error' => __('apimsg.Invalid Ingredient group key')];
                            }
                            $sub_items[$key]['ingredient_groups'][$igKey] = [
                                'ingredient_group_key' => $ingredientGroup->ingredient_group_key,
                                'ingredient_group_id' => $ingredientGroup->ingredient_group_id,
                                'ingredient_group_name' => $ingredientGroup->ingredient_group_name,
                                'arabic_ingredient_group_name' => IngredientGroupLang::where('ingredient_group_id',$ingredientGroup->ingredient_group_id)->where('language_code','ar')->value('ingredient_group_name'), 
                            ];                
                            $ingredientGroupSubTotal = 0;                
                            if(!isset($igValue->ingredients)) {
                                return ['status'=> false, 'error' => __('apimsg.Ingredients are missing')];
                            }
                            foreach($igValue->ingredients as $iKey => $iValue) {
                                $ingredients = IngredientGroupMapping::select(IngredientGroupMapping::tableName().".*",Ingredient::tableName().".*")
                                ->leftJoin(Ingredient::tableName(),IngredientGroupMapping::tableName().'.ingredient_id','=',Ingredient::tableName().'.ingredient_id');
                                IngredientLang::selectTranslation($ingredients);
                                $ingredients = $ingredients->where([
                                    Ingredient::tableName().".ingredient_key" => $iValue->ingredient_key,
                                    Ingredient::tableName().".status" => ITEM_ACTIVE,
                                    'ingredient_group_mapping.ingredient_group_id' => $ingredientGroup->ingredient_group_id
                                    ])->first();
                                if($ingredients === null) {
                                    return ['status'=> false, 'error' => 'Invalid Ingredient key'];
                                }
                                $ingredientSubtotal = (int)$iValue->quantity * ( (float)$ingredients->price * $price_on_selection_option->quantity) ;
                                $sub_items[$key]['ingredient_name'] = (isset($items[$key]['ingredient_name']) && $items[$key]['ingredient_name'] != '') ? $items[$key]['ingredient_name'].", ".$ingredients->ingredient_name : $ingredients->ingredient_name;
                                $sub_items[$key]['arabic_ingredient_name'] = (isset($items[$key]['arabic_ingredient_name']) && $items[$key]['arabic_ingredient_name'] != '') ? $items[$key]['arabic_ingredient_name']. "," .IngredientLang::where('ingredient_id',$ingredients->ingredient_id)->where('language_code','ar')->value('ingredient_name'):IngredientLang::where('ingredient_id',$ingredients->ingredient_id)->where('language_code','ar')->value('ingredient_name');
                                $sub_items[$key]['ingredient_groups'][$igKey]['ingredients'][$iKey] = [
                                    'ingredient_key' => $ingredients->ingredient_key,
                                    'ingredient_id' => $ingredients->ingredient_id,
                                    'price' => Common::currency($ingredients->price),
                                    'cprice' => (float)$ingredients->price,
                                    'quantity' => $iValue->quantity,
                                    'ingredient_subtotal' => Common::currency($ingredientSubtotal),
                                    'ingredient_csubtotal' => $ingredientSubtotal,
                                ];
                                $itemSubTotal += $ingredientSubtotal;
                                $ingredientGroupSubTotal += $ingredientSubtotal;
                            }

                            $sub_items[$key]['ingredient_groups'][$igKey]['ingredient_group_subtotal'] = Common::currency($ingredientGroupSubTotal);
                            $sub_items[$key]['ingredient_groups'][$igKey]['ingredient_group_csubtotal'] = $ingredientGroupSubTotal;
                                            
                            $sub_items[$key]['subtotal'] = $itemSubTotal;

                            $sub_items[$key]['ingredient_group_subtotal'] = $ingredientGroupSubTotal;
                        }
                    }
                    //print_r($sub_items);exit;

                    $price_on_selection_options[$cnt]->ingrdient_groups = $sub_items[$cnt]['ingredient_groups'];
                    $price_on_selection_options[$cnt]->ingredient_group_subtotal = $sub_items[$cnt]['ingredient_group_subtotal'];
                    $price_on_selection_options[$cnt]->subtotal = $sub_items[$cnt]['subtotal'];
                    $sub_items_ingredient_group_total_price += $sub_items[$cnt]['ingredient_group_subtotal'];
                    $sub_items_total_price += $sub_items[$cnt]['subtotal'];

                    $cnt++;
                }
            }
            //echo $sub_items_total_price;exit;

            $itemArray['items'][] = [
                'cart_item_key' => $value->cart_item_key,
                'item_key' => $item->item_key,
                'quantity' => $value->quantity,
                'ingrdient_groups' =>  json_decode($value->ingredients,true),
                'price_on_selection' => $value->price_on_selection,
                'sub_items' => $price_on_selection_options,
                'sub_items_ingredient_group_total_price' => ( $value->price_on_selection == 1 && !empty( $value->price_on_selection_options ) ) ? $sub_items_ingredient_group_total_price : 0,
                'sub_items_total_price' => ( $value->price_on_selection == 1 && !empty( $value->price_on_selection_options ) ) ? $sub_items_total_price : 0
            ];
        }     
        

        $items = (new OrderController())->itemCheckoutItemData($itemArray);
        

        if($items['status'] === false) {
            $this->commonError($items['error']);
            return $this->asJson();
        }
        

        $cart = (new OrderController())->dataFormat(['items' => $items['data']]); 
        //print_r($cart);exit;
        if( count($cart['items']) <= 0) {
            $this->commonError( __("apimsg.There is no items in your cart") );
        } else {

            

            $branchDetails = Branch::select([
                Vendor::tableName().'.*',
                Branch::tableName().'.*',
            ])
                ->leftJoin(Vendor::tableName(),Branch::tableName().".vendor_id",'=',Vendor::tableName().'.vendor_id')
                ->where([
                    Branch::tableName().'.status' => ITEM_ACTIVE,
                    Vendor::tableName().'.status' => ITEM_ACTIVE,
                    Branch::tableName().'.branch_id' => $cartDetails->branch_id,
                ])->first();
            if($branchDetails === null) {            
                $this->commonError( __("apimsg.Branch Not Found") );
            }
                
            $cart['vendor_details'] = [];
            $cart['payment_details'] = [];
            $itemSubtotal = 0;
            foreach($cart['items'] as $key => $value) {
                if( isset( $value['price_on_selection'] ) && $value['price_on_selection'] == 1 ) {
                    //print_r($value['sub_items_total_price']);exit;
                    $itemSubtotal += $value['sub_items_total_price'];
                    $cart['items'][$key]['price_on_selection_subtotal'] = Common::currency($value['sub_items_total_price']);
                }
                else {
                    $itemSubtotal += $value['subtotal'];
                    $cart['items'][$key]['subtotal'] = Common::currency($value['subtotal']);
                }
            }
            /* vendor details */
			$branch_cuisine = CuisineLang::whereIn('cuisine_id',BranchCuisine::where('branch_id',$branch_id)->pluck('cuisine_id')->toarray())->where('language_code','en')->get()->pluck('cuisine_name');
			//$branch_cuisine = (array)$branch_cuisine;
			//$tmp = explode(",", $en['mm_wmeet']);
			if( count($branch_cuisine) > 1 ) {
				for ($i = 0; $i < count($branch_cuisine); $i++) {
					$branch_cuisine[$i] = $branch_cuisine[$i] . " ";
				}
			}
			//print_r($branch_cuisine);exit;

            $vendorlangDetails = VendorLang::where('vendor_id',$branchDetails->vendor_id)->first();
            $branch_name = BranchLang::where('branch_id',$branch_id)->value('branch_name');
            $arabic_branch_name = BranchLang::where('branch_id',$branch_id)->where('language_code','ar')->value('branch_name');
            
            $vendorDetails = [
                'vendor_id' => $branchDetails->vendor_id,
                'vendor_key' => $branchDetails->vendor_key,
                'vendor_name' => $vendorlangDetails->vendor_name,
                'arabic_vendor_name' => VendorLang::where('vendor_id',$branchDetails->vendor_id)->where('language_code','ar')->value('vendor_name'),
                'vendor_logo' => FileHelper::loadImage($vendorlangDetails->vendor_logo),
                //'branch_cuisine' => CuisineLang::whereIn('cuisine_id',BranchCuisine::where('branch_id',$branch_id)->pluck('cuisine_id')->toarray())->where('language_code','en')->get()->pluck('cuisine_name'),
				'branch_cuisine' => $branch_cuisine,
                'arabic_branch_cuisine' => CuisineLang::whereIn('cuisine_id',BranchCuisine::where('branch_id',$branch_id)->pluck('cuisine_id')->toarray())->where('language_code','ar')->get()->pluck('cuisine_name'),
                'branch_key' => $branchDetails->branch_key,
                'branch_name' => $branch_name,
                'arabic_branch_name' => $arabic_branch_name,
                'min_order_value' => $branchDetails->min_order_value,
            
            ];
			//print_r($vendorDetails);exit;
            array_push($cart['vendor_details'], $vendorDetails);

            /** Sub Total */
            $vatDetails = [
                //'name' => 'Sub Total', 
				'name' => __('apimsg.Item Total'),
                'price' => Common::currency($itemSubtotal),
                //'color_code' => PAYMENT_VAR_TAX_COLOR,
				'color_code' => PAYMENT_SUB_TOTOAL_COLOR,
				'text_size' => PAYMENT_TEXT_SIZE,
				'is_semi_bold' => SEMI_BOLD,
                'is_bold' => SUB_TOTAL_BOLD,
                'is_italic' => IS_ITALIC,
                'is_line' => IS_LINE, 
            ];
            array_push($cart['payment_details'], $vatDetails);

            /** Vat tax amount */
            $vatAmount = ($itemSubtotal * $branchDetails->tax) / 100;
            $vatDetails = [
                'name' => 'VAT ('.$branchDetails->tax.'%)', 
                'price' => Common::currency($vatAmount),
                'percent' => $branchDetails->tax,
                'color_code' => PAYMENT_VAR_TAX_COLOR,
                'is_bold' => IS_BOLD,
                'is_italic' => IS_ITALIC,
                'is_line' => IS_LINE, 
            ];
            /** Push vat details in payment details if its > 0 only **/
            if( $branchDetails->tax > 0 )
                array_push($cart['payment_details'], $vatDetails);            

            /** Service tax amount */
            $serviceTaxAmount = 0;
            if($branchDetails->service_tax !== null && $branchDetails->service_tax > 0) {
                
               $serviceTaxAmount = ($itemSubtotal * $branchDetails->service_tax) / 100;
                $serviceTaxDetails = [
                    'name' => 'Service Tax ('.$branchDetails->service_tax.'%)', 
                    'price' => Common::currency($serviceTaxAmount), 
                    'cprice' => $serviceTaxAmount, 
                    'percent' => $branchDetails->service_tax,
                    'color_code' => PAYMENT_SERVICE_TAX_COLOR,
                    'is_bold' => IS_BOLD,
                    'is_italic' => IS_ITALIC,
                    'is_line' => IS_LINE,
                ];
                array_push($cart['payment_details'], $serviceTaxDetails);
            }

            /** Total Cost */
            $totalCheckouAmount = $itemSubtotal +$vatAmount + $serviceTaxAmount;
            $cart['total'] = [
                'cprice' => number_format($totalCheckouAmount,3),
                'price' => Common::currency($totalCheckouAmount),
                //'name' => 'Total',
				'name' => __('apimsg.To Pay'),
                'cart_total' => count($cart['items']),
                'color_code' => PAYMENT_GRAND_TOTOAL_COLOR,
				'text_size' =>PAYMENT_GRAND_TOTAL_TEXT_SIZE,
				'is_semi_bold' => SEMI_BOLD,
                'is_bold' => IS_BOLD,
                'is_italic' => IS_ITALIC,
                'is_line' => IS_LINE,
            ];  
			
			$cart['no_contact_delivery'] = 1;
			$cart['delivery_time'] = "30 Mins";
			$cart['cost_for_2'] = Common::currency($totalCheckouAmount * 2);

            $this->setMessage( __("apimsg.The cart items are fetched") );
            $this->setData($cart);
        } 
        return $this->asJson();
    }
    
    public function clearCart()
    {
        $userID = request()->user()->user_id;
        $branch = Branch::findByKey(request()->branch_key);
        if($branch === null) {
            return $this->commonError( __("apimsg.Branch not found") );
        }
        $cart = Cart::where(['user_id' => $userID,'branch_id' => $branch->branch_id])->first();
        if($cart !== null) {
            $cart->delete();
        }
        $this->setMessage( __("apimsg.Your cart is cleared") );
        return $this->asJson();
    }
}
