@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
          <h1 class="box-title">@lang('admincrud.Item Management')</h1>
          <div class="top-action">
                <a href="{{ route('item.create') }}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
              </div>
        </div> <!--box-header-->

        <div class="box-body">
         
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                  <th width="20">@lang('admincommon.S.No')</th>
                  <th>@lang('admincrud.Item Name')</th>
                  @if(APP_GUARD == GUARD_ADMIN)
                  <th>@lang('admincrud.Vendor Name')</th>
                  @endif
                  @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                  <th>@lang('admincrud.Branch Name')</th>                  
                  @endif
                  <th>@lang('admincrud.Item Category')</th>
                  {{-- <th>@lang('admincrud.Item Cuisine')</th>--}} 
                  <th class="status">@lang('admincommon.Created Date')</th>
                  <th class="status">@lang('admincommon.Sort No')</th>
                  <th class="status">@lang('admincrud.Approved Status')</th>
                  <th class="status">@lang('admincommon.Status')</th>                  
                  <th class="status">@lang('admincommon.Quick Buy')</th>
                  <!--<th class="status">@lang('admincommon.New Item')</th>-->                  
                  <th class="action">@lang('admincommon.Action')</th>
              </tr> 
                <tr>
                    <th></th>
                    <th>
                        {{ Form::text("item_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Item Name'), "data-name" => "1"]) }}
                    </th>  
                    @if(APP_GUARD == GUARD_ADMIN)
                    <th>
                        {{ Form::select('vendor_id', $vendorList, '' ,['class' => 'selectpicker filterSelect','id' => 'vendor', 'placeholder' => __('admincommon.All'), "data-name" => "2"] )}}
                    </th>
                    @endif
                    @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                    <th>
                        {{ Form::select('branch_id', [], '' ,['class' => 'selectpicker filterSelect','id' => 'Vendor-branch_id', 'placeholder' => __('admincommon.All'), "data-name" => "3"] )}}
                    </th> 
                    @endif                                       
                    <th>
                        {{ Form::text('category_name',  '' ,['class' => 'form-control filterText', 'placeholder' => __('admincrud.Item Category'), "data-name" => "4"] )}}
                    </th> 
                    {{-- <th>
                        {{ Form::text('cuisine_name', '' ,['class' => 'form-control filterText', 'placeholder' => __('admincrud.Item Cuisine'), "data-name" => "5"] )}}
                    </th> --}}
                    <th>
                         {{ Form::text('created_at', '' ,['class' => 'form-control filter_date_time_picker', 'placeholder' => __('admincrud.Created Date'), "data-name" => "6"] )}}
                    </th>    
                    <th>{{ Form::text('sort_no', '' ,['class' => 'form-control filterText', 'placeholder' => __('admincommon.Sort No'), "data-name" => "7"] )}}</th>
                    <th>
                        {{ Form::select('approved_status', $model->approvedStatus(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "8"] )}} 
                    </th>
                    <th class="status">
                        {{ Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "9"] )}}
                    </th>
                    <th class="quickbuy">
                        {{ Form::select('quickbuy_status', Common::quickbuy(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "10"] )}}
                    </th>
                    <!--<th class="newitem">
                        {{ Form::select('newitem_status', Common::newitem(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "11"] )}}
                    </th>-->
                    <th class="action"></th>
                </tr>
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
    window.dataTable = $('#dataTable').dataTable({      
        'ajax' : {
                url : "{{ route('item.index') }}",
                data: function (d) {
                    d.created_at = $('input[name=created_at]').val();                    
                }
            },
        'columns': [
            { 'data' : 'DT_RowIndex', 'name' : 'item_id', 'searchable' : false},
            { 'data' : 'item_name', 'name' : 'IL.item_name'},
            @if(APP_GUARD == GUARD_ADMIN)
            { 'data' : 'vendor_name', 'name' : 'vendor.vendor_id','sortable' : false},
            @endif
            @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
            { 'data' : 'branch_name', 'name' : 'branch_id','sortable' : false},            
            @endif
            { 'data' : 'category_name','name' : 'CL.category_name','sortable' : false},
            /* { 'data' : 'cuisine_name','name' : 'CUL.cuisine_name','sortable' : false}, */
            { 'data' : 'created_at', 'searchable' : false},
            { 'data' : 'sort_no'},
            { 'data' : 'approved_status'},
            { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
            { 'data' : 'quickbuy_status', 'sClass' : 'quickbuy_status', 'orderable' : false},
            //{ 'data' : 'newitem_status', 'sClass' : 'newitem_status', 'orderable' : false },
            { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
        ],      
    });        

    $('#vendor').on('change',function() {
        $vendorId = $(this).val();
        $.ajax({
            url: "{{ route('get-branch-by-vendor') }}",
            type: 'post',
            data: {vendor_id:$vendorId},
            success: function(result){ 
                if(result.status == AJAX_SUCCESS){
                    $('#Vendor-branch_id').html('');
                    $('#Vendor-branch_id').append($('<option>',{ value : "" }).text('All'));                       
                    $.each(result.data,function(key,title) {
                        $('#Vendor-branch_id').append($('<option>', { value : key }).text(title));                       
                    });
                    $('.selectpicker').selectpicker('refresh');
                } else {
                    errorNofify('Something went wrong','Error');
                }
            }
        });
    });
});
</script>
@endsection