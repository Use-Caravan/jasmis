<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Frontend\Controller;
use App\Http\Controllers\Api\V1\CartController as APICartController;
use App\{    
    Branch,
    CorporateVoucher,
    CorporateVoucherItem,
    Order,
    OrderItem,
    OrderItemLang,
    Item,
    OrderItemIngredientGroup,
    IngredientGroup,
    OrderIngredient,
    Ingredient,
    Cart,
    UserCorporate,
    Vendor,
    VendorLang,
    CorporateVoucherFile
};
use App;
use Common;
use DB;
use Validator;
use App\Exports\VoucherExport;
use \Mpdf\Mpdf;
use App\Helpers\FileHelper;
use File;

class CorporateVoucherController extends Controller
{    
    
    public function applyVoucher(Request $request) 
    {        
        $response = ['status' => AJAX_FAIL, 'message' => '', 'redirect_url' => '', 'data' => []];
        if($request->voucher_code === null) {
            $response['message'] = 'Voucher should not be empty';
            goto response;
        }
        $cartItem = [];

        $corporateVoucher = CorporateVoucher::select([
            CorporateVoucher::tableName().".corporate_voucher_key",
            CorporateVoucher::tableName().".voucher_number",
            CorporateVoucher::tableName().".user_corporate_id",
            CorporateVoucher::tableName().".order_id",
            CorporateVoucherItem::tableName().".order_item_id",
            CorporateVoucherItem::tableName().".is_claimed",
            CorporateVoucherItem::tableName().".claimed_at",
            CorporateVoucherItem::tableName().".quantity",
        ])
        ->leftJoin(CorporateVoucherItem::tableName(), CorporateVoucher::tableName().".corporate_voucher_id", "=", CorporateVoucherItem::tableName().".corporate_voucher_id")
        ->where('voucher_number',$request->voucher_code)
        ->first();
        if($corporateVoucher === null) {
            $response['message'] = 'Invalid voucher code you have entered';
            goto response;
        }
        if($corporateVoucher->is_claimed === 1) {
            $response['message'] = 'This voucher already claimed at '.$corporateVoucher->claimed_at;
            goto response;
        }

        $orderTable = Order::find($corporateVoucher->order_id);
        if($orderTable === null) {
            $response['message'] = 'Order not found';
            goto response;
        }

        $branch = Branch::find($orderTable->branch_id);
        if($branch === null) {
            $response['message'] = 'Branch not found';
            goto response;
        }
        $cartItem['branch_key'] = $branch->branch_key;

        $orderItem = Item::select([
            Item::tableName().".item_key",
        ])
        ->leftJoin(OrderItem::tableName(), OrderItem::tableName().".item_id", "=", Item::tableName().".item_id")
        ->where([OrderItem::tableName().".order_item_id" => $corporateVoucher->order_item_id])
        ->first();

        if($orderItem === null) {
            $response['message'] = 'This item is not found right now';
            goto response;
        }       
        $cartItem['item_key'] = $orderItem->item_key;
        $cartItem['quantity'] = $corporateVoucher->quantity;
        $cartItem['ingrdient_groups'] = [];
        $ingredientGroup = OrderItemIngredientGroup::where(['order_item_id' => $corporateVoucher->order_item_id])->get();
        foreach($ingredientGroup as $key => $group) {
            $ingredientGroup = IngredientGroup::find($group->ingredient_group_id);
            if($ingredientGroup === null) {
                continue;
            }
            $ingredientGroups = [
                'ingredient_group_key' => $ingredientGroup->ingredient_group_key,
                'ingredients' => []
            ];
            $orderIngredient = OrderIngredient::where(['order_item_id' => $corporateVoucher->order_item_id,'order_item_ingredient_group_id' => $group->order_item_ingredient_group_id])->get();
            
            foreach($orderIngredient as $Ikey => $ingredient) {
                
                $ingredient = Ingredient::find($ingredient->ingredient_id);
                if($ingredient === null) {
                    continue;
                }                
                $ingredientGroups['ingredients'][] = [
                    "ingredient_key" => $ingredient->ingredient_key,
                    "quantity" => 1
                ];
            }
            array_push($cartItem['ingrdient_groups'],$ingredientGroups);
        }               
        
        $userID = request()->user()->user_id;
        if($branch === null) {            
            $response['message'] = 'This item is not found right now';
            goto response;
        }
        $cart = Cart::where(['user_id' => $userID,'branch_id' => $orderTable->branch_id])->first();
        if($cart !== null) {
            $cart->delete();
        }
        
        $cartResponse = Common::compressData( (new APICartController)->userCart($cartItem) );
        
        $message = $cartResponse->message;
        
        if($cartResponse->status !== HTTP_SUCCESS) {            
            $response['message'] = 'Something went wrong in add to cart';
            goto response;
        }

        $request->session()->put('corporate_voucher','enabled');
        $request->session()->put('corporate_voucher_code',$request->voucher_code);
        $response['status'] = AJAX_SUCCESS;
        $response['data'] = $cartItem;
        $response['redirect_url'] = route('frontend.checkout',['branch_slug' => $branch->branch_slug]);
        response:
        return response()->json($response);
    }

    public function downloadCorporateVoucher(Request $request)
    {
        $order_key = $request->order_key;
        $order = Order::findByKey($order_key);
        $voucherFile = CorporateVoucherFile::where(['order_id' => $order->order_id,'user_corporate_id' => $order->user_corporate_id])->first();        
        if($voucherFile === null) {
            $userCorporate = UserCorporate::find($order->user_corporate_id);
            $vendor = Vendor::where([Vendor::tableName().'.vendor_id' => $order->vendor_id]);
            VendorLang::selectTranslation($vendor);
            $vendor = $vendor->first();
            $corporateVoucher = CorporateVoucher::where(['order_id' => $order->order_id])->get();            
            $vouchersList = [
                'company_logo' => FileHelper::loadImage(config('webconfig.app_logo')),
                'corporate_logo' => FileHelper::loadImage($userCorporate->company_logo),
                'vendor_logo' => FileHelper::loadImage($vendor->vendor_logo),
                'valid_upto' => date('Y-m-d'),
                'vouchers' => [],
            ];
            foreach($corporateVoucher as $key => $voucher) {
                $voucherNumber = Common::generateVoucherNumber();
                $voucher->voucher_number = $voucherNumber;            
                $voucherItems = CorporateVoucherItem::where(['corporate_voucher_id' => $voucher->corporate_voucher_id])->get();
                foreach($voucherItems as $vKey => $items) {

                    $orderItem = OrderItem::where([OrderItem::tableName().'.order_item_id' => $items->order_item_id]);
                    
                    OrderItemLang::selectTranslation($orderItem,'OIL');
                    $orderItem = $orderItem->first();
                    $vouchersList['vouchers'][] = [
                        'voucher_code' => $voucherNumber,
                        'item_name' => $orderItem->item_name,
                        'item_image' => FileHelper::loadImage($orderItem->item_image_path),
                        'valid_upto' => date("Y-m-d",strtotime($userCorporate->valid_upto)),
                    ];
                }
                $voucher->save();
            }       
            $mpdf = new \Mpdf\Mpdf();
            $stylesheet = file_get_contents( url('resources/assets/export-media-assets/print.css') );
            $html = view('exports.corporate-voucher',compact('vouchersList'))->render();
            $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
            File::makeDirectory("storage/app/".CORPORATE_VOUCHER_FILES, $mode = 0777, true, true);
            $mpdf->Output("storage/app/".CORPORATE_VOUCHER_FILES."/$order_key.pdf", \Mpdf\Output\Destination::FILE);
            $model = new CorporateVoucherFile();
            $filePath = "storage/app/".CORPORATE_VOUCHER_FILES."/$order_key.pdf";
            $model = $model->fill([
                'order_id' => $order->order_id,
                'user_corporate_id' => $order->user_corporate_id,
                'file_path' => "storage/app/".CORPORATE_VOUCHER_FILES."/$order_key.pdf"
            ]);
            $model->save();
        } else {
            $filePath = $voucherFile->file_path;
        }
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($filePath, "corporate-vouchers-$order_key.pdf", $headers);
    }
}
