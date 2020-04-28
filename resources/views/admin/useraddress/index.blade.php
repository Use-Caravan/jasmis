@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
        <h1 class="box-title">@lang('admincrud.User Address Management')</h1>
            
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th width="20">@lang('admincommon.S.No')</th>
                        <th>@lang('admincommon.Name')</th>
                        <th>@lang('admincommon.User Name')</th>
                        <th>@lang('admincrud.Address Type')</th>
                        <th>@lang('admincommon.Address')</th>
                        <th>@lang('admincommon.Email')</th>
                        <th>@lang('admincrud.Landmark')</th>
                        <th>@lang('admincrud.Company')</th>
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
                            {{ Form::text("address_type_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Address Type'), "data-name" => "3"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("address", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Address'), "data-name" => "4"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("email", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Email'), "data-name" => "5"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("landmark", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Landmark'), "data-name" => "6"]) }}                         
                        </th>
                        <th>
                            {{ Form::text("company", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Company'), "data-name" => "7"]) }}                         
                        </th>                        
                        <th class="status">
                            {{ Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "8"] )}}
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
      'ajax' : "{{ route('useraddress.index') }}",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'user_address_id', 'searchable' : false},
        { 'data' : 'name','name' : 'user.first_name',},
        { 'data' : 'username','name' : 'user.username',},
        { 'data' : 'address_type_name','name' : 'ATL.address_type_name',},
        { 'data' : 'address','name' : 'address_line_one',},
        { 'data' : 'email','name' : 'user.email'}, 
        { 'data' : 'landmark'},
        { 'data' : 'company'},
        
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
     
    }); 
});
</script>
@endsection