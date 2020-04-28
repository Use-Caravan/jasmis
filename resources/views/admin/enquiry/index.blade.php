@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
          <div class="flash-message">
            @if(Session::has('success'))
              <p class="alert alert-success">
                {{ Session('success') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              </p>
            @endif            
          </div> <!-- end .flash-message -->
          <h1 class="box-title">@lang('admincrud.Enquiry Management')</h1>
        </div> <!--box-header-->

        <div class="box-body">
          
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
                <tr>
                    <th width="20">@lang('admincommon.S.No')</th>
                    <th>@lang('admincommon.First Name')</th>
                    {{-- <th>@lang('admincommon.Last Name')</th> --}}
                    <th>@lang('admincommon.Email')</th>
                    <th>@lang('admincommon.Phone Number')</th>
                   {{-- <th>@lang('admincrud.Subject')</th>--}}
                    <th>@lang('admincrud.Comments')</th>
                    <th class="status">@lang('admincommon.Status')</th>
                    <th class="action">@lang('admincommon.Action')</th>
                </tr>
                <tr>
                    <th></th>
                    <th>
                        {{ Form::text("first_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.First Name'), "data-name" => "1"]) }}                         
                    </th>
                    {{-- <th>
                        {{ Form::text("last_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Last Name'), "data-name" => "2"]) }}                         
                    </th> --}}
                    <th>
                        {{ Form::text("email", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Email'), "data-name" => "2"]) }}                         
                    </th>
                    <th>
                        {{ Form::text("phone_number", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Phone Number'), "data-name" => "3"]) }}                         
                    </th>
                   {{--  <th>
                        {{ Form::text("subject", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Subject'), "data-name" => "5"]) }}                         
                    </th> --}}
                    <th>
                        {{ Form::text("comments", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Comments'), "data-name" => "4"]) }}                         
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
        'ajax' : "{{ route('enquiry.index') }}",
        'columns': [
            { 'data' : 'DT_RowIndex', 'name' : 'enquiry_id', 'searchable' : false},
            { 'data' : 'first_name'},
           /*  { 'data' : 'last_name'}, */
            { 'data' : 'email'},
            { 'data' : 'phone_number'},
           /*  { 'data' : 'subject'}, */
            { 'data' : 'comments'},
            { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
            { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
        ],
        
    });     
});
</script>
@endsection