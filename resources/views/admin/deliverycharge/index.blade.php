@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
        <h1 class="box-title">@lang('admincrud.Delivery Charge Management')</h1>
            <div class="top-action">
                <a href="{!! route('deliverycharge.create') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
            </div>
        </div> <!--box-header-->

        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                  <th width="20">@lang('admincommon.S.No')</th>                  
                  <th>@lang('admincrud.From Kilometer')</th>
                  <th>@lang('admincrud.To Kilometer')</th>
                  <th>@lang('admincrud.Price')</th>
                  <th class="status">@lang('admincommon.Status')</th>
                  <th class="action">@lang('admincommon.Action')</th>
              </tr>
              <tr>
                <th></th>
                <th>
                    {{ Form::text("from_km", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.From Kilometer'), "data-name" => "1"]) }}                         
                </th>
                <th>
                    {{ Form::text("to_km", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.To Kilometer'), "data-name" => "2"]) }}                         
                </th>
                <th>
                    {{ Form::text("price", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Price'), "data-name" => "3"]) }}                         
                </th>
                <th class="status">
                    {{ Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "4"] )}}
                </th>
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
      'ajax' : "{{ route('deliverycharge.index') }}",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'delivery_charge_id', 'searchable' : false},        
        { 'data' : 'from_km', 'name' : 'from_km'},
        { 'data' : 'to_km', 'name' : 'to_km' },
        { 'data' : 'price', 'name' : 'price' },
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
      
    });   
});
</script>
@endsection