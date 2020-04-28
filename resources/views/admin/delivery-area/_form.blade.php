
{{ Form::open(['url' => $url, 'id' => 'delivery-area-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">
       
        <div class="col-md-4">
            <div class="form-group {{ ($errors->has("country_id")) ? 'has-error' : '' }}">            
            {{ Form::label("country_id", __('admincrud.Country Name'), ['class' => 'required']) }} 
            {{ Form::select('country_id', $countryList, $model->country_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Country'),'id'=>'DeliveryArea-country_id'] )}}
                @if($errors->has("country_id"))
                    <span class="help-block error-help-block">{{ $errors->first("country_id") }}</span>
                @endif 
            </div>
        </div>   
        <div class="col-md-4">
            <div class="form-group {{ ($errors->has("city_id")) ? 'has-error' : '' }}">            
            {{ Form::label("city_id", __('admincrud.City Name'), ['class' => 'required']) }} 
            {{ Form::select('city_id', $cityList, $model->city_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose City'),'id' => 'DeliveryArea-city_id'] )}}
                @if($errors->has("city_id"))
                    <span class="help-block error-help-block">{{ $errors->first("city_id") }}</span>
                @endif 
            </div>
        </div>             
        <div class="col-md-4">
            <div class="form-group {{ ($errors->has("area_id")) ? 'has-error' : '' }}">            
            {{ Form::label("area_id", __('admincrud.Area Name'), ['class' => 'required']) }} 
            {{ Form::select('area_id', $areaList, $model->area_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Area'),'id' => 'DeliveryArea-area_id'] )}}
                @if($errors->has("area_id"))
                    <span class="help-block error-help-block">{{ $errors->first("area_id") }}</span>
                @endif 
            </div>
        </div>             
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{$errors->has("delivery_area_name.$key")}}"> 
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content full_row">            
            @foreach ($languages as $key => $language)
                <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                    <div class="form-group {{ ($errors->has("delivery_area_name.$key")) ? 'has-error' : '' }}" >
                        <div class="col-md-12">
                            {{$modelLang[$key]}}
                            {{ Form::label("delivery_area_name[$key]", __('admincrud.Delivery Area'), ['class' => 'required']) }}
                            {{ Form::text("delivery_area_name[$key]", $modelLang['delivery_area_name'][$key], ['class' => 'form-control']) }} 
                            @if($errors->has("delivery_area_name.$key"))
                                <span class="help-block error-help-block">{{ $errors->first("delivery_area_name.$key") }}</span>
                            @endif                    
                        </div>
                    </div>                    
                </div>                
            @endforeach
        </div> <!--tab-pane-->  
        @php
            $model->zone_type = (!$model->exists) ?  DELIVERY_AREA_ZONE_CIRCLE : $model->zone_type;
        @endphp
        {{ Form::hidden('zone_type',$model->zone_type,[ 'id' => 'zone_type' ]) }}
        {{ Form::hidden('circle_latitude',$model->circle_latitude,[ 'id' => 'circle_latitude', 'class' => 'area_latitude' ]) }}
        {{ Form::hidden('circle_longitude',$model->circle_longitude,[ 'id' => 'circle_longitude', 'class' => 'area_longitude' ]) }}
        {{ Form::hidden('zone_radius',$model->zone_radius,[ 'id' => 'zone_radius' ]) }}
        {{ Form::hidden('zone_latlng', '' ,[ 'id' => 'zone_latlng' ]) }}
        <div class="col-md-12">
            <input id="places_autocomplete" class="form-control" type="text" placeholder="@lang('admincrud.Search your location')">
            <div id="map" style="height:500px;"></div>
            @if(old('zone_type') == DELIVERY_AREA_ZONE_CIRCLE && ( $errors->has("circle_latitude") || $errors->has("circle_longitude")  || $errors->has("zone_radius") ) )
                <span class="help-block error-help-block">Please create delivery circel</span>
            @endif
            @if(old('zone_type') == DELIVERY_AREA_ZONE_POLYGON && $errors->has("zone_latlng"))
                <span class="help-block error-help-block">Please create delivery polygon</span>
            @endif
        </div>
        <div class="clearfix"></div><br/>
        <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                @php $model->status = ($model->exists) ? $model->status : ITEM_ACTIVE @endphp

                {{ Form::label("status", __('admincommon.Status'), ['class' => 'required']) }}

                {{ Form::radio('status', ITEM_ACTIVE, ($model->status == ITEM_ACTIVE), ['class' => 'hide','id'=> 'statuson' ]) }}
                {{ Form::label("statuson", __('admincommon.Active'), ['class' => ' radio']) }}

                {{ Form::radio('status', ITEM_INACTIVE, ($model->status == ITEM_INACTIVE), ['class' => 'hide','id'=>'statusoff']) }}
                {{ Form::label("statusoff", __('admincommon.Inactive'), ['class' => 'radio']) }}
                @if($errors->has("status"))
                    <span class="help-block error-help-block">{{ $errors->first("status") }}</span>
                @endif                    
            </div>
        </div>  
    </div>
  <!-- /.box-body -->
    <div class="box-footer">
        {{ Html::link(route('delivery-area.index'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\DeliveryAreaRequest', '#delivery-area-form')  !!}

<script type="text/javascript">
$(document).ready(function(){    
    $('#DeliveryArea-country_id').change(function()
    { 
        $.ajax({
            url: "{{ route('city-by-country') }}",
            type: 'post',
            data: {country_id: $(this).val()},
            success: function(result){ 
                if(result.status == AJAX_SUCCESS){
                    $('#DeliveryArea-city_id').html('');
                    $.each(result.data,function(key,title)
                    {  
                        $('#DeliveryArea-city_id').append($('<option>', { value : key }).text(title));                       
                    });
                   loadArea($('#DeliveryArea-city_id').val());
                    $('.selectpicker').selectpicker('refresh');
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    });

    $('#DeliveryArea-city_id').change(function()
    {  
        loadArea($(this).val());
    });
    function loadArea(cityId)
    {    
         $.ajax({
            url: "{{ route('area-by-city') }}",
            type: 'post',
            data: {city_id: cityId},
            success: function(result){   
                if(result.status == AJAX_SUCCESS){
            $('#DeliveryArea-area_id').html('');
                    $.each(result.data,function(key,title)
                    {  
                        $('#DeliveryArea-area_id').append($('<option>', { value : key }).text(title));
                    });
                    $('.selectpicker').selectpicker('refresh');                                                               
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });

    }
});
</script>
@include('admin.delivery-area._map-script')

