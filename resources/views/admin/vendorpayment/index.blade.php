@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
          <h1 class="box-title">@lang('admincrud.Payment Management')</h1>
        </div> <!--box-header-->
        <div class="box-body">
            {{ Form::open(['route' => 'vendorpayment.index', 'id' => 'payment-filter', 'class' => 'form-horizontal', 'method' => 'GET'])}}
            @if(APP_GUARD == GUARD_ADMIN)
                {{ Form::select('vendor_id', $vendorList, request()->vendor_id ,['class' => 'selectpicker filterSelect','id' => 'vendor', 'placeholder' => __('admincommon.All')] )}} 
            @endif
            @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                {{ Form::select('branch_id', $branchList, request()->branch_id ,['class' => 'selectpicker filterSelect', 'id' => 'Vendor-branch_id','placeholder' => __('admincommon.All')] )}} 
            @endif
            {{ Form::select('order_datetime', $dates, (request()->order_datetime === null) ? key($dates) : request()->order_datetime,['class' => 'selectpicker filterSelect', 'id' => 'order_date','placeholder' => __('admincommon.All')] )}} 
            <div class="top-action pull-right">
                <button value="submit" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Filter')</button>
            </div>
            {{ Form::close() }}
            <div class="table-responsive">
            <table class="table table-bordered table-striped" id="paymentTable">
              <thead>
                <tr>
                    <th width="20">@lang('admincommon.S.No')</th>
                    @if(APP_GUARD == GUARD_ADMIN)
                        <th>@lang('admincrud.Vendor Name')</th>   
                    @endif
                    @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                    <th>@lang('admincrud.Branch Name')</th>
                        @endif
                    <th>@lang('admincrud.Order Date Time')</th>
                    <th>@lang('admincrud.Order Total')</th>
                    <th>@lang('admincrud.Vendor Profit')</th>
                    <th>@lang('admincrud.Admin Profit')</th>
                </tr>
                @foreach($vendorPayments as $key => $value)
                <tr>
                    <td>{{$key+1}}</td>
                    @if(APP_GUARD == GUARD_ADMIN)
                    <td>{{$value->vendor_name}}</td>
                    @endif
                    @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                    <td>{{$value->branch_name}}</td>
                    @endif
                    <td>{{$value->order_datetime}}</td>
                    <td>{{$value->order_total}}</td>
                    <td>{{$value->vendor_profit}}</td>
                    <td>{{$value->admin_profit}}</td>
                </tr>
                @endforeach
              </thead>
            </table>
            
          </div>
        </div> <!--box-body-->
    </div> <!--box-->
  </section>
</div> <!--content-wrapper-->
@include('admin.layouts.partials._tableconfig')
<script type="text/javascript">
$(document).ready(function(){
   $('#vendor').on('change',function() {
        $vendorId = $(this).val();
        $.ajax({
            url: "{{ route('get-branch-by-vendor') }}",
            type: 'post',
            data: {vendor_id:$vendorId},
            success: function(result){ 
                if(result.status == AJAX_SUCCESS){
                    $('#Vendor-branch_id').html('');                    
                     $('#Vendor-branch_id').append($('<option>', { value : "" }).text("All"));
                    $.each(result.data,function(key,title)
                    {  
                        $('#Vendor-branch_id').append($('<option>', { value : key }).text(title));                       
                    });                    
                    $('.selectpicker').selectpicker('refresh');
                    
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    }); 
});
</script>
@endsection