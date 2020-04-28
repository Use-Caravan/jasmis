{{ Form::open(['url' => $url, 'id' => 'area-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">        
        <div class="form-group {{ ($errors->has("country_id")) ? 'has-error' : '' }}">
            <div class="col-md-12">
            {{ Form::label("country_id", __('admincrud.Country Name'), ['class' => 'required']) }} 
            {{ Form::select('country_id', $countryList, $model->country_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Country'),'id'=>'Area-country_id'] )}}
                @if($errors->has("country_id"))
                    <span class="help-block error-help-block">{{ $errors->first("country_id") }}</span>
                @endif 
            </div>
        </div>   
        <div class="form-group {{ ($errors->has("city_id")) ? 'has-error' : '' }}">
            <div class="col-md-12">
            {{ Form::label("city_id", __('admincrud.City Name'), ['class' => 'required']) }} 
            {{ Form::select('city_id', $cityList, $model->city_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose City'),'id' => 'Area-city_id'] )}}
                @if($errors->has("city_id"))
                    <span class="help-block error-help-block">{{ $errors->first("city_id") }}</span>
                @endif 
            </div>
        </div>     
        
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{$errors->has("area_name.$key")}}"> 
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content full_row">            
            @foreach ($languages as $key => $language)
                <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                    <div class="form-group {{ ($errors->has("area_name.$key")) ? 'has-error' : '' }}" >
                        <div class="col-md-12">
                            {{ Form::label("area_name[$key]", __('admincrud.Area Name'), ['class' => 'required']) }}
                            {{ Form::text("area_name[$key]", $modelLang['area_name'][$key], ['class' => 'form-control']) }} 
                            @if($errors->has("area_name.$key"))
                                <span class="help-block error-help-block">{{ $errors->first("area_name.$key") }}</span>
                            @endif                    
                        </div>
                    </div>                    
                </div>                
            @endforeach
        </div> <!--tab-pane-->                        
        <div class="col-m-12">
            <input id="places_autocomplete" class="form-control" type="text" placeholder="@lang('admincrud.Search your location')">
            <div id="map" style="height:250px;"></div>
        </div>
        <div class="form-group {{ ($errors->has("latitude")) ? 'has-error' : '' }}">                    
            <div class="col-md-12" id="lat">                          
                {{ Form::label("latitude", __('admincrud.Latitude'), ['class' => 'required']) }}
                {{ Form::text("latitude", $model['latitude'], ['class' => 'form-control area_latitude','readonly']) }} 
                @if($errors->has("latitude"))
                    <span class="help-block error-help-block">{{ $errors->first("latitude.$key") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("longitude")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("longitude", __('admincrud.Longitude'), ['class' => 'required']) }}
                {{ Form::text("longitude", $model['longitude'], ['class' => 'form-control area_longitude','readonly']) }} 
                @if($errors->has("longitude"))
                    <span class="help-block error-help-block">{{ $errors->first("longitude") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">
                {{ Form::label("status", __('admincommon.Status'),['class' => 'required']) }}                                          
                @php $model->status = ($model->exists) ? $model->status : ITEM_ACTIVE @endphp
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
        {{ Html::link(route('area.index'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\AreaRequest', '#area-form')  !!}

<script type="text/javascript">
$(document).ready(function(){
    $('#Area-country_id').change(function()
    {
        $.ajax({
            url: "{{ route('city-by-country') }}",
            type: 'post',
            data: {country_id: $('#Area-country_id').val()},
            success: function(result){                
                if(result.status == AJAX_SUCCESS){
                    $.each(result.data,function(key,title)
                    {  
                        $('#Area-city_id').append($('<option>', { value : key }).text(title));                       
                    });                                                                   
                    $('.selectpicker').selectpicker('refresh');
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    });   
});
function initMap() {

    var latitude = $('.area_latitude').val();
    var longitude = $('.area_longitude').val();
    if(latitude != '' && longitude != ''){
        var latlng = new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
    }else{
        var latlng = new google.maps.LatLng({{ config('webconfig.app_latitude') }}, {{ config('webconfig.app_longitude') }});
    }
    var map = new google.maps.Map(document.getElementById('map'), {
        center: latlng,
        zoom: 13,
        mapTypeId: 'roadmap'
    });
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,            
        draggable: true,
        animation: google.maps.Animation.DROP    
    });
    google.maps.event.addListener(marker, 'dragend', function(event) 
    {
        $('.area_latitude').val(event.latLng.lat());
        $('.area_longitude').val(event.latLng.lng());        
    });    
    // Create the search box and link it to the UI element.
    var input = document.getElementById('places_autocomplete');        
    var options = {
        componentRestrictions: {country: "bh"}
    };
    var autocomplete = new google.maps.places.Autocomplete(input,options);  
    autocomplete.addListener('place_changed', function() {        
        var place = autocomplete.getPlace();
        console.log(place);
        
        marker.setPosition(place.geometry.location);
        map.setCenter(place.geometry.location);        
        $('.area_latitude').val(place.geometry.location.lat());
        $('.area_longitude').val(place.geometry.location.lng());
    });
    google.maps.event.addListener(map, 'click', function(event) {        
        marker.setPosition(event.latLng);
        $('.area_latitude').val(event.latLng.lat());
        $('.area_longitude').val(event.latLng.lng());
    });
}
</script>

