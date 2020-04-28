@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
        <h1 class="box-title">@lang('admincrud.Vendor Management')</h1>
            <div class="top-action">
                <a href="{!! route('vendor.create') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
            </div>

        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th width="20">@lang('admincommon.S.No')</th>
                        <th>@lang('admincrud.Vendor Name')</th>
                        <th>@lang('admincrud.Area Name')</th>
                        <th>@lang('admincrud.Approved Status')</th>
                        <th>@lang('admincrud.Created Date')</th>
                        <th class="status">@lang('admincommon.Status')</th>
                        <th class="popular">@lang('admincommon.Popular')</th>
                        <th class="action">@lang('admincommon.Action')</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>
                            {{ Form::text("vendor_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Vendor Name'), "data-name" => "1"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("area_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Area Name'), "data-name" => "2"]) }}                         
                        </th>
                       {{-- <th class="status">
                            {{ Form::select('availability_status',$model->availablityTypes(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "3"] )}}
                        </th> --}}
                        <th>
                            {{ Form::select('approved_status', $model->approvedStatus(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "3"] )}} 
                        </th>
                        <th>
                            {{ Form::text("created_at", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Created Date'), "data-name" => "4"]) }}                         
                        </th>
                        <th class="status">
                            {{ Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "5"] )}}
                        </th>
                        <th class="popular">
                            {{ Form::select('popular_status', Common::popular(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "6"] )}}
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


      'ajax' : "{{ route('vendor.index') }}",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'vendor_id', 'searchable' : false},
        { 'data' : 'vendor_name', 'name' : 'VL.vendor_name'},
        { 'data' : 'area_name','name' : 'AL.area_name'},
        /*{ 'data' : 'availability_status', 'name' : 'branch.availability_status'},*/
        { 'data' : 'approved_status'},
        { 'data' : 'created_at',},
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'popular_status', 'sClass' : 'popular_status', 'orderable' : false},
        
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false},
      ],

    });  


});
</script>
@endsection