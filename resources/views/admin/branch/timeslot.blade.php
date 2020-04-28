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
          <h1 class="box-title">@lang('admincrud.TimeSlot Management')</h1>
        </div> <!--box-header-->

        <div class="box-body">          
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="categoryTable">
                <thead>
                    <tr>
                        <th rowspan="2">@lang('admincrud.Days')</th>                            
                        <th colspan="3" class="textCenter"> @lang('admincrud.Delivery Hours')</th>
                        <th colspan="3" class="textCenter"> @lang('admincrud.Take Away / DineIn Hours')</th>
                    </tr>
                    <tr>                            
                        <th class="text-info">@lang('admincrud.Start Time')</th>
                        <th class="text-info">@lang('admincrud.End Time')</th>
                        <th class="text-info">@lang('admincommon.Status')</th>
                        <th class="text-info">@lang('admincrud.Start Time')</th>
                        <th class="text-info">@lang('admincrud.End Time')</th>
                        <th class="text-info">@lang('admincommon.Status')</th>
                    </tr>
                </thead>                
                <tbody>
                    @foreach($slotType as $key => $value)
                    <tr id="">
                        <td>{{$value['day']}}</td>
                        <td>
                            <div class="form-group time_picker icon"> 
                                <input type="text" class="form-control timeslotpicker timeslotAction {{$key}}startTime{{ ORDER_TYPE_DELIVERY }}" branch_timeslot_key="{{ ($value[1] != null) ? $value[1]['branch_timeslot_key'] : '' }}" orderType="{{ ORDER_TYPE_DELIVERY }}" dayNo="{{$key}}" branchKey="{{ $branchKey }}" oldValue="{{ ($value[1] != null) ? $value[1]['start_time'] : '' }}" value="{{ ($value[1] != null) ? $value[1]['start_time'] : '' }}"><i class="fa fa-clock-o"></i>
                            </div>
                        </td>
                        <td>
                            <div class="form-group time_picker icon">
                                <input type="text" class="form-control timeslotpicker timeslotAction  {{$key}}endTime{{ ORDER_TYPE_DELIVERY }}" branch_timeslot_key="{{ ($value[1] != null) ? $value[1]['branch_timeslot_key'] : '' }}" orderType="{{ ORDER_TYPE_DELIVERY }}" dayNo="{{$key}}" branchKey="{{ $branchKey }}" oldValue="{{ ($value[1] != null) ? $value[1]['end_time'] : '' }}" value="{{ ($value[1] != null) ? $value[1]['end_time'] : '' }}"><i class="fa fa-clock-o"></i>
                            </div>
                        </td>
                        <td class=" status">
                            <label class="switch" for="id_{{ $key.ORDER_TYPE_DELIVERY }}">
                                <input type="checkbox" class="timeslotSwitch {{$key}}status{{ ORDER_TYPE_DELIVERY }}" branch_timeslot_key="{{ ($value[1] != null) ? $value[1]['branch_timeslot_key'] : '' }}" orderType="{{ ORDER_TYPE_DELIVERY }}" dayNo="{{$key}}"  branchKey="{{ $branchKey }}" id="id_{{ $key.ORDER_TYPE_DELIVERY }}" {{ ($value[1] != null && $value[1]["status"] == ITEM_ACTIVE) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <div class="form-group time_picker icon">
                                <input type="text" class="form-control timeslotpicker timeslotAction {{$key}}startTime{{ ORDER_TYPE_PICKUP_DINEIN }}" branch_timeslot_key="{{ ($value[2] != null) ? $value[2]['branch_timeslot_key'] : '' }}" orderType="{{ ORDER_TYPE_PICKUP_DINEIN }}" dayNo="{{$key}}"  branchKey="{{ $branchKey }}"  oldValue="{{ ($value[2] != null) ? $value[2]['start_time'] : '' }}" value="{{ ($value[2] != null) ? $value[2]['start_time'] : '' }}"><i class="fa fa-clock-o"></i>
                            </div>
                        </td>
                        <td>
                            <div class="form-group time_picker icon">
                                <input type="text" class="form-control timeslotpicker timeslotAction {{$key}}endTime{{ ORDER_TYPE_PICKUP_DINEIN }}" branch_timeslot_key="{{ ($value[2] != null) ? $value[2]['branch_timeslot_key'] : '' }}" orderType="{{ ORDER_TYPE_PICKUP_DINEIN }}" dayNo="{{$key}}"  branchKey="{{ $branchKey }}" oldValue="{{ ($value[2] != null) ? $value[2]['end_time'] : '' }}" value="{{ ($value[2] != null) ? $value[2]['end_time'] : '' }}"><i class="fa fa-clock-o"></i>
                            </div>
                        </td>
                        <td class=" status">
                            <label class="switch" for="id_{{$key.ORDER_TYPE_PICKUP_DINEIN}}">
                                <input type="checkbox" class="timeslotSwitch {{$key}}status{{ ORDER_TYPE_PICKUP_DINEIN }}" branch_timeslot_key="{{ ($value[2] != null) ? $value[2]['branch_timeslot_key'] : '' }}" orderType="{{ ORDER_TYPE_PICKUP_DINEIN }}" dayNo="{{$key}}"  branchKey="{{ $branchKey }}" id="id_{{$key.ORDER_TYPE_PICKUP_DINEIN}}" {{ ($value[2] != null && $value[2]["status"] == ITEM_ACTIVE) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr> 
                    @endforeach                    
                </tbody>
            </table>
          </div>
        </div> <!--box-body-->
    </div> <!--box-->
  </section>
</div> <!--content-wrapper-->
<script>    
$(document).ready(function()
{
    $('.timeslotpicker').datetimepicker({
        format: 'LT'
    }).on('dp.change', timeSlotPicker);
    $('.timeslotSwitch').on('change', timeSlotPicker);
});
function timeSlotPicker() {
    var branchKey = $(this).attr('branchKey');    
    var orderType = $(this).attr('orderType');
    var branchTimeSlotKey = $(this).attr('branch_timeslot_key');
    var dayNo = $(this).attr('dayNo');            
    var startTime = $('.'+dayNo+'startTime'+orderType).val();
    var endTime = $('.'+dayNo+'endTime'+orderType).val();
    var status = ($('.'+dayNo+'status'+orderType).prop("checked") == true) ? {{ ITEM_ACTIVE }} : {{ITEM_INACTIVE}} ;
    if(startTime == '' || endTime == '') {
        if($('.'+dayNo+'startTime'+orderType).attr('oldValue') != ''){
            $('.'+dayNo+'startTime'+orderType).val( $('.'+dayNo+'startTime'+orderType).attr('oldValue') );
        }        
        if($('.'+dayNo+'endTime'+orderType).attr('oldValue') != ''){
            $('.'+dayNo+'endTime'+orderType).val( $('.'+dayNo+'endTime'+orderType).attr('oldValue') );        
        }
        $('.'+dayNo+'status'+orderType). prop("checked", false);
        if(startTime == '' && endTime != '') {
            errorNotify(" {{ __('Start time and end time should not be empty') }} ");
        }        
        return false;
    }
    $.ajax({
        url: "{{ route('branch.timeslot') }}",
        type: 'post',
        data : { branchKey : branchKey, branch_timeslot_key: branchTimeSlotKey, timeslot_type : orderType, day_no : dayNo, start_time : startTime, end_time : endTime, status : status  },
        success: function(result) {
            if(result.status == AJAX_SUCCESS ) {
                if(result.data != null) {
                    $('.'+dayNo+'startTime'+orderType).attr('branch_timeslot_key',result.data.branch_timeslot_key);
                    $('.'+dayNo+'endTime'+orderType).attr('branch_timeslot_key',result.data.branch_timeslot_key);
                    $('.'+dayNo+'status'+orderType).attr('branch_timeslot_key',result.data.branch_timeslot_key);
                }
                $('.'+dayNo+'startTime'+orderType).attr('oldValue', $('.'+dayNo+'startTime'+orderType).val() );
                $('.'+dayNo+'endTime'+orderType).attr('oldValue', $('.'+dayNo+'endTime'+orderType).val() );
                successNotify(result.msg);
            } else {
                errorNotify(result.msg);
                $('.'+dayNo+'startTime'+orderType).val( $('.'+dayNo+'startTime'+orderType).attr('oldValue') );
                $('.'+dayNo+'endTime'+orderType).val( $('.'+dayNo+'endTime'+orderType).attr('oldValue') );
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if(jqXHR.status == {{AJAX_VALIDATION_ERROR_CODE}}){
            var errors = jqXHR.responseJSON.errors;
            var message = '';
                $.each(errors, function (key, val) {                            
                    $.each(val, function (ikey, ival) {
                        message += ival+"<br/>";
                    });
                });
                if($('.'+dayNo+'startTime'+orderType).attr('oldValue') != ''){
                    $('.'+dayNo+'startTime'+orderType).val( $('.'+dayNo+'startTime'+orderType).attr('oldValue') );
                }        
                if($('.'+dayNo+'endTime'+orderType).attr('oldValue') != ''){
                    $('.'+dayNo+'endTime'+orderType).val( $('.'+dayNo+'endTime'+orderType).attr('oldValue') );        
                }                
                errorNotify(message);
            }
        }
    }); 
}
</script>
@endsection