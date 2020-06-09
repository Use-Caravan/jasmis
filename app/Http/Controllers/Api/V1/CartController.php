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
    Api\BranchCuisine
};
use App;
use Validator;
use Common;
use DB;
use FileHelper;


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
                                
                foreach($getThisItem as $key => $value) {
                    if($value->is_ingredient == 0) {                        
                        $catItemID = $value->cart_item_id;
                        goto cartItemUpdate;
                    }
                    $existsIngredients = json_decode($value->ingredients,true);
                    $currentIngredients = $rawData['ingrdient_groups'];
                    if($existsIngredients == $currentIngredients) {
                        $catItemID = $value->cart_item_id;
                    
                        goto cartItemUpdate;                                                
                    }
                }                
                goto cartItemAdd;
            }
            
            cartItemAdd:
            
                if($rawData['quantity'] == 0) {
                    goto cartDelete;
                }
                
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
                
                goto response;

            cartItemUpdate:
            
                if($rawData['quantity'] == 0) {
                    goto cartDelete;
                }     
                                
                $cartItem = new CartItem();
                $cartItem = $cartItem->find($catItemID);
                
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
        if(request()->quantity == 0)
        {
            $updateCartItem = CartItem::where(['cart_item_key' => request()->cart_item_key])->delete();
        } else {
            $removeCartItem->quantity = request()->quantity;
            $removeCartItem->item_instruction = request()->item_instruction;
            $removeCartItem->save();
        } 
        $this->setMessage( __("apimsg.Item have been updated") );
        return $this->asJson();
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
            $itemArray['items'][] = [
                'cart_item_key' => $value->cart_item_key,
                'item_key' => $item->item_key,
                'quantity' => $value->quantity,
                'ingrdient_groups' =>  json_decode($value->ingredients,true),
            ];
        }     
        

        $items = (new OrderController())->itemCheckoutItemData($itemArray);
        

        if($items['status'] === false) {
            $this->commonError($items['error']);
            return $this->asJson();
        }
        

        $cart = (new OrderController())->dataFormat(['items' => $items['data']]); 
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
                $itemSubtotal += $value['subtotal'];
                $cart['items'][$key]['subtotal'] = Common::currency($value['subtotal']);

            }
            /* vendor details */

            $vendorlangDetails = VendorLang::where('vendor_id',$branchDetails->vendor_id)->first();
            $branch_name = BranchLang::where('branch_id',$branch_id)->value('branch_name');
            
            
            $vendorDetails = [
                'vendor_id' => $branchDetails->vendor_id,
                'vendor_key' => $branchDetails->vendor_key,
                'vendor_name' => $vendorlangDetails->vendor_name,
                'vendor_logo' => FileHelper::loadImage($vendorlangDetails->vendor_logo),
                'branch_cuisine' => CuisineLang::whereIn('cuisine_id',BranchCuisine::where('branch_id',$branch_id)->pluck('cuisine_id')->toarray())->where('language_code','en')->get()->pluck('cuisine_name'),
                'branch_key' => $branchDetails->branch_key,
                'branch_name' => $branch_name,
                'min_order_value' => $branchDetails->min_order_value,
            
            ];
            array_push($cart['vendor_details'], $vendorDetails);

            /** Sub Total */
            $vatDetails = [
                'name' => 'Sub Total', 
                'price' => Common::currency($itemSubtotal),
                'color_code' => PAYMENT_VAR_TAX_COLOR,
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
                'cprice' => $totalCheckouAmount,
                'price' => Common::currency($totalCheckouAmount),
                'name' => 'Total',
                'cart_total' => count($cart['items']),
                'color_code' => PAYMENT_GRAND_TOTOAL_COLOR,
                'is_bold' => IS_BOLD,
                'is_italic' => IS_ITALIC,
                'is_line' => IS_LINE,
            ];  

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
