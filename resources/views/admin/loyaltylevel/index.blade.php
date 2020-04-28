@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
          <h1 class="box-title">@lang('admincrud.Loyalty Level Management')</h1>
           <div class="top-action">
            <a href="{!! route('loyaltylevel.create') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
         
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                    <th width="20">@lang('admincommon.S.No')</th>
                    <th>@lang('admincrud.Loyalty Level Name')</th>                  
                    <th>@lang('admincrud.From Point')</th>
                    <th>@lang('admincrud.To Point')</th>
                    <th>@lang('admincrud.Redeem Amount Per Point')</th>                  
                    <th class="status">@lang('admincommon.Status')</th>
                    <th class="action">@lang('admincommon.Action')</th>
              </tr>
              <tr>
                    <th></th>
                    <th>
                        {{ Form::text("loyalty_level_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Loyalty Level Name'), "data-name" => "1"]) }}                         
                    </th>  
                    <th>
                        {{ Form::text("from_point", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.From Point'), "data-name" => "2"]) }}
                    </th>
                    <th>
                        {{ Form::text("to_point", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.To Point'), "data-name" => "3"]) }}
                    </th>
                    <th>
                        {{ Form::text("redeem_amount_per_point", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Redeem Amount Per Point'), "data-name" => "3"]) }}
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
        'ajax' : "{{ route('loyaltylevel.index') }}",
        'columns': [
            { 'data' : 'DT_RowIndex', 'name' : 'loyalty_level_id', 'searchable' : false},
            { 'data' : 'loyalty_level_name', 'name' : 'LL.loyalty_level_name'},
            { 'data' : 'from_point'},
            { 'data' : 'to_point'},
            { 'data' : 'redeem_amount_per_point'},
            { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
            { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
        ],
        
    });    
});
</script>
@endsection