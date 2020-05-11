<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\CModel;
use App\Scopes\VendorScope;
use App\Scopes\BranchScope;
use Common;
use DB;
use App;
use Auth;


class Order extends CModel
{    
    /**
     * Enable the softdelte 
     *
     * @var class
     */
    use SoftDeletes;


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'order';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'order_id';
       
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'order_key';
    
    /**
     * The attributes that enable unique key generation.
     *
     * @var string
     */
    protected $keyGenerate = true;


    /**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['csrf-token']; 


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VendorScope());
        static::addGlobalScope(new BranchScope());
    }



     /**
	 * Get Unique key to generate key
	 * @return string
	*/
    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    }  


     /**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{  
		return self::where(self::uniqueKey(),$key)->first();
    }

     /**	 
	 *
	 * @var query
	 */
	public static function getList()
	{
        $self = new self();
        $query = self::select($self->getTable().'.*');
        return $query;
	}

    public static function getOrders($corporateOrder = null)
    {   
        $orders = self::getList()
                ->addSelect(User::tableName().'.first_name',User::tableName().'.last_name',User::tableName().'.username',User::tableName().'.email')
                ->leftjoin(User::tableName(),self::tableName().'.user_id',User::tableName().'.user_id')
                ->leftjoin(Branch::tableName(),self::tableName().'.branch_id',Branch::tableName().'.branch_id')
                ->leftjoin(Vendor::tableName(),self::tableName().'.vendor_id',Vendor::tableName().'.vendor_id')
                ->leftjoin(UserAddress::tableName(),self::tableName().'.user_address_id',UserAddress::tableName().'.user_address_id');
                 if($corporateOrder === null) {
                    $orders = $orders->where(function($orders) {
                        $orders->where([
                            Order::tableName().'.payment_status' => ORDER_PAYMENT_STATUS_SUCCESS,
                            Order::tableName().'.order_booked_by' => USER_TYPE_CUSTOMER,
                            User::tableName().'.user_type' => USER_TYPE_CORPORATES,
                            [Order::tableName().'.payment_type', '<>', PAYMENT_OPTION_COD]
                        ])->orWhere(Order::tableName().'.payment_type', PAYMENT_OPTION_COD)
                          ->orWhere(Order::tableName().'.payment_type', PAYMENT_OPTION_ONLINE)
                          ->orWhere(Order::tableName().'.payment_type', PAYMENT_OPTION_WALLET);
                    }); 
                 }
                BranchLang::selectTranslation($orders);
                VendorLang::selectTranslation($orders);
                if($corporateOrder !== null) {
                    $orders = $orders->addSelect(UserCorporate::tableName().'.corporate_name')
                    ->leftjoin(UserCorporate::tableName(),self::tableName().'.order_id',UserCorporate::tableName().'.order_id')
                    ->where([
                        Order::tableName().'.order_booked_by' => USER_TYPE_CORPORATES,
                    ]);
                }
        return $orders;
                   
    } 

    public static function getReports()
    {      
        $report = self::getList()
                   ->addSelect(User::tableName().'.first_name',User::tableName().'.last_name',User::tableName().'.username',User::tableName().'.email')
                   ->leftjoin(User::tableName(),self::tableName().'.user_id',User::tableName().'.user_id')
                   ->leftjoin(Branch::tableName(),self::tableName().'.branch_id',Branch::tableName().'.branch_id')
                   ->leftjoin(Vendor::tableName(),self::tableName().'.vendor_id',Vendor::tableName().'.vendor_id')
                   ->leftjoin(UserAddress::tableName(),self::tableName().'.user_address_id',UserAddress::tableName().'.user_address_id');
                   BranchLang::selectTranslation($report);
                   VendorLang::selectTranslation($report);
                    if(request()->order_number !== null) {
                        $report = $report->where([Order::tableName().'.order_number' => request()->order_number]);   
                    }
                   if(request()->vendor_id !== null) {
                        $report = $report->where([Order::tableName().'.vendor_id' => request()->vendor_id]);   
                    }
                    if(request()->branch_id !== null) {
                        $report = $report->where([Order::tableName().'.branch_id' => request()->branch_id]);
                    } 
                    if(request()->from_date !== null && request()->to_date !== null) {  
                        $fromDate = date('Y-m-d H:i:s', strtotime(request()->from_date));                        
                        $toDate = date('Y-m-d H:i:s', strtotime(request()->to_date));
                        $report = $report->whereDate(Order::tableName().'.order_datetime','>=',$fromDate);
                        $report = $report->whereDate(Order::tableName().'.order_datetime','<=',$toDate);
                    }
                    if(request()->order_status !== null) {
                        $report = $report->where([Order::tableName().'.order_status' => request()->order_status]);
                    }                             
        return $report;
                   
    } 

    public static function vendorPaymentDetails()
    {
        $vendorPayment = self::getList() 
                    ->leftjoin(Vendor::tableName(),self::tableName().'.vendor_id',Vendor::tableName().'.vendor_id')
                    ->leftjoin(Branch::tablename(),self::tableName().'.branch_id',Branch::tableName().'.branch_id');
                    VendorLang::selectTranslation($vendorPayment);
                    BranchLang::selectTranslation($vendorPayment);
                    if(request()->vendor_id !== null) {
                        $vendorPayment = $vendorPayment->where([Order::tableName().'.vendor_id' => request()->vendor_id]);   
                    }
                    if(request()->branch_id !== null) {
                        $vendorPayment = $vendorPayment->where([Order::tableName().'.branch_id' => request()->branch_id]);
                    }
                    if(request()->order_datetime !== null) {
                        $dates = explode('/',request()->order_datetime);
                        $fromDate = trim($dates[0]," ");
                        $toDate = trim($dates[1]," ");
                        $vendorPayment->whereBetween(self::tableName().'.order_datetime',[$fromDate,$toDate]);
                    } else {
                        $dates = Vendor::vendorPaymentTimeslot();
                        $value = key($dates);
                        $dates = explode('/',$value);
                        $fromDate = trim($dates[0]," ");
                        $toDate = trim($dates[1]," ");
                        $vendorPayment->whereBetween(self::tableName().'.order_datetime',[$fromDate,$toDate]);
                    }
        $vendorPayment = $vendorPayment->get();
        return $vendorPayment;
    }

    public static function getPaymentFilter($vendorId,$branchId,$fromDate,$toDate)
    {   
        $paymentFilter = self::getList()
                                  ->leftjoin(Vendor::tableName(),self::tableName().'.vendor_id',Vendor::tableName().'.vendor_id')
                                  ->leftjoin(Branch::tablename(),self::tableName().'.branch_id',Branch::tableName().'.branch_id');
                                  VendorLang::selectTranslation($paymentFilter);
                                  BranchLang::selectTranslation($paymentFilter);
                                  if($vendorId !== null) {
                                      $paymentFilter->where([Order::tableName().'.vendor_id' => $vendorId]);
                                  }
                                  if($vendorId !== null) {
                                      $paymentFilter->where([Order::tableName().'.branch_id' => $branchId]);
                                  }
                                  $paymentFilter->whereBetween(self::tableName().'.order_datetime',[$fromDate,$toDate]);
                                  $paymentFilter = $paymentFilter->get();
        return $paymentFilter;
    }

    public static function getOrderDetails($orderKey)
    {  
         /* $order = self::getList()
                ->addselect([self::tableName().'.*', 
                DB::raw("group_concat(DISTINCT ( ".OrderItem::tableName().".order_item_id) ) as order_item_id"),
                DB::raw("(SELECT group_concat(item_name) FROM order_item_lang AS OITL LEFT JOIN order_item AS OIT ON OIT.order_item_id = OITL.order_item_id WHERE order.order_id = OIT.order_id and language_code = '".App::getLocale()."') as item_names"),
                DB::raw("group_concat(DISTINCT ( ".OrderIngredient::tableName().".order_ingredient_id) ) as order_ingredient_id"),
                DB::raw("(SELECT group_concat(ingredient_name) FROM order_ingredient_lang AS OIL LEFT JOIN order_ingredient AS OI ON OI.order_ingredient_id = OIL.order_ingredient_id WHERE order.order_id = OI.order_id and language_code = '".App::getLocale()."') as ingredient_names"),
                DB::raw("group_concat(DISTINCT ( ".OrderItemIngredientGroup::tableName().".order_item_ingredient_group_id) ) as order_item_ingredient_group_id"),
                DB::raw("(SELECT group_concat(group_name) FROM order_item_ingredient_group_lang AS OIGL LEFT JOIN order_item_ingredient_group AS OIG ON OIG.order_item_ingredient_group_id = OIGL.order_item_ingredient_group_id WHERE order.order_id = OIG.order_id and language_code = '".App::getLocale()."') as ingredient_group_names"),
                ])
                ->leftjoin(OrderItem::tableName(),self::tableName().'.order_id',OrderItem::tableName().'.order_id')
                ->leftjoin(Item::tableName(),OrderItem::tableName().'.item_id',Item::tableName().'.item_id')
                ->leftjoin(OrderIngredient::tableName(),self::tableName().'.order_id',OrderIngredient::tableName().'.order_id')
                ->leftjoin(OrderItemIngredientGroup::tableName(),self::tableName().'.order_id',OrderItemIngredientGroup::tableName().'.order_id');
                OrderItemLang::selectTranslation($order,'ITL');
                OrderIngredientLang::selectTranslation($order,'OIL');
                OrderItemIngredientGroupLang::selectTranslation($order,'OIGRL'); 
                $order = $order->where([self::tableName().'.order_key' => $orderKey])->first();  */

                //$order = Order::findByKey($orderKey)                
                 $order = self::getList()
                    ->addSelect(User::tableName().'.first_name',User::tableName().'.last_name',User::tableName().'.username',Voucher::tableName().'.promo_code')
                    ->leftjoin(Vendor::tableName(),self::tableName().'.vendor_id',Vendor::tableName().'.vendor_id')
                    ->leftjoin(Branch::tableName(),self::tableName().'.branch_id',Branch::tableName().'.branch_id')
                    ->leftjoin(User::tableName(),self::tableName().'.user_id',User::tableName().'.user_id')
                    ->leftjoin(Voucher::tableName(),self::tableName().'.voucher_id',Voucher::tableName().'.voucher_id');
                    VendorLang::selectTranslation($order);
                    BranchLang::selectTranslation($order);
                    $order->where([Order::tableName().'.order_key' => $orderKey]);
                    $order=$order->first();              
                $items = OrderItem::addSelect([OrderItem::tableName().'.order_id',OrderItem::tableName().'.order_item_id'])->where([OrderItem::tableName().'.order_id' => $order->order_id]);
                OrderItemLang::selectTranslation($items,'OIL');
                $order->items = $items->get();
                    foreach($order->items as $key => $orderIngredient) {
                        $ingredients = OrderIngredient::where(OrderIngredient::tableName().'.order_item_id',$orderIngredient->order_item_id);
                        OrderIngredientLang::selectTranslation($ingredients);
                        $orderIngredient->ingredients =$ingredients->get();
                        foreach($orderIngredient->ingredients as $key => $ingredientGroup) {
                            $ingredientGroup = OrderItemIngredientGroup::where(OrderItemIngredientGroup::tableName().'.order_item_id',$ingredientGroup->order_item_id);
                            OrderItemIngredientGroupLang::selectTranslation($ingredientGroup);
                            $ingredientGroup->ingredientGroups =$ingredientGroup->get();
                        } 
                    }
                    if($order->order_booked_by === USER_TYPE_CORPORATES) {
                        $order = self::getList()
                        ->addSelect([User::tableName().'.first_name',User::tableName().'.last_name',User::tableName().'.username'])
                        ->leftjoin(Vendor::tableName(),self::tableName().'.vendor_id',Vendor::tableName().'.vendor_id')
                        ->leftjoin(Branch::tableName(),self::tableName().'.branch_id',Branch::tableName().'.branch_id')
                        ->leftjoin(User::tableName(),self::tableName().'.user_id',User::tableName().'.user_id');
                        VendorLang::selectTranslation($order);
                        BranchLang::selectTranslation($order);
                        $order = $order->where([Order::tableName().'.order_key' => $orderKey])->first();
                        $items = Order::addSelect([CorporateVoucher::tableName().'.voucher_number',OrderItem::tableName().'.order_id',OrderItem::tableName().'.order_item_id',OrderItem::tableName().'.base_price'])
                            ->leftjoin(CorporateVoucher::tableName(),Order::tableName().'.order_id',CorporateVoucher::tableName().'.order_id')
                            ->leftjoin(CorporateVoucherItem::tableName(),CorporateVoucher::tableName().'.corporate_voucher_id',CorporateVoucherItem::tableName().'.corporate_voucher_id')
                            ->leftjoin(OrderItem::tableName(),CorporateVoucherItem::tableName().'.order_item_id',OrderItem::tableName().'.order_item_id')
                            ->where([OrderItem::tableName().'.order_id' => $order->order_id]);
                            OrderItemLang::selectTranslation($items,'OIL');
                            $order->items = $items->get();
                            foreach($order->items as $key => $orderIngredient) {
                                $ingredients = OrderIngredient::where(OrderIngredient::tableName().'.order_item_id',$orderIngredient->order_item_id);
                                OrderIngredientLang::selectTranslation($ingredients);
                                $orderIngredient->ingredients =$ingredients->get();
                                foreach($orderIngredient->ingredients as $key => $ingredientGroup) {
                                    $ingredientGroup = OrderItemIngredientGroup::where(OrderItemIngredientGroup::tableName().'.order_item_id',$ingredientGroup->order_item_id);
                                    OrderItemIngredientGroupLang::selectTranslation($ingredientGroup);
                                    $ingredientGroup->ingredientGroups =$ingredientGroup->get();
                                } 
                            }
                    }
        return $order;         
    }           

   
    public static function approvedStatus($approvedStatus = null, $required = 'label')
    {        
        $options = [
            ORDER_APPROVED_STATUS_PENDING      => 
             [
                'label' => __('admincrud.Pending'),
                'color' => ORDER_PENDING_COLOR
             ],
            ORDER_APPROVED_STATUS_APPROVED  => 
            [
                'label' => __('admincrud.Approved'),
                'color' => ORDER_ACCEPTED_COLOR
            ],
            ORDER_APPROVED_STATUS_REJECTED     =>
            [
                'label' => __('admincrud.Rejected'),
                'color' => ORDER_REJECTED_COLOR
            ],
            ORDER_APPROVED_STATUS_PREPARING => 
            [
                'label' => __('admincrud.Preparing'),
                'color' => ORDER_PREPARING_COLOR
            ],
            ORDER_APPROVED_STATUS_DRIVER_ACCEPTED => 
            [
                'label' => __('admincrud.Driver Accepted'),
                'color' => ORDER_ACCEPTED_COLOR
            ],
            ORDER_APPROVED_STATUS_READY_FOR_PICKUP => 
            [
                'label' => __('admincrud.Ready For Pickup'),
                'color' => ORDER_READY_FOR_PICKUP_COLOR
            ],
           /*  ORDER_APPROVED_STATUS_DRIVER_PICKED_UP => 
            [
                'label' => __('admincrud.Driver Picked Up'),
                'color' => ORDER_DRIVER_PICKUP_UP_COLOR
            ], */
            ORDER_APPROVED_STATUS_DELIVERED =>
            [   
                'label' => __('admincrud.Delivered'),
                'color' => ORDER_DELIVERED_COLOR
            ],
          /*    ORDER_APPROVED_STATUS_COMPLETED =>
            [
                'label' => __('admincrud.Completed'),
                'color' => ORDER_COMPLETED_COLOR
            ],  */
            ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER => [
                'label' => __('admincrud.Assigned to Driver'),
                'color' => ORDER_COMPLETED_COLOR
            ],
             ORDER_ONTHEWAY => [
                'label' => __('admincrud.Order On the way'),
                'color' => ORDER_COMPLETED_COLOR
            ],
           /*   ORDER_DRIVER_DELIVERED => [
                'label' => __('admincrud.Driver Delivered'),
                'color' => ORDER_COMPLETED_COLOR
            ], */ 
            ORDER_DRIVER_REQUESTED => [
                'label' => __('admincrud.Driver Requested'),
                'color' => ORDER_COMPLETED_COLOR
            ],
            ORDER_DRIVER_REJECTED => [
                'label' => __('admincrud.Driver Rejected'),
                'color' => ORDER_COMPLETED_COLOR
            ],        
        ];        


        if($approvedStatus !== null && isset($options[$approvedStatus][$required])) {
            return $options[$approvedStatus][$required];  
        } else {
            $status = [];
            foreach($options as $key => $value) {
                $status[$key] = $value['label'];
            }            
            return $status;
        };
    }

    public function orderStatus($orderStatus = null,$orderType = null) 
    {   
        $statusList = [
            $orderStatus  =>  self::approvedStatus($orderStatus),            
        ];
        
        if($orderType == ORDER_TYPE_PICKUP_DINEIN && $orderStatus == ORDER_APPROVED_STATUS_APPROVED) {
            $statusList[ORDER_APPROVED_STATUS_PREPARING]  =  self::approvedStatus(ORDER_APPROVED_STATUS_PREPARING);
        }
        if($orderType == ORDER_TYPE_PICKUP_DINEIN && $orderStatus == ORDER_APPROVED_STATUS_READY_FOR_PICKUP) {
            $statusList[ORDER_APPROVED_STATUS_DELIVERED]  =  self::approvedStatus(ORDER_APPROVED_STATUS_DELIVERED);
        }

        switch($orderStatus) {
            case ORDER_APPROVED_STATUS_PENDING:                
                $statusList[ORDER_APPROVED_STATUS_APPROVED]  =  self::approvedStatus(ORDER_APPROVED_STATUS_APPROVED);
                break;
            case ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER:
                $statusList[ORDER_APPROVED_STATUS_PREPARING]  =  self::approvedStatus(ORDER_APPROVED_STATUS_PREPARING);
                break;
            case ORDER_APPROVED_STATUS_PREPARING:
                $statusList[ORDER_APPROVED_STATUS_READY_FOR_PICKUP]  =  self::approvedStatus(ORDER_APPROVED_STATUS_READY_FOR_PICKUP);                    
                break;
            case ORDER_ONTHEWAY:            
                $statusList[ORDER_APPROVED_STATUS_DELIVERED]  =  self::approvedStatus(ORDER_APPROVED_STATUS_DELIVERED);
                break;
        }
        if( $orderStatus !== ORDER_APPROVED_STATUS_REJECTED) {
            $statusList[ORDER_APPROVED_STATUS_REJECTED] = self::approvedStatus(ORDER_APPROVED_STATUS_REJECTED);
        }
        return $statusList;
    }


    public function corporateOrderStatus($orderStatus = null) 
    {   
        $statusList = [
            $orderStatus  =>  self::corporateApprovedStatus($orderStatus),            
        ];
        switch($orderStatus) {
            case ORDER_APPROVED_STATUS_PENDING:                
                $statusList[ORDER_APPROVED_STATUS_DELIVERED]  =  self::corporateApprovedStatus(ORDER_APPROVED_STATUS_DELIVERED);
                break;
            case ORDER_APPROVED_STATUS_DELIVERED:            
                $statusList[ORDER_APPROVED_STATUS_PENDING]  =  self::corporateApprovedStatus(ORDER_APPROVED_STATUS_PENDING);
                break;
        }
        return $statusList;
    }

    public function corporateApprovedStatus($orderStatus = null)
    {   
        $options = [                                         
            ORDER_APPROVED_STATUS_PENDING  => __('admincrud.Unapproved'),
            ORDER_APPROVED_STATUS_DELIVERED  => __('admincrud.Approved'),
        ];
        return ($orderStatus !== null && isset($options[$orderStatus])) ? $options[$orderStatus] : $options;

    }

    public function corporatePaymentTypes($type = null) 
    {
        $options = [                                         
            CORPORATE_BOOKING_PAYMENT_ONLINE  => __('admincrud.Online'),
            CORPORATE_BOOKING_PAYMENT_CREDIT  => __('admincrud.Credit'),
            CORPORATE_BOOKING_PAYMENT_LPO  => __('admincrud.LPO'),            
        ];                
        return ($type !== null && isset($options[$type])) ? $options[$type] : $options;

    }

    public function deliveryTypes($type = null)
    {   
        $options = [
            DELIVERY_TYPE_ASAP  => __('admincrud.ASAP'),
            DELIVERY_TYPE_PRE_ORDER  => __('admincrud.Pre Order'),            
        ];                
        return ($type !== null && isset($options[$type])) ? $options[$type] : $options;
    }

    public function orderTypes($orderTypes = null)
    {                      
        $options = [
            ORDER_TYPE_DELIVERY  => __('admincrud.Delivery'),
            ORDER_TYPE_PICKUP_DINEIN      => __('admincrud.Pickup & Dine In'),
            ORDER_TYPE_BOTH  => __('admincrud.Both'),   
        ]; 
        return ($orderTypes !== null && isset($options[$orderTypes])) ? $options[$orderTypes] : $options;
    }     

    public function paymentTypes($paymentType = null)
    {   
        $options = [
            PAYMENT_OPTION_ONLINE   => __('admincrud.Online'),
            PAYMENT_OPTION_COD      => __('admincrud.COD'),
            PAYMENT_OPTION_WALLET     => __('admincrud.CWallet'),
            //PAYMENT_OPTION_ALL     => __('admincommon.All')
        ];
        
        return ($paymentType !== null && isset($options[$paymentType])) ? $options[$paymentType] : $options;
    }


    public function paymentStatus($paymentStatus = null)
    {
        $options = [
            ORDER_PAYMENT_STATUS_PENDING   => __('admincrud.Pending'),
            ORDER_PAYMENT_STATUS_SUCCESS      => __('admincrud.Success'),
            ORDER_PAYMENT_STATUS_FAILURE     => __('admincrud.Failure')
        ];
        return ($paymentStatus !== null && isset($options[$paymentStatus])) ? $options[$paymentStatus] : $options;
    }

    /**
     * @param object $orderModel object of order table
     * @return boolean 
     */
    public static function addLoyaltyPoints($orderModel)
    {         
        
        $userDetails = User::find($orderModel->user_id);        
        /** Adding Loyalty point to user */                        
        $loyaltyPoint = LoyaltyPoint::where('from_amount', '<=', $orderModel->item_total)->where('to_amount', '>=', $orderModel->item_total)->first();
        if($loyaltyPoint === null) {
            $loyaltyPoint = LoyaltyPoint::select('loyalty_point_id','to_amount','point')->where('status',ITEM_ACTIVE)->where('to_amount' ,'<', $orderModel->order_total)->orderBy('to_amount','DESC')->first();
        }
        // $orderPoint = $loyaltyPoint->point;
        // $userLoyaltyCredit = new UserLoyaltyCredit();
        // $userLoyaltyCredit = $userLoyaltyCredit->fill([
        //     'order_id' => $orderModel->order_id,
        //     'user_id' => $userDetails->user_id,
        //     'loyalty_point_id' => $loyaltyPoint->loyalty_point_id,
        //     'order_amount' => $orderModel->item_total,
        //     'loyalty_point' => $loyaltyPoint->point,
        //     'transaction_for' => 1,
        //     'previous_user_point' => ($userDetails->loyalty_points === null) ? 0 : (int)$userDetails->loyalty_points,
        //     'current_user_point' => ($userDetails->loyalty_points === null) ? 0 + $orderPoint : (int)$userDetails->loyalty_points + $orderPoint,
        // ]);
        //$userLoyaltyCredit->save();
        $userDetails->loyalty_points = ($userDetails->loyalty_points === null) ? 0 + $loyaltyPoint->point : (int)$userDetails->loyalty_points + $loyaltyPoint->point;
        $userDetails->total_loyalty_points = $userDetails->total_loyalty_points  + $loyaltyPoint->point;
        return $userDetails->save();
        /** Adding Loyalty point to user */
    }

    public function convertWebtoDeliveryboystatus($order_status)
    {        
        switch ($order_status) {
            case ORDER_APPROVED_STATUS_PENDING:
                $orderStatus = NODE_ORDER_PENDING;
                break;
            case ORDER_APPROVED_STATUS_APPROVED:
                $orderStatus = NODE_ORDER_ACCEPTED;
                break;
            case ORDER_APPROVED_STATUS_PREPARING:
                $orderStatus = NODE_ORDER_PREPARED;
                break;
            case ORDER_ONTHEWAY:
                $orderStatus = NODE_ORDER_ONTHEWAY;
                break; 
            case ORDER_APPROVED_STATUS_DRIVER_PICKED_UP:
                $orderStatus = NODE_ORDER_ONTHEWAY;
                break; 
            case ORDER_APPROVED_STATUS_DELIVERED:
                $orderStatus = NODE_ORDER_DELIVERED;
                break;
            case ORDER_APPROVED_STATUS_REJECTED:
                $orderStatus = NODE_ORDER_REJECTED;
                break; 
            case ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER:
                $orderStatus = NODE_ORDER_DRIVER_ASSIGNED;
                break;
            case ORDER_APPROVED_STATUS_DRIVER_ACCEPTED:
                $orderStatus = NODE_ORDER_DRIVER_ACCEPTED;
                break;
            case ORDER_DRIVER_REJECTED:
                $orderStatus = NODE_ORDER_DRIVER_REJECTED;
                break;
            case ORDER_DRIVER_DELIVERED:
                $orderStatus = NODE_ORDER_DRIVER_DELIVERED;
                break;
            case ORDER_DRIVER_REQUESTED:
                $orderStatus = NODE_ORDER_DRIVER_REQUESTED;
                break;
            case ORDER_APPROVED_STATUS_READY_FOR_PICKUP:
                $orderStatus = NODE_ORDER_READY_TO_PICKUP;
                break;  
            default:
                $orderStatus = $order_status;
                break;  
        }
        return $orderStatus;
    }

    /**
     * @param int $orderCountType => its working based on order status
     * @param int $orderdayType => its working based on days period
     * @param boolean $isCount => default count else amount will send     
     * @return int/float orders count or orders sum amount
     */
    public static function getOrdersCount($orderCountType = ORDER_COUNT_TYPE_ALL, $orderDayType = ORDER_COUNT_DAY_TYPE_ALL, $isCount = true)
    {        
        $order = new Order();
            
        switch($orderDayType) {
            case ORDER_COUNT_DAY_TYPE_TODAY:
                $order = $order->whereDate('order_datetime',date('Y-m-d'));
                break;            
            case ORDER_COUNT_DAY_TYPE_MONTH:
                $order = $order->whereMonth('order_datetime',date('m'));
                break;            
            case ORDER_COUNT_DAY_TYPE_YEAR:
                $order = $order->whereYear('order_datetime',date('Y'));
                break;            
            case ORDER_COUNT_DAY_TYPE_QUATER_YEAR:
                $current_month = date('m');
                $current_year = date('Y');
                if($current_month>=1 && $current_month<=3)
                {
                    $start_date = strtotime('1-January-'.$current_year);  // timestamp or 1-Januray 12:00:00 AM
                    $end_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM means end of 31 March
                }
                else  if($current_month>=4 && $current_month<=6)
                {
                    $start_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM
                    $end_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM means end of 30 June
                }
                else  if($current_month>=7 && $current_month<=9)
                {
                    $start_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM
                    $end_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM means end of 30 September
                }
                else  if($current_month>=10 && $current_month<=12)
                {
                    $start_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM
                    $end_date = strtotime('1-January-'.($current_year+1));  // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
                }
                $order = $order->whereDate('order_datetime','>=',date('Y-m-d',$start_date))->whereDate('order_datetime','<=',date('Y-m-d',$end_date));
                break;
        }
        switch($orderCountType) {
            case ORDER_COUNT_TYPE_ALL :
            $orderStatus = [
                    ORDER_APPROVED_STATUS_PENDING,
                    ORDER_APPROVED_STATUS_APPROVED,
                    ORDER_APPROVED_STATUS_REJECTED,
                    ORDER_APPROVED_STATUS_PREPARING,
                    ORDER_APPROVED_STATUS_DELIVERED,
                    ORDER_ONTHEWAY,
                    ORDER_APPROVED_STATUS_DRIVER_PICKED_UP,
                    ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER,
                    ORDER_APPROVED_STATUS_DRIVER_ACCEPTED,
                    ORDER_APPROVED_STATUS_READY_FOR_PICKUP,
                    ORDER_DRIVER_REQUESTED,
                ];
            $order = $order->whereIn('order_status',$orderStatus);
            break;
            case ORDER_COUNT_TYPE_PENDING :        
                $orderStatus = [
                    ORDER_APPROVED_STATUS_PENDING,
                    /* ORDER_APPROVED_STATUS_APPROVED,
                    ORDER_APPROVED_STATUS_PREPARING,
                    ORDER_ONTHEWAY,
                    ORDER_APPROVED_STATUS_DRIVER_PICKED_UP,
                    ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER,
                    ORDER_APPROVED_STATUS_DRIVER_ACCEPTED,
                    ORDER_APPROVED_STATUS_READY_FOR_PICKUP,
                    ORDER_DRIVER_REQUESTED, */
                ];
                $order = $order->whereIn('order_status',$orderStatus);
                break; 
            case ORDER_COUNT_TYPE_DELIVERED:
                $orderStatus = [
                    ORDER_DRIVER_DELIVERED,    
                    ORDER_APPROVED_STATUS_DELIVERED,
                ];
                $order = $order->whereIn('order_status',$orderStatus);
                break; 
            case ORDER_COUNT_TYPE_REJECTED:
                $orderStatus = [
                    ORDER_APPROVED_STATUS_REJECTED,
                ];
                $order = $order->whereIn('order_status',$orderStatus);
                break; 
        }  
        /* if(Auth::guard(GUARD_VENDOR)->check() == 1) {
            $vendorDetails = Auth::guard(GUARD_VENDOR)->user();
            $order = $order->where('vendor_id',$vendorDetails->vendor_id);
        } */
            
        if($isCount === true) {
            return $order->where([
                        Order::tableName().'.payment_status' => ORDER_PAYMENT_STATUS_SUCCESS,
                        [Order::tableName().'.payment_type', '<>', PAYMENT_OPTION_COD]
                    ])->orWhere(Order::tableName().'.payment_type', PAYMENT_OPTION_COD)->count();
                    // return $order->count();
        }
            
        return $order->where(['payment_status' => ORDER_PAYMENT_SUCCESS])->sum('order_total');
    } 
}
