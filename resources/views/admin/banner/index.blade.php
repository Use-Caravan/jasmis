@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
          <h1 class="box-title">@lang('admincrud.Banner Management')</h1>
        <div class="top-action">
            <a href="{!! route('banner.create') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
          </div>
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
                <tr>
                    <th width="20">@lang('admincommon.S.No')</th>
                    <th>@lang('admincrud.Banner Name')</th>
                    <th>@lang('admincrud.Banner Image')</th>
                    <th>@lang('admincrud.Redirect URL')</th>
                    <th class="status">@lang('admincommon.Status')</th>
                    <th class="action">@lang('admincommon.Action')</th>
                </tr>
                <tr>
                    <th></th>
                    <th>
                        {{ Form::text("banner_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Banner Name'), "data-name" => "1"]) }}                         
                    </th>
                    <th> </th>
                    <th>
                        {{ Form::text("redirect_url", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Redirect URL'), "data-name" => "3"]) }}                         
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
      'ajax' : "{{ route('banner.index') }}",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'banner_id', 'searchable' : false},
        { 'data' : 'banner_name', 'name' : 'BL.banner_name'},
        { 'data' : 'banner_file','searchable' : false, 'sortable' : false},
        { 'data' : 'redirect_url','name':'redirect_url'},
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
      
    });    
});
</script>
@endsection