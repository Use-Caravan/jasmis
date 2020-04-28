@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
        <h1 class="box-title">@lang('admincrud.User Wishlist Management')</h1>
            
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th width="20">@lang('admincommon.S.No')</th>
                        <th>@lang('admincommon.Name')</th>
                        <th>@lang('admincommon.User Name')</th>
                        <th>@lang('admincrud.Branch Name')</th>
                        <th>@lang('admincommon.Email')</th>
                        <th class="status">@lang('admincommon.Status')</th>
                        <th class="action">@lang('admincommon.Action')</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>
                            {{ Form::text("name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Name'), "data-name" => "1"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("username", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.User Name'), "data-name" => "2"]) }}                         
                        </th>
                       
                        <th>
                            {{ Form::text("branch_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Branch Name'), "data-name" => "3"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("email", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Email'), "data-name" => "4"]) }}                         
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
      'ajax' : "{{ route('userwishlist.index') }}",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'user_wishlist_id', 'searchable' : false},
        { 'data' : 'name','name' : 'user.first_name',},
        { 'data' : 'username','name' : 'user.username',},
        { 'data' : 'branch_name','name' : 'BL.branch_name',},
        { 'data' : 'email','name' : 'user.email',},
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
     
    });    
});
</script>
@endsection