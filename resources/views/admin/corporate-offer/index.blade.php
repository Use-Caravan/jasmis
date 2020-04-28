@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
          <h1 class="box-title">@lang('admincrud.Corporate Offer Management')</h1>
          <div class="top-action">
            <a href="{!! route('corporate-offer.create') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                  <th width="20">@lang('admincommon.S.No')</th>
                  <th>@lang('admincrud.Offer Name')</th>
                  <th>@lang('admincrud.Offer Type')</th>
                  <th>@lang('admincrud.Offer Value') %</th>
                  <th>@lang('admincrud.Offer Level') </th>
                  <th>@lang('admincrud.Start Date')</th>
                  <th>@lang('admincrud.End Date')</th>
                  <th class="status">@lang('admincommon.Status')</th>
                  <th class="action">@lang('admincommon.Action')</th>
              </tr>
              <tr>
                <th></th>
                <th>
                    {{ Form::text("offer_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Offer Name'), "data-name" => "1"]) }}                         
                </th>
                <th>
                    {{ Form::text("offer_type", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Offer Type'), "data-name" => "2"]) }}                         
                </th>
                <th>
                    {{ Form::text("offer_value", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Value'), "data-name" => "3"]) }} 
                </th> 
                <th>
                    {{ Form::text("offer_level", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Offer Level'), "data-name" => "4"]) }} 
                </th>
                <th></th>
                <th></th>               
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
      'ajax' : "{{ route('corporate-offer.index') }}",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'corporate_offer_id', 'searchable' : false},
        { 'data' : 'offer_name', 'name' : 'COL.offer_name'},
        { 'data' : 'offer_type', 'name' : 'offer_type'},
        { 'data' : 'offer_value', 'name' : 'offer_value'},
        { 'data' : 'offer_level', 'name' : 'offer_level'},
        { 'data' : 'start_datetime'},        
        { 'data' : 'end_datetime'},
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
      
    });    
});
</script>
@endsection