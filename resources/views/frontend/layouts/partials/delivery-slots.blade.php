<!-- group elements -->
@if($delivery_type == 2)
<div class="dl_group">
    <h4 class="heading_border fadeInUp">Delivery type</h4>
    <div class="full_row fadeInUp">                
        <input type="radio" class="radio" name="delivery_type" value="{{DELIVERY_TYPE_ASAP}}" id="DT{{DELIVERY_TYPE_ASAP}}">
        <label class="radio" for="DT{{DELIVERY_TYPE_ASAP}}">ASAP</label>                
        <input type="radio" class="radio" name="delivery_type" value="{{DELIVERY_TYPE_PRE_ORDER}}" id="DT{{DELIVERY_TYPE_PRE_ORDER}}">
        <label class="radio" for="DT{{DELIVERY_TYPE_PRE_ORDER}}">Pre Order</label>        
    </div>
</div>
@endif
@if(empty($days) || $days == null)
Service is not available
@else 
<!-- group elements -->
<div class="dl_group" id="pre_order_div" style="{{ ($delivery_type == 2) ? 'display:none;' : '' }}">
    <h4 class="heading_border fadeInUp">Date & Time</h4>
    <div class="row fadeInUp">
        <div class="col-md-6">
            <div class="form-group">
                <label class="icons"><i class="material-icons">date_range</i></label>
                <select class="form-control" placeholder="Selected Date" prop="date" name="delivery_date" id="delivery_date">
                    <option>Select Date</option>
                    @foreach($days as $key => $value)
                        <option value="{{$value['date']}}">{{$value['date']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="icons"><i class="material-icons">access_time</i></label>
                <select class="form-control" name="delivery_time" id="delivery_time" prop="time" placeholder="Selected Time">
                    <option>Select Time</option>
                    @foreach($days as $key => $value)
                        @foreach($value['times'] as $time)                            
                            <option style="display:none" data-date="{{$value['date']}}" value="{{$time}}">{{$time}}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<!-- group elements -->
@endif