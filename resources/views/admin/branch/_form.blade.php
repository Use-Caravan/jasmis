{{ Form::open(['url' => $url, 'id' => 'branch-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST','enctype' => "multipart/form-data" ]) }}
    <div class="box-body">
        
               
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{$errors->has("branch_name.$key").$errors->has("branch_address.$key")}}"> 
                <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a></li>
            @endforeach
        </ul>
        <div class="tab-content full_row">            
            @foreach ($languages as $key => $language)
                <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">                

                    <div class="col-md-6">                
                        <div class="form-group {{ ($errors->has("branch_name.$key")) ? 'has-error' : '' }}" >
                            {{ Form::label("branch_name[$key]", __('admincrud.Branch Name'), ['class' => 'required']) }}
                            {{ Form::text("branch_name[$key]", $modelLang['branch_name'][$key], ['class' => 'form-control']) }} 
                            @if($errors->has("branch_name.$key"))
                                <span class="help-block error-help-block">{{ $errors->first("branch_name.$key") }}</span>
                            @endif                    
                        </div>
                    </div>
                    {{--<div class="col-md-6">
                        <div class="form-group {{ ($errors->has("branch_logo.$key")) ? 'has-error' : '' }} vendor "  >
                        <ul class="uploads reset">                                
                            <li>                               
                                {{ 
                                    Form::label(
                                    "branch_logo[$key]",
                                    __('admincrud.Branch Logo'), 
                                    [
                                        'class' => "required fa fa-plus-circle branch_logo[$key]",
                                        'style' => ($model->exists) ? 'background:url('.FileHelper::loadImage(  isset($modelLang["branch_logo"][$key]) ? $modelLang["branch_logo"][$key] : ''  ).')' : ''
                                    ])
                                 }}

                                {{ Form::file("branch_logo[$key]", ['class' => 'form-control branch_upload']) }}--}}
                               
                                {{--  
                                    <input id="upload1" type="file">
                                    <label for="upload1"><i class="fa fa-plus-circle"></i></label> 
                                --}}                                
                            {{--</li>
                             @if($errors->has("branch_logo.$key"))
                                    <span class="help-block error-help-block">{{ $errors->first("branch_logo.$key") }}</span>
                                @endif 
                        </ul>
                    </div>
                    </div> --}}
                    
                    <div class="col-md-6">
                        <div class="form-group {{ ($errors->has("branch_address.$key")) ? 'has-error' : '' }}" >
                            {{ Form::label("branch_address[$key]", __('admincrud.Branch Address'), ['class' => 'required']) }}
                            {{ Form::textarea("branch_address[$key]", $modelLang['branch_address'][$key], ['class' => 'form-control']) }} 
                            @if($errors->has("branch_address.$key"))
                                <span class="help-block error-help-block">{{ $errors->first("branch_address.$key") }}</span>
                            @endif                    
                        </div>
                    </div>                                            
                </div>                
            @endforeach
        </div> <!--tab-pane-->
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("contact_email")) ? 'has-error' : '' }}">                    
                {{ Form::label("contact_email", __('admincommon.Email'), ['class' => 'required']) }}
                {{ Form::text("contact_email", $model['contact_email'], ['class' => 'form-control']) }} 
                @if($errors->has("contact_email"))
                    <span class="help-block error-help-block">{{ $errors->first("contact_email") }}</span>
                @endif                    
            </div>
        </div> 
        <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("contact_number")) ? 'has-error' : '' }}">                    
                {{ Form::label("contact_number", __('admincommon.Contact Number'), ['class' => 'required']) }}
                {{ Form::text("contact_number", $model['contact_number'], ['class' => 'form-control']) }} 
                @if($errors->has("contact_number"))
                    <span class="help-block error-help-block">{{ $errors->first("contact_number") }}</span>
                @endif                    
            </div>
        </div>
         <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("password")) ? 'has-error' : '' }}">                    
                {{ Form::label("password", __('admincommon.Password'), ($model->exists)?'':['class' => 'required']) }}
                {{ Form::password("password", ['class' => 'form-control']) }} 
                @if($errors->has("password"))
                    <span class="help-block error-help-block">{{ $errors->first("password") }}</span>
                @endif                    
            </div>
        </div> 
        <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("confirm_password")) ? 'has-error' : '' }}">                    
                {{ Form::label("confirm_password", __('admincommon.Confirm Password'),($model->exists)?'':['class' => 'required']) }}
                {{ Form::password("confirm_password", ['class' => 'form-control']) }} 
                @if($errors->has("confirm_password"))
                    <span class="help-block error-help-block">{{ $errors->first("confirm_password") }}</span>
                @endif                    
            </div>
        </div> 
        @if(APP_GUARD == GUARD_ADMIN) 
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("vendor_id")) ? 'has-error' : '' }}">
            {{ Form::label("vendor_id", __('admincrud.Vendor Name'), ['class' => 'required']) }} 
            {{ Form::select('vendor_id', $vendorList, $model->vendor_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Vendor'),'id' => 'vendor_id'] )}}
                @if($errors->has("vendor_id"))
                    <span class="help-block error-help-block">{{ $errors->first("vendor_id") }}</span>
                @endif 
            </div>
        </div>
        @endif
        @if(APP_GUARD == GUARD_VENDOR) 
            {{ Form::hidden("vendor_id", auth()->guard(GUARD_VENDOR)->user()->vendor_id) }}
        @endif
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("country_id")) ? 'has-error' : '' }}">
            {{ Form::label("country_id", __('admincrud.Country Name'), ['class' => 'required']) }} 
            {{ Form::select('country_id', $countryList, $model->country_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Country'),'id' => 'Vendor-country_id'] )}}
                @if($errors->has("country_id"))
                    <span class="help-block error-help-block">{{ $errors->first("country_id") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("city_id")) ? 'has-error' : '' }}">
            {{ Form::label("city_id", __('admincrud.City Name'), ['class' => 'required']) }} 
            {{ Form::select('city_id', $cityList, $model->city_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose City'),'id' => 'Vendor-city_id'] )}}
                @if($errors->has("city_id"))
                    <span class="help-block error-help-block">{{ $errors->first("city_id") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("area_id")) ? 'has-error' : '' }}">
            {{ Form::label("area_id", __('admincrud.Area Name'), ['class' => 'required']) }} 
            {{ Form::select('area_id', $areaList, $model->area_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Area'),'id' => 'Vendor-area_id'] )}}
                @if($errors->has("area_id"))
                    <span class="help-block error-help-block">{{ $errors->first("area_id") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("delivery_area_id")) ? 'has-error' : '' }}"> 
            {{Form::label('delivery_area_id', __('admincrud.Delivery Area'),['class' => 'required'])}}
            {{Form::select('delivery_area_id[]',$deliveryAreaList,$existsDeliveryAreas,['multiple'=>'multiple','class' => 'selectpicker','id' => 'Vendor-delivery_area_id', 'title' => __('admincommon.Nothing selected')])}}
            @if($errors->has("delivery_area_id"))
                <span class="help-block error-help-block">{{ $errors->first("delivery_area_id") }}</span>
            @endif 
            </div> 
        </div> 
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("cuisine_id")) ? 'has-error' : '' }}"> 
            {{Form::label('cuisine_id', __('admincrud.Cuisine Name'),['class' => 'required'])}}
            {{Form::select('cuisine_id[]',$cusineList,$existsCuisine,['multiple'=>'multiple','class' => 'selectpicker', 'title' => __('admincommon.Nothing selected')])}}
            @if($errors->has("cuisine_id"))
                <span class="help-block error-help-block">{{ $errors->first("cuisine_id") }}</span>
            @endif 
            </div> 
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("category_id")) ? 'has-error' : '' }}"> 
            {{Form::label('category_id', __('admincrud.Category Name'),['class' => 'required'])}}
            {{Form::select('category_id[]',$categoryList,$existsCategory,['multiple'=>'multiple','class' => 'selectpicker', 'title' => __('admincommon.Nothing selected')])}}
            @if($errors->has("category_id"))
                <span class="help-block error-help-block">{{ $errors->first("category_id") }}</span>
            @endif 
            </div> 
        </div>
        <div class="col-m-6">
            <input id="places_autocomplete" class="form-control" type="text" placeholder="@lang('admincrud.Search branch location')">
            <div id="map" style="height:250px;"></div>
        </div>
        <div class="col-md-6" id="lat">
            <div class="form-group {{ ($errors->has("latitude")) ? 'has-error' : '' }}">                    
                {{ Form::label("latitude", __('admincrud.Latitude'), ['class' => 'required']) }}
                {{ Form::text("latitude", $model['latitude'], ['class' => 'form-control area_latitude','readonly']) }} 
                @if($errors->has("latitude"))
                    <span class="help-block error-help-block">{{ $errors->first("latitude.$key") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("longitude")) ? 'has-error' : '' }}">                    
                {{ Form::label("longitude", __('admincrud.Longitude'), ['class' => 'required']) }}
                {{ Form::text("longitude", $model['longitude'], ['class' => 'form-control area_longitude','readonly']) }} 
                @if($errors->has("longitude"))
                    <span class="help-block error-help-block">{{ $errors->first("longitude") }}</span>
                @endif                    
            </div>
        </div> 
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("restaurant_type")) ? 'has-error' : '' }}">
            {{ Form::label("restaurant_type", __('admincrud.Restaurant Type')) }}
            @php $model->restaurant_type = ($model->exists) ? $model->restaurant_type : RESTAURANT_TYPE_VEG @endphp      
                @foreach($vendor->restaurantTypes() as $key => $value)    
                    {{ Form::radio('restaurant_type', $key, ($model->restaurant_type == $key), ['class' => 'hide', 'id' => "restaurant_type$key"]) }}
                    {{ Form::label("restaurant_type$key", $value, ['class' => 'radio']) }} 
                @endforeach
                @if($errors->has("restaurant_type"))
                    <span class="help-block error-help-block">{{ $errors->first("restaurant_type") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("preparation_time")) ? 'has-error' : '' }}">                    
                {{ Form::label("preparation_time", __('admincrud.Prepartion Time'), ['class' => 'required']) }}
                {{ Form::text("preparation_time", $model['preparation_time'], ['class' => 'form-control']) }} 
                @if($errors->has("preparation_time"))
                    <span class="help-block error-help-block">{{ $errors->first("preparation_time") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("delivery_time")) ? 'has-error' : '' }}">                    
                {{ Form::label("delivery_time", __('admincrud.Delivery Time'), ['class' => 'required']) }}
                {{ Form::text("delivery_time", $model['delivery_time'], ['class' => 'form-control']) }} 
                @if($errors->has("delivery_time"))
                    <span class="help-block error-help-block">{{ $errors->first("delivery_time") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("pickup_time")) ? 'has-error' : '' }}">                    
                {{ Form::label("pickup_time", __('admincrud.Pickup Time'), ['class' => 'required']) }}
                {{ Form::text("pickup_time", $model['pickup_time'], ['class' => 'form-control']) }} 
                @if($errors->has("pickup_time"))
                    <span class="help-block error-help-block">{{ $errors->first("pickup_time") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("order_type")) ? 'has-error' : '' }}">
            {{ Form::label("order_type", __('admincrud.Order Type')) }}
            @php $model->order_type = ($model->exists) ? $model->order_type : ORDER_TYPE_PICKUP_DINEIN @endphp
                @foreach($order->orderTypes() as $key => $value) 
                    {{ Form::radio('order_type', $key, ($model->order_type == $key), ['class' => 'hide', 'id' => "order_type$key"]) }}
                    {{ Form::label("order_type$key", $value, ['class' => 'radio']) }} 
                @endforeach 
                @if($errors->has("order_type"))
                    <span class="help-block error-help-block">{{ $errors->first("order_type") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("availability_status")) ? 'has-error' : '' }}">
            {{ Form::label("availability_status", __('admincrud.Availability Status')) }}
            @php $model->availability_status = ($model->exists) ? $model->availability_status : AVAILABILITY_STATUS_OPEN @endphp
                @foreach($vendor->availablityTypes() as $key => $value)
                    {{ Form::radio('availability_status', $key, ($model->availability_status == $key), ['class' => 'hide', 'id' => "availability_status$key"]) }}
                    {{ Form::label("availability_status$key", $value, ['class' => 'radio']) }} 
                @endforeach            
                @if($errors->has("availability_status"))
                    <span class="help-block error-help-block">{{ $errors->first("availability_status") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("approved_status")) ? 'has-error' : '' }}">
            {{ Form::label("approved_status", __('admincrud.Approved Status')) }}
            @php $model->approved_status = ($model->exists) ? $model->approved_status : BRANCH_APPROVED_STATUS_PENDING  @endphp
                @foreach($vendor->approvedStatus() as $key => $value)
                    {{ Form::radio('approved_status', $key , ($model->approved_status == $key ), ['class' => 'hide', 'id' => "approved_status_pending$key"]) }}
                    {{ Form::label("approved_status_pending$key", $value, ['class' => 'radio']) }} 
                @endforeach            
                @if($errors->has("approved_status"))
                    <span class="help-block error-help-block">{{ $errors->first("approved_status") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">
                {{ Form::label("vendor_status", __('admincrud.Branch Status')) }}
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
        {{ Html::link(route('branch.index'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}
{!! JsValidator::formRequest('App\Http\Requests\Admin\BranchRequest', '#branch-form')  !!}
<script> 
$(document).ready(function(){
    $('#Vendor-country_id').change(function()
    {   
        $.ajax({
            url: "{{ route('city-by-country') }}",
            type: 'post',
            data: {country_id: $('#Vendor-country_id').val()},
            success: function(result){ 
                 if(result.status == AJAX_SUCCESS){
                    $('#Vendor-city_id').html('');                    
                    $.each(result.data,function(key,title)
                    {  
                        $('#Vendor-city_id').append($('<option>', { value : key }).text(title));                       
                    });                    
                    loadArea($('#Vendor-city_id').val());
                    $('.selectpicker').selectpicker('refresh');
                    
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    });

    $('#Vendor-city_id').change(function()
    {
        loadArea($(this).val());
    });

    $('#Vendor-area_id').change(function()
    {  
        loadDeliveryArea($(this).val());
    });

function loadArea(cityID)
{
    $.ajax({
        url: "{{ route('area-by-city') }}",
        type: 'post',
        data: {city_id: cityID},
        success: function(result){ 
            if(result.status == AJAX_SUCCESS){
                $('#Vendor-area_id').html('');                    
                $.each(result.data,function(key,title)
                {  
                    $('#Vendor-area_id').append($('<option>', { value : key }).text(title));                       
                });     
                loadDeliveryArea($('#Vendor-area_id').val());
                $('.selectpicker').selectpicker('refresh');                  
            }else{
                Error('Something went wrong','Error');
            }
        }
    });
}
function loadDeliveryArea(areaID)
{
    $.ajax({
        url: "{{ route('delivery-area-by-area') }}",
        type: 'post',
        data: {area_id: areaID},
        success: function(result){            
            if(result.status == AJAX_SUCCESS){
                $('#Vendor-delivery_area_id').html('');
                
                $.each(result.data,function(key,title)
                {  
                    $('#Vendor-delivery_area_id').append($('<option>', { value : key }).text(title));                       
                });
                $('.selectpicker').selectpicker('refresh');
            }else{
                Error('Something went wrong','Error');
            }
        }
    });
}

$('.branch_upload').on('change',function () {
    var image = $(this).val();
    var preview = $(this).closest('.uploads').find('label');
    //var preview = document.querySelector('.base');
    var file    = document.querySelector('input[type=file]').files[0];
    var reader  = new FileReader();


    reader.addEventListener("load", function () {        
        //preview.src = reader.result;                
        preview.css('background-image', 'url(' + reader.result + ')');

    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }

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
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('webconfig.map_key') }}&callback=initMap&libraries=drawing,places&sensor=false"></script>



