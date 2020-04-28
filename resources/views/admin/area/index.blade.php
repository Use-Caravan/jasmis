@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
            @include('admin.layouts.partials._flashmsg')
            <h1 class="box-title">@lang('admincrud.Area Management')</h1>
            <div class="top-action">
                <a href="{!! route('area.create') !!}" title="@lang('admincommon.Add New')" class="btn mb15 pull-right"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
            </div>
        </div> <!--box-header-->

        <div class="box-body">                
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
                <tr>
                    <th width="20">@lang('admincommon.S.No')</th>
                    <th>@lang('admincrud.Country Name')</th>  
                    <th>@lang('admincrud.City Name')</th>
                    <th>@lang('admincrud.Area Name')</th>
                    <th class="status">@lang('admincommon.Status')</th>
                    <th class="action">@lang('admincommon.Action')</th>
                </tr>                 
                <tr>
                    <th></th>
                    <th>
                        {{ Form::text("country_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Country Name'), "data-name" => "1"]) }}                         
                    </th>  
                    <th>
                        {{ Form::text("city_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.City Name'), "data-name" => "2"]) }}
                    </th>
                    <th>
                        {{ Form::text("area_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Area Name'), "data-name" => "3"]) }}
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
        'ajax' : "{{ route('area.index') }}",
        'columns': [
            { 'data' : 'DT_RowIndex', 'name' : 'area_id', 'searchable' : false},
            { 'data' : 'country_name', 'name': 'CYL.country_name'},
            { 'data' : 'city_name', 'name' : 'CTL.city_name'},
            { 'data' : 'area_name', 'name' : 'AL.area_name' },
            { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
            { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
        ],
    });    
});
</script>
@endsection