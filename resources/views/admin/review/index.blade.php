@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
        <h1 class="box-title">@lang('admincrud.Ratings Management')</h1>
            
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th width="20">@lang('admincommon.S.No')</th>
                        <th>@lang('admincommon.Name')</th>
                        <th>@lang('admincrud.Branch Name')</th>
                        <th>@lang('admincrud.Rating')</th>
                        <th>@lang('admincrud.Review')</th>
                        <th>@lang('admincrud.Approved Status')</th>
                        <th>@lang('admincrud.Created Date')</th>
                        <th class="status">@lang('admincommon.Status')</th>
                        <th class="action">@lang('admincommon.Action')</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>
                            {{ Form::text("name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Name'), "data-name" => "1"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("vendor_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Vendor Name'), "data-name" => "2"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("rating", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Rating'), "data-name" => "3"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("review", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Review'), "data-name" => "4"]) }}                         
                        </th>
                        <th>
                            {{ Form::select('approved_status', $model->approvedStatus(), '' ,['class' => 'selectpicker filterSelect ', 'placeholder' => __('admincommon.All'), "data-name" => "5"] )}} 
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
      'ajax' : "{{ route('review.index') }}",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'branch_review_id', 'searchable' : false},
        { 'data' : 'name','name' : 'user.first_name',},
        { 'data' : 'branch_name','name' : 'BL.branch_name'},
        { 'data' : 'rating'},
        { 'data' : 'review'},
        { 'data' : 'approved_status'},
        { 'data' : 'created_at'},
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
     
    });    
});
</script>
@endsection