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
        <input type="hidden" value="{{ $branch->branch_key }}" name="branch_key">
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
                        @php 
                            $deliveryStartHtml = '';
                            $deliveryEndHtml = '';
                            $deliveryStatusHtml = '';
                            $pickupStartHtml = '';
                            $pickupEndHtml = '';
                            $pickupStautsHtml = '';
                        @endphp
                        
                        @foreach($value['timeslots']['delivery'] as $slotKey => $slotValue)
                            @php
                                $arrayLength = array_key_last($value['timeslots']['delivery']);
                                $deliveryStartHtml .= view('admin.branch.components.time_picker',[
                                    'component_type' => 1,
                                    'column' => 'start_time',
                                    'trip_type' => ORDER_TYPE_DELIVERY,
                                    'day_no' => $value['day_no'],
                                    'slot_value' => $slotValue,
                                    'slotKey' => $slotKey,
                                    'arrayLength' => $arrayLength
                                ])->render();
                                $deliveryEndHtml .= view('admin.branch.components.time_picker',[
                                    'component_type' => 1,
                                    'column' => 'end_time',
                                    'trip_type' => ORDER_TYPE_DELIVERY,
                                    'day_no' => $value['day_no'],
                                    'slot_value' => $slotValue,
                                    'slotKey' => $slotKey,
                                    'arrayLength' => $arrayLength
                                ])->render();
                                $deliveryStatusHtml .= view('admin.branch.components.time_picker',[
                                    'component_type' => 0,
                                    'column' => 'status',
                                    'trip_type' => ORDER_TYPE_DELIVERY,
                                    'day_no' => $value['day_no'],
                                    'slot_value' => $slotValue,
                                    'slotKey' => $slotKey,
                                    'arrayLength' => $arrayLength
                                ])->render();
                            @endphp
                        @endforeach

                        @foreach($value['timeslots']['pickup'] as $slotKey => $slotValue)
                            @php                                
                                $pickupStartHtml .= view('admin.branch.components.time_picker',[
                                    'component_type' => 1,
                                    'column' => 'start_time',
                                    'trip_type' => ORDER_TYPE_PICKUP_DINEIN,
                                    'day_no' => $value['day_no'],
                                    'slot_value' => $slotValue,
                                    'slotKey' => $slotKey,
                                    'arrayLength' => $arrayLength
                                ])->render();                                    
                                $pickupEndHtml .= view('admin.branch.components.time_picker',[
                                    'component_type' => 1,
                                    'column' => 'end_time',
                                    'trip_type' => ORDER_TYPE_PICKUP_DINEIN,
                                    'day_no' => $value['day_no'],
                                    'slot_value' => $slotValue,
                                    'slotKey' => $slotKey,
                                    'arrayLength' => $arrayLength
                                ])->render();
                                $pickupStautsHtml .= view('admin.branch.components.time_picker',[
                                    'component_type' => 0,
                                    'column' => 'status',
                                    'trip_type' => ORDER_TYPE_PICKUP_DINEIN,
                                    'day_no' => $value['day_no'],
                                    'slot_value' => $slotValue,
                                    'slotKey' => $slotKey,
                                    'arrayLength' => $arrayLength
                                ])->render();
                            @endphp
                        @endforeach
                    <tr day_no="{{ $value['day_no'] }}">
                        <td>{{$value['day_name']}}</td>
                        <td id="td_{{ $value['day_no'].'-delivery-start_time' }}">
                            {!! $deliveryStartHtml !!}
                        </td>
                        <td id="td_{{ $value['day_no'].'-delivery-end_time' }}">
                            {!! $deliveryEndHtml !!}
                        </td>
                        <td class="status"  id="td_{{ $value['day_no'].'-delivery-status' }}">
                            {!! $deliveryStatusHtml !!}
                        </td>
                        <td id="td_{{ $value['day_no'].'-pickup-start_time' }}">
                            {!! $pickupStartHtml !!}
                        </td>
                        <td id="td_{{ $value['day_no'].'-pickup-end_time' }}">
                            {!! $pickupEndHtml !!}
                        </td>
                        <td class=" status" id="td_{{ $value['day_no'].'-pickup-status' }}">
                            {!! $pickupStautsHtml !!}
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
<script id="comment-template" type="text/x-lodash-template">
    <% if(component_type === 1) { %>
        <div class="form-group time_picker trigger_data icon <%= (trip_type == 1) ? day_no+'-delivery' : day_no+'-pickup' %>" branch_timeslot_key="" trip_type="<%= trip_type %>">
            <input type="text" <%= column %>="true" class="form-control timeslotpicker"  value=""><i class="fa fa-clock-o"></i>
        </div>
    <% } else { %>
        <div class="form-group">
        <label class="trigger_data switch <% (trip_type == 1) ? day_no+'-delivery' : day_no+'-pickup' %>" for="id_<%= (trip_type == 1) ? day_no+'-delivery' : day_no+'-pickup' %>"  branch_timeslot_key="" trip_type="<%= trip_type %>">
            <input type="checkbox" <%= column %>="true" class="timeslotSwitch status"  id="id_<%= (trip_type == 1) ? unique_key+'_'+day_no+'-delivery' : day_no+'-pickup' %>">
            <span class="slider"></span>
        </label>
        <button class="addrow" position="<%= (trip_type == 1) ? day_no+'-delivery' : day_no+'-pickup' %>">
            <i class="fa fa-plus"></i>
        </button>
        <button class="removerow" position="<%= (trip_type == 1) ? day_no+'-delivery' : day_no+'-pickup' %>">
            <i class="fa fa-plus"></i>
        </button>
        <div>
    <% } %>
  </script>
<script>    
$(document).ready(function()
{
    reloadDatePicker();
    $('body').on('click','.addrow',function() {
        //['day_no' => $value['day_no'], 'slot_value' => $slotValue]
        let selector = $(this).attr('position');
        let trip_type = $(this).attr('trip_type');
        let day_no = $(this).attr('day_no');
        let unique_key = $(this).attr('unique_key');
        var commentTemplate = document.getElementById("comment-template").innerHTML;
        var templateFn = _.template(commentTemplate);        
        var start_time = templateFn({ component_type: 1, column: "start_time", 'trip_type': trip_type,'day_no': day_no, "unique_key": unique_key  });
        var end_time = templateFn({ component_type: 1, column: "end_time", 'trip_type': trip_type,'day_no': day_no, "unique_key": unique_key  });
        var status = templateFn({ component_type: 0, column: "status", 'trip_type': trip_type,'day_no': day_no, "unique_key": unique_key  });
        $('#td_'+selector+'-start_time').append(start_time);
        $('#td_'+selector+'-end_time').append(end_time);
        $('#td_'+selector+'-status').append(status);
        reloadDatePicker();
    });    
});
function reloadDatePicker()
{
    $('.timeslotpicker').datetimepicker({
        format: 'LT'
    }).on('dp.change', timeSlotPicker);
    $('.timeslotSwitch').on('change', timeSlotPicker);
}
function timeSlotPicker() {
    var branchKey = $('[name=branch_key]').val();
    var branchTimeSlotKey = $(this).closest('.trigger_data').attr('branch_timeslot_key');
    var orderType = $(this).closest('.trigger_data').attr('trip_type');
    var orderTypeText = $(this).closest('.trigger_data').attr('trip_type') == 1 ? 'delivery' : 'pickup' ;
    var dayNo = $(this).closest('tr').attr('day_no');
    var startTime = $('.'+dayNo+'-'+orderTypeText+' [start_time=true]').val();
    var endTime = $('.'+dayNo+'-'+orderTypeText+' [end_time=true]').val();
    var status = $('.'+dayNo+'-'+orderTypeText+' [status=true]').prop('checked') == true ? 1 : 0;
    
    if(startTime == '' || endTime == '') {
        /* if($('.'+dayNo+'startTime'+orderType).attr('oldValue') != ''){
            $('.'+dayNo+'startTime'+orderType).val( $('.'+dayNo+'startTime'+orderType).attr('oldValue') );
        }        
        if($('.'+dayNo+'endTime'+orderType).attr('oldValue') != ''){
            $('.'+dayNo+'endTime'+orderType).val( $('.'+dayNo+'endTime'+orderType).attr('oldValue') );        
        }
        $('.'+dayNo+'status'+orderType). prop("checked", false); */
        if(startTime == '' && endTime != '') {
            errorNotify(" {{ __('Start time and end time should not be empty') }} ");
        }        
        return false;
    }

    let slot_data = {
        branch_key: branchKey,        
        branch_timeslot_key: branchTimeSlotKey,
        timeslot_type: orderType,
        day_no: dayNo,
        start_time: startTime,
        end_time: endTime,
        status: status
    };
    
    $.ajax({
        url: "{{ route('branch.timeslot-new') }}",
        type: 'post',
        data : slot_data,
        success: function(result) {
            if(result.status == AJAX_SUCCESS ) {
                /* if(result.data != null) {
                    $('.'+dayNo+'startTime'+orderType).attr('branch_timeslot_key',result.data.branch_timeslot_key);
                    $('.'+dayNo+'endTime'+orderType).attr('branch_timeslot_key',result.data.branch_timeslot_key);
                    $('.'+dayNo+'status'+orderType).attr('branch_timeslot_key',result.data.branch_timeslot_key);
                }
                $('.'+dayNo+'startTime'+orderType).attr('oldValue', $('.'+dayNo+'startTime'+orderType).val() );
                $('.'+dayNo+'endTime'+orderType).attr('oldValue', $('.'+dayNo+'endTime'+orderType).val() ); */
                successNotify(result.msg);
            } else {
                errorNotify(result.msg);
                /* $('.'+dayNo+'startTime'+orderType).val( $('.'+dayNo+'startTime'+orderType).attr('oldValue') );
                $('.'+dayNo+'endTime'+orderType).val( $('.'+dayNo+'endTime'+orderType).attr('oldValue') ); */
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
                /* if($('.'+dayNo+'startTime'+orderType).attr('oldValue') != ''){
                    $('.'+dayNo+'startTime'+orderType).val( $('.'+dayNo+'startTime'+orderType).attr('oldValue') );
                }        
                if($('.'+dayNo+'endTime'+orderType).attr('oldValue') != ''){
                    $('.'+dayNo+'endTime'+orderType).val( $('.'+dayNo+'endTime'+orderType).attr('oldValue') );        
                } */
                errorNotify(message);
            }
        }
    });     
}
</script>
@endsection