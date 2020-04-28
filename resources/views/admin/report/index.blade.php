@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        @include('admin.layouts.partials._flashmsg')
        <h1 class="box-title">@lang('admincrud.Report Management')</h1>
            <div class="top-action">
                <a exporthref="{{ route('report-export') }}" title="@lang('admincrud.Export')" class="btn mb15" id="report_export"><i class="fa fa-file-excel-o"></i>@lang('admincrud.Export')</a>
                {{--<button class="btn mb15" id="report_export"><i class="fa fa-file-excel-o"></i>@lang('admincrud.Export')</button>--}}
            </div>
        </div> <!--box-header-->
        
        <div class="box-body report_fil">
            {{ Form::open(['route' => 'report.index', 'id' => 'report-filter', 'class' => 'form-horizontal', 'method' => 'GET'])}}
            <div class="row">
                <div class="col-md-3">    
                    @if(APP_GUARD == GUARD_ADMIN)
                        {{ Form::select('vendor_id',$vendorList, request()->vendor_id ,['class' => 'selectpicker filterSelect','id' => 'vendor', 'placeholder' => __('admincrud.Vendor Name')] )}} 
                    @endif
                </div>
                <div class="col-md-3">    
                    @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                        {{ Form::select('branch_id', $branchList, request()->branch_id ,['class' => 'selectpicker filterSelect', 'id' => 'Vendor-branch_id','placeholder' => __('admincrud.Branch Name')])}} 
                    @endif
                </div>
                <div class="col-md-3">    
                    {{ Form::text('order_number',request()->order_number,['class' => 'form-control', 'placeholder' => __('admincrud.Order Number')] )}} 
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    {{ Form::text("from_date",request()->from_date, [ 'class' => 'form-control date_picker','id'=>'','placeholder' => __('admincrud.From Date'), "autocomplete" => "off"]) }} 
                </div>
                <div class="col-md-3">
                    {{ Form::text("to_date",request()->to_date, [ 'class' => 'form-control date_picker','id'=>'','placeholder' => __('admincrud.To Date') ,"autocomplete" => "off"]) }} 
                </div>
                <div class="col-md-3">
                    {{ Form::select('order_status', $modelOrder->approvedStatus(), request()->order_status ,['class' => 'selectpicker', 'id' => 'order_id','placeholder' => __('admincrud.Order Status')])}}     
                </div>
                <div class="col-md-3">
                    <div class="top-action">
                        <button value="submit" class="btn mb15"><i class="fa fa-plus-circle"></i>@lang('admincommon.Filter')</button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
         <div class="table-responsive">
            <table class="table table-bordered table-striped" id="reportTable">
                <thead>
                    <tr>
                        <th width="20">@lang('admincommon.S.No')</th>
                        <th>@lang('admincrud.Order Number')</th>
                        @if(APP_GUARD == GUARD_ADMIN)
                        <th>@lang('admincrud.Vendor Name')</th>
                        @endif
                        @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                        <th>@lang('admincrud.Branch Name')</th>
                        @endif
                        <th>@lang('admincrud.Order Date Time')</th>
                        <th>@lang('admincrud.Order Status')</th> 
                        <th class="action">@lang('admincommon.Action')</th>
                    </tr>
                    @foreach($model as $key => $value)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$value->order_number}}</td>
                        @if(APP_GUARD == GUARD_ADMIN)
                        <td>{{$value->vendor_name}}</td>
                        @endif
                        @if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR)
                        <td>{{$value->branch_name}}</td>
                        @endif
                        <td>{{$value->order_datetime}}</td>
                        <td>{{$modelOrder->approvedStatus($value->order_status)}}</td> 
                        <td class="action">
                            <a href ="{{route('report.show',['id' => $value->order_key])}}" ><i class="fa fa-eye"></i></a>
                            {{--{{ Form::open(['route' => ['report.destroy',$value->order_key], 'id' => 'report-delete', 'class' => 'form-horizontal', 'method' => 'DELETE'])}} 
                                <button><i class="fa fa-trash"></i></button>
                            {{Form::close()}} --}}
                        </td>
                    </tr>
                @endforeach
                </thead>
            </table>
            <div class="pull-right">
                {{$model->links()}}
            </div>
          </div>
        </div> <!--box-body-->
    </div> <!--box-->
  </section>
</div> <!--content-wrapper-->
<!-- modal_medium -->
<div id="modal_medium" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Drivers List</h4>
                <i class="fa fa-close" data-dismiss="modal"></i>
            </div><!--modal-header-->            
            <div class="modal-body full_row" id="drivers_list">
                
            </div> <!--modal-body-->
        </div><!--modal-content-->
    </div><!--modal-dialog-->
</div><!--modal_medium-->
@include('admin.layouts.partials._tableconfig')
<script type="text/javascript">
$(document).ready(function(){
    $('.date_picker').datetimepicker({
        format: 'DD-MM-YYYY'
    });
     
    $('#report_export').on('click',function() {
        var action = $(this).attr('exporthref');        
        $('#report-filter').attr('action',action);
        $('#report-filter').submit();
    });
    $('#vendor').on('change',function() {
        $vendorId = $(this).val();
        $.ajax({
            url: "{{ route('get-branch-by-vendor') }}",
            type: 'post',
            data: {vendor_id:$vendorId},
            success: function(result){ 
                if(result.status == AJAX_SUCCESS){
                    $('#Vendor-branch_id').html('');                    
                     $('#Vendor-branch_id').append($('<option>', { value : "" }).text("All"));
                    $.each(result.data,function(key,title)
                    {  
                        $('#Vendor-branch_id').append($('<option>', { value : key }).text(title));                       
                    });                    
                    $('.selectpicker').selectpicker('refresh');
                    
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    });
});
</script>
@endsection