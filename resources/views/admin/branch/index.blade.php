@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
        <h1 class="box-title">@lang('admincrud.Branch Management')</h1>
            <div class="top-action">
                <a href="{!! route('branch.create') !!}" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Add New')</a>
            </div>
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th width="20">@lang('admincommon.S.No')</th>
                        @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                        <th>@lang('admincrud.Branch Name')</th>
                        @endif
                        @if(APP_GUARD == GUARD_ADMIN)
                        <th>@lang('admincrud.Vendor Name')</th>
                        @endif
                        <th>@lang('admincrud.Area Name')</th>
                        <th>@lang('admincrud.Availability Status')</th>
                        <th>@lang('admincrud.Approved Status')</th>
                        <th>@lang('admincrud.Created Date')</th>
                        <th class="status">@lang('admincommon.Status')</th>
                        <th class="action">@lang('admincommon.Action')</th>
                    </tr>
                    <tr>
                        <th></th>
                        @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                        <th>
                            {{ Form::text("branch_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Branch Name'), "data-name" => "1"]) }}                         
                        </th>
                        @endif
                        @if(APP_GUARD == GUARD_ADMIN)
                        <th>
                            {{ Form::text("vendor_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Vendor Name'), "data-name" => "2"]) }}                         
                        </th>
                        @endif
                        <th>
                            {{ Form::text("area_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Area Name'), "data-name" => "3"]) }}                         
                        </th>
                        <th class="status">
                            {{ Form::select('availability_status',$model->availablityStatus(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "4"] )}}
                        </th>
                        <th>
                            {{ Form::select('approved_status', $model->approvedStatus(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "5"] )}} 
                        </th>
                         <th>
                            {{ Form::text("created_at", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Created Date'), "data-name" => "6"]) }}                         
                        </th>
                        <th class="status">
                            {{ Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "7"] )}}
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
      'ajax' : "{{ route('branch.index') }}",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'branch_id', 'searchable' : false},
        @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
        { 'data' : 'branch_name', 'name' : 'BL.branch_name'},
        @endif
        @if(APP_GUARD == GUARD_ADMIN)
        { 'data' : 'vendor_name', 'name' : 'VL.vendor_name'},
        @endif
        { 'data' : 'area_name','name' : 'AL.area_name'},
        { 'data' : 'availability_status'},
        { 'data' : 'approved_status'},
        { 'data' : 'created_at',},  
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
     
    });
});
</script>
@endsection