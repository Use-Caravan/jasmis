@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
          <h1 class="box-title">@lang('admincrud.Country Management')</h1>
           <div class="top-action">
            <a href="{!! route('country.create') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
         
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                    <th width="20">@lang('admincommon.S.No')</th>
                    <th>@lang('admincrud.Country Name')</th>                  
                    <th>@lang('admincrud.Country Code')</th>                  
                    <th class="status">@lang('admincommon.Status')</th>
                    <th class="action">@lang('admincommon.Action')</th>
              </tr>
              <tr>
                    <th></th>
                    <th>
                        {{ Form::text("country_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Country Name'), "data-name" => "1"]) }}                         
                    </th>  
                    <th>
                        {{ Form::text("country_code", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Country Code'), "data-name" => "2"]) }}
                    </th>  
                    <th class="status">
                        {{ Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "3"] )}}
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
        'ajax' : "{{ route('country.index') }}",
        'columns': [
            { 'data' : 'DT_RowIndex', 'name' : 'country_id', 'searchable' : false},
            { 'data' : 'country_name', 'name' : 'CL.country_name'},
            { 'data' : 'country_code'},
            { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
            { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
        ],
        
    });    
    setInterval(function() {
        window.dataTable.api().ajax.reload();
    },30000 );
});
</script>
@endsection