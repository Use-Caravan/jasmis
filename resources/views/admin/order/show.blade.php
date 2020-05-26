@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
  <div class="col-md-offset-2 col-md-6">
    <div class="box">      
        <div class="box-header with-border">
          <div class="flash-message">
            @if(Session::has('success'))
              <p class="alert alert-success">
                {{ Session('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              </p>
            @endif            
          </div> <!-- end .flash-message -->
          <h1 class="box-title">@lang('admincrud.Order Management')</h1>
          
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="orderTable">              
              <tbody>
                <tr>
                    <th>@lang('admincrud.Order Number')</th>
                    <td>{{ $model->order_number }}</td>
                </tr>  
                <tr>
                    <th>@lang('admincommon.Customer Name')</th>
                    <td>{{ $model->first_name.$model->last_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Phone Number')</th>
                    <td>{{ $model->user_phone_number }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Vendor Name')</th>
                    <td>{{ $model->vendor_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Branch Name')</th>
                    <td>{{ $model->branch_name }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Date Time')</th>
                    <td>{{ $model->order_datetime }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Type')</th>
                    <td>{{ ($model->order_type === null ) ? 'undefined' : $model->orderTypes($model->order_type) }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Total')</th>
                    <td>{{ ($model->order_total === null) ? '-' : $model->order_total }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Payment Type')</th>
                    <td>{{ ($model->payment_type === null) ? 'undefined' : $model->paymentTypes($model->payment_type) }}</td>    
                </tr>                   
                <tr>
                    <th>@lang('admincrud.Item Total')</th>
                    <td>{{ $model->item_total }}</td>
                </tr> 
                <tr>
                    <th>@lang('admincrud.Wallet Amount Used')</th>
                    <td>{{ $model->wallet_amount_used }}</td>
                </tr>
               <tr>
                    <th>@lang('admincrud.Delivery Fee')</th>
                    <td>{{ $model->delivery_fee }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Delivery Distance')</th>
                    <td>{{ $model->delivery_distance }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Tax')</th>
                    <td>{{ ($model->tax === null) ? '-' : $model->tax }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Tax Percent')</th>
                    <td>{{ ($model->tax_percent) === null ? '-' : $model->tax_percent }}</td>
                </tr>
                @if($model->service_tax != 0 || $model->service_tax != null)
                <tr> 
                    <th>@lang('admincrud.Service Tax')</th>
                    <td>{{ ($model->service_tax === null) ? '-' : $model->service_tax }}</td>
                </tr> 
                @endif
                <tr>
                    <th>@lang('admincrud.Service Tax Percent' )</th>
                    <td>{{ ($model->service_tax_percent == null) ? '-' : $model->service_tax_percent }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Voucher Code')</th>
                    <td>{{ ($model->promo_code === null) ? '-' : $model->promo_code }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Voucher Offer Value')</th>
                    <td>{{ ($model->voucher_offer_value === null) ? '-' : $model->voucher_offer_value }}</td>
                </tr> 
                {{-- <tr>
                    <th>@lang('admincommon.Payment Status')</th>
                    <td>{{ ($model->payment_status = null) ? 'undefined' : $model->paymentStatus($model->payment_status) }}</td>
                </tr> --}}
                <tr>
                    <th>@lang('admincrud.Payment Response')</th>
                    <td>{{ ($model->payment_response === null) ? '-' : $model->payment_response }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Approved Date Time')</th>
                    <td>{{ ($model->order_approved_datetime === null) ? '-' : $model->order_approved_datetime }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Rejected Date Time')</th>
                    <td>{{ ($model->order_rejected_datetime === null) ? '-' : $model->order_rejected_datetime }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Order Payment Id')</th>
                    <td>{{ ($model->order_payment_id === null) ? '-' : $model->order_payment_id }}</td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Item Name')</th>
                    <td>
                        @foreach($model->items as $key => $itemName)
                            {{ ($itemName->item_name === null) ? '-' : $itemName->item_name }}</br>
                            @foreach($itemName->ingredients as $key => $ingredientName )
                                <ul><li>{{ ($ingredientName->ingredient_name === null) ? '-' : $ingredientName->ingredient_name }}</li></ul>
                            @endforeach
                            
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>@lang('admincrud.Ingredient Group Name')</th>
                    <td>{{ ($model->ingredient_group_names === null) ? '-' : $model->ingredient_group_names }}</td>
                </tr> 
                <tr>
                    <th>@lang('admincrud.Ingredient Name')</th>
                    <td>{{ ($model->ingredient_names === null) ? '-' : $model->ingredient_names }}</td>
                </tr>
                <tr>
                    <th>@lang('admincommon.Status')</th>
                    @if($model->status == ITEM_ACTIVE)
                    <td>@lang('admincommon.Active')</td>
                    @elseif($model->status == ITEM_INACTIVE)
                    <td>@lang('admincommon.Inactive')</td>
                    @endif
                </tr>
                </tbody>
            </table>
          </div>
          </div>
        </div> <!--box-body-->
    </div> <!--box-->
  </section>
</div> <!--content-wrapper-->

@endsection