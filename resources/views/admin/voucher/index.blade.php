@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
          <h1 class="box-title">@lang('admincrud.Voucher Management')</h1>
          <div class="top-action">
            <a href="{!! route('voucher.create') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                  <th width="20">@lang('admincommon.S.No')</th>
                  <th>@lang('admincrud.Promo Code')</th>
                  <th>@lang('admincrud.Value')</th>
                  <th>@lang('admincrud.Promo Code For')</th>
                  <th>@lang('admincrud.App Type')</th>
                  <th class="status">@lang('admincommon.Status')</th>
                  <th class="action">@lang('admincommon.Action')</th>
              </tr>
              <tr>
                <th></th>
                <th>
                    {{ Form::text("promo_code", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Promo Code'), "data-name" => "1"]) }}                         
                </th>
                <th>
                    {{ Form::text("value", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Value'), "data-name" => "2"]) }}                         
                </th>
                <th>
                    {{ Form::select('apply_promo_for',$model->selectApplyPromo(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "3"] )}}
                </th>
                <th>
                    {{ Form::select('app_type', $model->selectAppTypes(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "4"] )}}
                </th>
                <th class="status">
                    {{ Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "5"] )}}
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
        'ajax' : "{{ route('voucher.index') }}",
        'columns': [
            { 'data' : 'DT_RowIndex', 'name' : 'voucher_id', 'searchable' : false},
            { 'data' : 'promo_code', 'name' : 'promo_code'},
            { 'data' : 'value','sortable' : false},
            { 'data' : 'apply_promo_for'},
            { 'data' : 'app_type','name':'app_type','sortable' : false},
            { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
            { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
        ],      
    });    
});
</script>
@endsection