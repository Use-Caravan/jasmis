@if($component_type === 1)
<div class="form-group time_picker trigger_data icon {{($trip_type == 1) ? $day_no.'-delivery' : $day_no.'-pickup' }}" branch_timeslot_key="{{ $slot_value['branch_timeslot_key'] }}" trip_type="{{$trip_type}}">
    <input type="text" {{$column}}="true" class="form-control timeslotpicker"  value="{{$slot_value[$column]}}"><i class="fa fa-clock-o"></i>
</div>
@else
<div class="form-group">
<label class="trigger_data switch {{ ($trip_type == 1) ? $day_no.'-delivery' : $day_no.'-pickup' }}" for="id_{{($trip_type == 1) ? $slotKey.'_'.$day_no.'-delivery' : $slotKey.'_'.$day_no.'-pickup' }}"  branch_timeslot_key="{{ $slot_value['branch_timeslot_key'] }}" trip_type="{{$trip_type}}">
    <input type="checkbox" {{$column}}="true" class="timeslotSwitch status"  id="id_{{($trip_type == 1) ? $slotKey.'_'.$day_no.'-delivery' : $slotKey.'_'.$day_no.'-pickup' }}" {{ $slot_value[$column] ? 'checked' : '' }} >
    <span class="slider"></span>
</label>
@if($slotKey > 0)
<button class="addrow" position="{{ ($trip_type == 1) ? $day_no.'-delivery' : $day_no.'-pickup' }}" trip_type="{{$trip_type}}" unique_key={{$slotKey}} day_no={{$day_no}}>
    <i class="fa fa-remove"></i>
</button>
@endif

@if($arrayLength === $slotKey)
<button class="addrow" position="{{ ($trip_type == 1) ? $day_no.'-delivery' : $day_no.'-pickup' }}" trip_type="{{$trip_type}}" unique_key={{$slotKey}} day_no={{$day_no}}>
    <i class="fa fa-plus"></i>
</button>
@endif
</div>
@endif