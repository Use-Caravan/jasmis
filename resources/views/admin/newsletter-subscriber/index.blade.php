@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
          <h1 class="box-title">@lang('admincrud.Newsletter Subscriber Management')</h1>
          <div class="top-action">
                <a href="{!! route('newsletter-sendmail') !!}" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincrud.Send Newsletter')</a>            
                <a href="{!! route('newsletter-export') !!}" title="@lang('admincrud.Export')" class="btn mb15"><i class="fa fa-file-excel-o"></i>@lang('admincrud.Export')</a>
            </div>
        </div> <!--box-header-->

        <div class="box-body">
          

          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
                <tr>
                    <th width="20">@lang('admincommon.S.No')</th>
                    <th>@lang('admincommon.Name')</th>
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
                        {{ Form::text("email", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Email'), "data-name" => "2"]) }}                         
                    </th>
                    <th class="status">
                        {{ Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "2"] )}}
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
        'ajax' : "{{ route('newsletter-subscriber.index') }}",
        'columns': [
            { 'data' : 'DT_RowIndex', 'name' : 'newsletter_subscriber_id', 'searchable' : false},
            { 'data' : 'name'},
            { 'data' : 'email'},
            { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
            { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
        ],
      
    });    
});
</script>
@endsection