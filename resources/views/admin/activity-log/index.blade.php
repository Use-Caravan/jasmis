@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
            @include('admin.layouts.partials._flashmsg')     
          <h1 class="box-title">@lang('admincrud.Activity Log')</h1>
          <div class="top-action hide" id="deleteAction">
            {{ Form::open(['url' => route('activity-log.destroy',['null']), 'id' => 'deleteForm', 'class' => 'form-horizontal', 'method' =>  'POST' ]) }}
                {{ Form::hidden('_method', 'DELETE') }}
                {{ Form::hidden('log_id', '',['id'=> 'log_ids']) }}
                <a class="trash btn mb15" title="@lang('admincommon.Are you sure?')" data-toggle="popover" data-placement="left" data-target="#delete_confirm" data-original-title="@lang('admincommon.Are you sure?')"><i class="fa fa-trash"></i>@lang('admincommon.Delete')</a> 
            {{ Form::close() }}
        </div>       
        </div> <!--box-header-->

        <div class="box-body">               
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                  <th width="20">
                        <input id="checkall" name="log_id[]" type="checkbox" class="hide">
                        <label for="checkall" class="checkbox"></label>
                  </th>
                  <th width="20">@lang('admincommon.S.No')</th>
                  {{-- <th>@lang('admincrud.Log Name')</th> --}}
                  <th>@lang('admincrud.Causer Name')</th>
                  <th>@lang('admincrud.Description')</th>                  
                  <th>@lang('admincrud.IP Address')</th>
                  <th>@lang('admincrud.Browser')</th>
                  <th>@lang('admincrud.Datetime')</th>                  
                  {{-- <th class="action">@lang('admincommon.Action')</th> --}}
              </tr>
              <tr>
                <th></th>
                <th></th>
                <th>
                    {{ Form::text("causer_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Causer Name'), "data-name" => "2"]) }}
                </th>
                <th>
                    {{ Form::text('description', '' ,['class' => 'form-control filterText', 'placeholder' => __('admincrud.Description'), "data-name" => "3"] )}}
                </th>
                <th>
                    {{ Form::text('ip_address', '' ,['class' => 'form-control filterText', 'placeholder' => __('admincrud.IP Address'), "data-name" => "4"] )}}
                </th>
                <th>
                    {{ Form::text('browser', '' ,['class' => 'form-control filterText', 'placeholder' => __('admincrud.Browser'), "data-name" => "5"] )}}
                </th>
                <th></th>                
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
        'ajax' : "{{ route('activity-log.index') }}",
        'columns': [
            { 'data' : 'checkbox','searchable' : false,'sortable':false},
            { 'data' : 'DT_RowIndex', 'name' : 'activitylog_id', 'searchable' : false},
            { 'data' : 'causer_name',},
            { 'data' : 'description',},        
            { 'data' : 'ip_address', 'name' : 'properties' },
            { 'data' : 'browser', 'name' : 'properties'},
            { 'data' : 'created_at',},                    
        ],
        "order": [[ 1, 'desc' ]],
    });    
    $('#checkall').click(function()
    {        
        $('.checkboxlog').not(this).prop('checked', this.checked);
        var logIds = [];
        $(".checkboxlog").each(function( index ) {
            if($( this ).prop('checked') == true){
                logIds.push($(this).val());
            }
        });
        if(logIds.length > 0) {
            $('#deleteAction').removeClass('hide');
        } else {
            $('#deleteAction').addClass('hide');
        }
        $('#log_ids').val(logIds);
    });
    $('body').on('click','.checkboxlog',function()
    {        
        var logIds = [];
        $(".checkboxlog").each(function( index ) {
            if($( this ).prop('checked') == false){
                $('#checkall').prop('checked', false);                
            }else{                
                logIds.push($(this).val());
            }                        
        }); 
        if(logIds.length > 0) {
            $('#deleteAction').removeClass('hide');
        } else {
            $('#deleteAction').addClass('hide');
        }       
        $('#log_ids').val(logIds);
    });    
});
</script>
@endsection