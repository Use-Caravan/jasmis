@extends('frontend.layouts.layout')
@section('content')

  <!-- checkout restaurant -->
    <section class="de-checkout" id="branch_checkout" branch_key="{{$branchDetails->branch_key}}">
        <div class="container">

        
            <!-- breadcums -->
            <div class="breadcums wow fadeInUp">
                <ul class="reset">
                    <li><a href="{{route('frontend.index')}}">{{__('Home')}}</a></li>
                    <li><a href="{{route('frontend.branch.index')}}">{{__('Restaurants')}}</a></li>
                    <li><a href="{{route('frontend.branch.show',[$branchDetails->branch_slug])}}">{{$branchDetails->branch_name}}</a></li>
                    <li><span>{{__('Checkout')}}</span></li>
                </ul>
            </div>
            <!-- breadcums -->
            <!-- checkout box -->
            <div class="checkout-box">

                <h2 class="f24 wow fadeInUp">{{__('Checkout')}}</h2>

                <!-- detail cart page -->

                <div class="full_row cart-table wow fadeInUp {{ session::has('corporate_voucher') ? 'disable_pointer_event' : '' }}">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{__('Item')}}</th>
                                    <th>{{__('Quantity')}}</th>
                                    <th class="text-right">{{__('Price')}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="checkout_cart">
                                @include('frontend.branch.checkout-cart')
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- detail cart page -->

                <!-- row end -->
                <div class="full_row v-copon {{ session::has('corporate_voucher') ? 'disable_pointer_event' : '' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="shape-btn wow fadeInUp shape1" href="{{route('frontend.branch.show',[$branchDetails->branch_slug])}}"><span class="shape">{{__('Continue Shopping')}} </span></a>
                            <a class="shape-btn wow fadeInUp shape1 shape-dark" href="{{ route('frontend.clear-cart',['branch_key'=>$branchDetails->branch_key]) }}"><span class="shape">{{__('Clear Cart')}}</span></a>
                        </div>
                        <div class="col-md-6">
                            <span class="voucher_code wow fadeInUp voucher_branch"> {{__('View Offers') }} <a class="voucher_branch_clr"><i class="fa fa-gift" aria-hidden="true"></i></a></span>  
                            <div class="int-group wow fadeInUp">
                                <input type="text" class="form-control" name="coupon_code"  id="coupon_code" value="{{Session::get('coupon_code')}}" placeholder="{{ __('Enter Coupon Code') }}">
                                <button id="apply_coupon" class="btn btn-secondary uppercase loader2" {{ Session::has('coupon_code') ? "disabled" : "" }}>{{__('Apply')}}</button>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- row end -->

                
                <!-- delivery tytpe -->
                <div class="full_row dl_info">
                    <div class="row">
                        @if(Auth::guard(GUARD_USER)->user()->user_type ===  USER_TYPE_CUSTOMER)
                        <div class="col-md-7">
                            <!-- group elements -->
                            <div class="dl_group">
                                <h4 class="heading_border wow fadeInUp">{{__('Order type')}}</h4>
                                <div class="full_row wow fadeInUp">
                                    @foreach($orderTypes as $key => $value) 
                                        @if($key != ORDER_TYPE_BOTH)
                                            <input type="radio" class="radio" name="order_type" value="{{$key}}" id="OT{{$key}}" {{(request()->session()->get('order_type') == $key) ? 'checked' : ''}}>
                                            <label class="radio" for="OT{{$key}}">{{$value}}</label>
                                        @endif
                                    @endforeach                                    
                                </div>
                            </div>
                            <!-- group elements -->

                            <div id="delivery_type_time_slots">

                            </div>
                            
                            <div class="dl_group">
                                <h4 class="heading_border wow fadeInUp">{{__('Choose Payment type')}}</h4>
                                <div class="full_row wow fadeInUp">
                                    @foreach($paymentTypes as $key => $value)
                                        @if($key !== PAYMENT_OPTION_ALL)
                                            <input type="radio" class="radio" name="payment_type" value="{{$key}}" id="PT{{$value}}" value="{{$key}}">
                                            <label class="radio" for="PT{{$value}}">{{$value}}</label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <!-- group elements -->
                        </div>
                        @endif
                        <div class="col-md-5">
                            <h4 class="heading_border wow fadeInUp">{{__('Shopping Cart Total')}}</h4>
                            <table class="shopping_cart wow fadeInUp">
                                <tbody id="checkout_payment">
                                    @include('frontend.branch.checkout-payment')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    
                @if(Auth::guard(GUARD_USER)->user()->user_type ===  USER_TYPE_CUSTOMER)
                <!-- delivery tytpe  end-->
                <div class="full_row dl_address" id="user_delivery_address">

                    <h4 class="heading_border wow fadeInUp">{{__('Delivery address')}} 
                <a href="#modal_address" class="add-address wow fadeInUp" data-toggle="modal" data-target="#modal_address"><i class="material-icons">add_circle_outline</i></a>
                </h4>

                    <div class="row-1">
                        <ul class="reset address_ul">
                            @foreach($userAddress as $key => $value)
                            <li>
                                <div class="box wow zoomIn">
                                    <input type="radio" class="radio" name="user_address_key" id="UDA{{$key}}" value="{{$value->user_address_key}}">
                                    <label for="UDA{{$key}}" class="radio">
                                        <h4>{{$value->address_type_name}}</h4>
                                        <span>{{$value->full_address}}</span>
                                    </label>
                                </div>
                            </li>
                            @endforeach                            
                            @if($userAddress === null || empty($userAddress))
                                <h4>User Address is empty</h4>
                            @endif
                            
                        </ul>
                    </div>

                </div>
                <!-- delivery address -->
                
                @elseif(Auth::guard(GUARD_USER)->user()->user_type ===  USER_TYPE_CORPORATES)
                
                <div class="full_row dl_address" id="personal_info">
                    <h4 class="heading_border wow fadeInUp">{{__('Personal Information')}} </h4>
                    <div class="form-box floating_label">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ ($corporateUser->corporate_name !== null) ? 'focus' : '' }}">
                                    {{ Form::label("d-cname", __('Company Name'), ['class' => 'required' ]) }}
                                    {{ Form::text("corname", ($corporateUser !== null) ? $corporateUser->corporate_name : '', ['class' => 'form-control','id' => "d-cname", 'readonly' => ($corporateUser !== null) ? true : false ]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ ($corporateUser->contact_name !== null) ? 'focus' : '' }}">
                                    {{ Form::label("d-conname", __('Contact Name'), ['class' => 'required' ]) }}
                                    {{ Form::text("conname",($corporateUser !== null) ? $corporateUser->contact_name : '', ['class' => 'form-control','id' => "d-conname", 'readonly' => ($corporateUser !== null) ? true : false]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ ($corporateUser->office_email !== null) ? 'focus' : '' }}">
                                    {{ Form::label("d-offemail", __('Official Email Address'), ['class' => 'required' ]) }}
                                    {{ Form::text("offemail", ($corporateUser !== null) ? $corporateUser->office_email : '', ['class' => 'form-control','id' => "d-offemail",'readonly' => ($corporateUser !== null) ? true : false]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ ($corporateUser->mobile_number !== null) ? 'focus' : '' }}">
                                    {{ Form::label("d-mob", __('Mobile Number'), ['class' => 'required' ]) }}
                                    {{ Form::text("mobnum", ($corporateUser !== null) ? $corporateUser->mobile_number : '', ['class' => 'form-control','id' => "d-mob",'readonly' => ($corporateUser !== null) ? true : false]) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ ($corporateUser->contact_address !== null) ? 'focus' : '' }}">
                                    {{ Form::label("d-conadd", __('Contact Address'), ['class' => 'required' ]) }}
                                    {{ Form::text("conadd",($corporateUser !== null) ? $corporateUser->contact_address : '', ['class' => 'form-control','id' => "d-conadd",'readonly' => ($corporateUser !== null) ? true : false]) }}
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="form-group">
                                    <textarea class="form-control" rows="4" placeholder="Comments" ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="heading_border wow fadeInUp">{{__('Choose Payment Method')}} </h4>
                    <div class="full_row dl_group wow fadeInUp">
                        <input type="radio" name="payment_type" value="{{CORPORATE_BOOKING_PAYMENT_ONLINE}}" class="radio" id="PMOnline">
                        <label class="radio" for="PMOnline">Online</label>
                        <input type="radio" name="payment_type" value="{{CORPORATE_BOOKING_PAYMENT_CREDIT}}" class="radio" id="PMcredit">
                        <label class="radio" for="PMcredit">Credit Facility</label>
                        <input type="radio" name="payment_type" value="{{CORPORATE_BOOKING_PAYMENT_LPO}}" class="radio" id="LPO">
                        <label class="radio" for="LPO">LPO</label>
                    </div>  
                </div>
               
                @endif


                <div class="full_row mb-4 mt-2 text-center btn_checkout">
                    <button class="shape-btn wow fadeInUp shape1" id="place_order"><span class="shape">{{__('Checkout')}}</span></button>
                </div>

            </div>
            <!-- checkout box -->

        </div>

<!-- Voucher  modal -->
<div class="modal ing_modal_ui fade" id="voucher_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="VoucherContent">

            {{-- Voucher will come from Ajax --}}

        </div>
    </div>
</div>
<!-- Voucher modal -->
</section>

<!-- checkout restaurant -->
@include('frontend.layouts.partials._addressmodel')
<script>
$(document).ready(function()
{    
    $('body').on('click','.use_voucher',function()
    {   
        $('#coupon_code').val($(this).attr('voucher_code'));
        $('#voucher_modal').modal('toggle');
        $('#apply_coupon').click();
    });
    $('.voucher_branch').click(function() {
        var branch_key = $('#branch_checkout').attr('branch_key');
        $.ajax({
            type : 'post',
            url : "{{ route('get-branch-vouchers') }}",
            data: { branch_key:branch_key, app_type: {{APP_TYPE_WEB}} },                
            success:function(result){   
                if(result.status == {{HTTP_SUCCESS}}) {
                    $('#voucher_modal').modal('toggle');
                    $('#VoucherContent').html(result.data);
                } 
                else{
                    errorNotify(result.msg)        
                }                   
            }
        });
    });     
    loadTimeslot()
    $('input[type=radio][name=order_type]').change(function() {
        loadTimeslot()
    });
    @if(Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CUSTOMER)
    $('input[type=radio][name=user_address_key], input[type=radio][name=payment_type]').change(function() {
       
        var order_type = $("input[name='order_type']:checked").val();        
        if(order_type == null || order_type == undefined) {
            $('input[type=radio][name=user_address_key]').prop('checked',false);
            errorNotify( "{{ __('Please choose order type') }}" );
            return ;
        } else {
            calculateData();
        }
    });

    $('body').on('change','#delivery_date, #delivery_time',function() {
        if($(this).attr('prop') == 'time') {
            localStorage.setItem("delivery_time", $('#delivery_time').val());
            calculateData();
        } else {
            // Store
            localStorage.setItem("delivery_date", $('#delivery_date').val());
            setLocalStorage();
        }        
    });    


    $('body').on('change','input[type=radio][name=delivery_type]',function()
    {        
        $('#pre_order_div').hide();
        if( $('input[type=radio][name=delivery_type]:checked').val() == 2) {
            $('#pre_order_div').show();
        }
        localStorage.setItem("delivery_type",$('input[type=radio][name=delivery_type]:checked').val());        
    });
    @endif

    $('#apply_coupon').click(function() {
        
        if( $('#coupon_code').val() == '' ){
            errorNotify("{{ __('Please enter coupon code') }}")
        } else {
            calculateData();
        }        
    });

    $('#place_order').click(function()
    {               
        var branch_key = $('#branch_checkout').attr('branch_key');
        var user_address_key = $("input[name='user_address_key']:checked").val();
        var order_type = $("input[name='order_type']:checked").val();
        var payment_option = $("input[type=radio][name='payment_type']:checked").val();
        var coupon_code = $('#coupon_code').val();
        var delivery_date = $('#delivery_date').val();
        var delivery_time = $('#delivery_time').val();
        var delivery_type = $('input[name="delivery_type"]:checked').val();
        var asap = delivery_type;
        var ths = $(this);

        if(branch_key == null) {
            errorNotify(" {{ __('Branch not found') }}");
            return ;
        }

        @if(Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CUSTOMER)
        if(order_type == null || order_type == undefined) {
            errorNotify(" {{ __('Please choose order type') }}");
            return ;
        }

        if(order_type == {{ORDER_TYPE_DELIVERY}} && (user_address_key == null || user_address_key == undefined)) {
            errorNotify("{{ __('Please choose delivery address')}}");
            return ;
        }
                
        if(delivery_date == null || delivery_date == undefined) {
            errorNotify("{{ __('Please choose delivery date') }}");
            return ;
        }

        if(delivery_time == null || delivery_time == undefined) {
            errorNotify("{{ __('Please choose delivery time') }}");
            return ;
        }
        @endif
        if(payment_option == null || payment_option == undefined) {
            errorNotify(" {{ __('Please choose payment type') }}");
            return ;
        }

        var data = {
            branch_key:branch_key,
            user_address_key:user_address_key,
            order_type:order_type,
            payment_option:payment_option,
            coupon_code:coupon_code,
            delivery_date:delivery_date,
            delivery_time:delivery_time,
            delivery_type:delivery_type,
            asap:asap,
            order_notes:''
        };
        $.ajax({
            url: "{{ route('frontend.place-order')}}",
            type: 'post',            
            data : data,
            beforeSend:function() {
                ths.html('<span class="shape"><i class="fa fa-circle-o-notch fa-spin"></i> loading...</span>');
            },
            success: function(result) {
                if(result.status == HTTP_SUCCESS ){
                    successNotify(result.message);
                    ths.html('<span class="shape">Checkout</span>');
                        if(result.data.payment_mode == 1 || result.data.payment_mode == 5 ){
                            window.location =result.data.payment_url;
                        }
                        else {
                             window.location =result.url;   
                        }
                }else{
                    var message = result.message;
                    errorNotify(message.replace(",","<br/>"));
                    ths.html('<span class="shape">Checkout</span>');
                }
            }
        });
    });    

    $('body').on('click','.quantity_maximum, .quantity_minimum',function() {
        var currenct = parseInt($(this).closest('.quantity').find('.quantity_text').val());        
        var newVal = currenct;
        if($(this).attr('action') == 'minus') {
            if(currenct > 0) {
                newVal = currenct - 1;
            }            
        }
        if($(this).attr('action') == 'plus') {
            newVal = currenct + 1; 
        }        
        $(this).closest('.quantity').find('.quantity_text').val(newVal);
        var cart_item_key = $(this).attr('cartitemkey');        
        updatecartQuantity(cart_item_key,newVal);
    });
    
});
function setLocalStorage()
{    
    
    if(localStorage.delivery_type != undefined) {
        $('input[type=radio][name=delivery_type][value='+localStorage.delivery_type+']').attr('checked',true);
        $('#pre_order_div').hide();
        if( localStorage.delivery_type == 2) {
            $('#pre_order_div').show();
        }
    }

    if(localStorage.delivery_date != undefined && localStorage.delivery_date !== null) {
        $('#delivery_date').val(localStorage.delivery_date);
        localStorage.setItem("delivery_date", $('#delivery_date').val());
        $("#delivery_time option").hide();
        $("#delivery_time option[data-date=" + $('#delivery_date').val() + "]").show();
        if(localStorage.delivery_time != undefined) {
            $('#delivery_time').val(localStorage.delivery_time);
        }
    }    
}
function loadTimeslot()
{
    var branch_key = $('#branch_checkout').attr('branch_key');
    var order_type = $('input[type=radio][name=order_type]:checked').val();

    if(order_type === undefined) {
        return ;
    }

    $('#user_delivery_address').show();
    if(order_type == {{ORDER_TYPE_PICKUP_DINEIN}}) {
        $('#user_delivery_address').hide();
    }

    $.ajax({
        url: "{{ url('api/v1/timeslot')}}",
        type: 'post',
        data : {branch_key : branch_key, order_type : order_type, request_from : 'web' },
        success: function(result) {
            $('#delivery_type_time_slots').html(result.data);    
            setLocalStorage();        
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $('input[type=radio][name=order_type]').prop('checked',false);
            errorNotify(jqXHR.responseJSON.message);
            /* alert(jqXHR.status);
            alert(textStatus);
            alert(errorThrown); */
        },            
    });        
}

function updatecartQuantity(cart_item_key, quantity)
{
    $.ajax({ 
        url: "{{ route('frontend.cart-quantity-update')}}",
        type: 'post',        
        data : {cart_item_key:cart_item_key,quantity:quantity,branch_key:$('#branch_checkout').attr('branch_key'),checkout:true},
        success: function(result) {
            if(result.status == HTTP_SUCCESS ){
                successNotify(result.message);
                $('#checkout_cart').html(result.checkout_cart);
                $('#checkout_payment').html(result.checkout_payment);

                if(result.cart_quantity > 0) {
                    $('#cartIconLi').show();
                    $('#cartIconLi a').attr('href',result.checkout_url);
                    $('#cartCountSpan').html(result.cart_quantity);
                } else {
                    window.location.replace(result.branch_url);
                }              
            }else{
                var message = result.message;
                errorNotify(message.replace(",","<br/>"));
            }
        }
    });
}


function calculateData()
{  
    var branch_key = $('#branch_checkout').attr('branch_key');
    var user_address_key = $("input[name='user_address_key']:checked").val();
    var order_type = $("input[name='order_type']:checked").val();
    var payment_option = $("input[name='payment_type']:checked").val();
    var coupon_code = $('#coupon_code').val();
    var delivery_date = $('#delivery_date').val();
    var delivery_time = $('#delivery_time').val();
    var delivery_type = $('input[name="delivery_type"]:checked').val();
    var asap = delivery_type;
    
    if(branch_key == null) {
        errorNotify("{{ __('Branch not found') }}");
        return ;
    }
    var data = {
        branch_key:branch_key,
        user_address_key:user_address_key,
        order_type:order_type,
        payment_option:payment_option,
        coupon_code:coupon_code,
        delivery_date:delivery_date,
        delivery_time:delivery_time,
        asap:asap,
        order_notes:''
    };
    $.ajax({
        url: "{{ route('frontend.calculate-data')}}",
        type: 'post',            
        data : data,  
        /* beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'))},           */
        success: function(result) {
            if(result.status == HTTP_SUCCESS ){
                successNotify(result.message);
                $('#checkout_cart').html(result.checkout_cart);
                $('#checkout_payment').html(result.checkout_payment);
                return "voucher empty";
            }else{
                if(result.system.error_for == 'voucher_code') {
                    $('#coupon_code').val('');
                }
                var message = result.message;
                errorNotify(message.replace(",","<br/>"));
            }
        }
    });
}
</script>
@endsection