<?php echo e(Form::open(['url' => $url, 'id' => 'vendor-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST','enctype' => "multipart/form-data" ])); ?>

    <div class="box-body">
        
               
        <ul class="nav nav-tabs full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                             
                <li <?php if($key == App::getLocale()): ?> class="active" <?php endif; ?>> <a data-toggle="tab" href="#tab<?php echo e($key); ?>"><?php echo e($language); ?> </a></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <div class="tab-content full_row">            
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div id="tab<?php echo e($key); ?>" class="tab-pane fade <?php if($key == App::getLocale()): ?> active in <?php endif; ?>">                

                    <div class="col-md-6">                
                        <div class="form-group <?php echo e(($errors->has("vendor_name.$key")) ? 'has-error' : ''); ?>" >
                            <?php echo e(Form::label("vendor_name[$key]", __('admincrud.Vendor Name'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text("vendor_name[$key]", $modelLang['vendor_name'][$key], ['class' => 'form-control'])); ?> 
                            <?php if($errors->has("vendor_name.$key")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("vendor_name.$key")); ?></span>
                            <?php endif; ?>                    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo e(($errors->has("vendor_logo.$key")) ? 'has-error' : ''); ?> vendor " >
                        <ul class="uploads reset">                                
                            <li>                               
                                <?php echo e(Form::label(
                                    "vendor_logo[$key]",
                                    __('admincrud.Vendor Logo')." 120 * 120", 
                                    [
                                        'class' => "required fa fa-plus-circle vendor_logo[$key]",
                                        "id" => "vendor_lang_image$key",
                                        'style' => ($model->exists) ? 'background:url('.FileHelper::loadImage(  isset($modelLang["vendor_logo"][$key]) ? $modelLang["vendor_logo"][$key] : ''  ).')' : ''
                                    ])); ?>


                                <?php echo e(Form::file("vendor_logo[$key]", ['class' => 'form-control vendor_upload','lang' => $key])); ?>

                               
                                                                
                            </li>
                                <?php if($errors->has("vendor_logo.$key")): ?>
                                    <span class="help-block error-help-block"><?php echo e($errors->first("vendor_logo.$key")); ?></span>
                                <?php endif; ?> 
                        </ul>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo e(($errors->has("vendor_description.$key")) ? 'has-error' : ''); ?>" >
                            <?php echo e(Form::label("vendor_description[$key]", __('admincrud.Vendor Description'), ['class' => 'required'])); ?>

                            <?php echo e(Form::textarea("vendor_description[$key]", $modelLang['vendor_description'][$key], ['class' => 'form-control'])); ?> 
                            <?php if($errors->has("vendor_description.$key")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("vendor_description.$key")); ?></span>
                            <?php endif; ?>                    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group <?php echo e(($errors->has("vendor_address.$key")) ? 'has-error' : ''); ?>" >
                            <?php echo e(Form::label("vendor_address[$key]", __('admincrud.Vendor Address'), ['class' => 'required'])); ?>

                            <?php echo e(Form::textarea("vendor_address[$key]", $modelLang['vendor_address'][$key], ['class' => 'form-control'])); ?> 
                            <?php if($errors->has("vendor_address.$key")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("vendor_address.$key")); ?></span>
                            <?php endif; ?>                    
                        </div>
                    </div>                                            
                                        
                </div>                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div> <!--tab-pane-->
        <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("username")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("username", __('admincommon.User Name'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("username", $model['username'], ['class' => 'form-control'])); ?> 
                <?php if($errors->has("username")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("username")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("email")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("email", __('admincommon.Email'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("email", $model['email'], ['class' => 'form-control'])); ?> 
                <?php if($errors->has("email")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("email")); ?></span>
                <?php endif; ?>                    
            </div>
        </div> 
        <div class="col-md-6">  
            <div class="form-group <?php echo e(($errors->has("mobile_number")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("mobile_number", __('admincommon.Mobile Number'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("mobile_number", $model['mobile_number'], ['class' => 'form-control'])); ?> 
                <?php if($errors->has("mobile_number")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("mobile_number")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-md-6">  
            <div class="form-group <?php echo e(($errors->has("contact_number")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("contact_number", __('admincommon.Contact Number'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("contact_number", $model['contact_number'], ['class' => 'form-control'])); ?> 
                <?php if($errors->has("contact_number")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("contact_number")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
         
        <div class="col-md-6">  
            <div class="form-group <?php echo e(($errors->has("password")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("password", __('admincommon.Password'), ($model->exists)?'':['class' => 'required'])); ?>

                <?php echo e(Form::password("password", ['class' => 'form-control'])); ?> 
                <?php if($errors->has("password")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("password")); ?></span>
                <?php endif; ?>                    
            </div>
        </div> 
        <div class="col-md-6">  
            <div class="form-group <?php echo e(($errors->has("confirm_password")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("confirm_password", __('admincommon.Confirm Password'),($model->exists)?'':['class' => 'required'])); ?>

                <?php echo e(Form::password("confirm_password", ['class' => 'form-control'])); ?> 
                <?php if($errors->has("confirm_password")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("confirm_password")); ?></span>
                <?php endif; ?>                    
            </div>
        </div> 
         
        <div class="col-md-6">
            <div class="form-group <?php echo e(($errors->has("country_id")) ? 'has-error' : ''); ?>">
            <?php echo e(Form::label("country_id", __('admincrud.Country Name'), ['class' => 'required'])); ?> 
            <?php echo e(Form::select('country_id', $countryList, $model->country_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Country'),'id' => 'Vendor-country_id'] )); ?>

                <?php if($errors->has("country_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("country_id")); ?></span>
                <?php endif; ?> 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("city_id")) ? 'has-error' : ''); ?>">
            <?php echo e(Form::label("city_id", __('admincrud.City Name'), ['class' => 'required'])); ?> 
            <?php echo e(Form::select('city_id', $cityList, $model->city_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose City'),'id' => 'Vendor-city_id'] )); ?>

                <?php if($errors->has("city_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("city_id")); ?></span>
                <?php endif; ?> 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("area_id")) ? 'has-error' : ''); ?>">
            <?php echo e(Form::label("area_id", __('admincrud.Area Name'), ['class' => 'required'])); ?> 
            <?php echo e(Form::select('area_id', $areaList, $model->area_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Area'),'id' => 'Vendor-area_id'] )); ?>

                <?php if($errors->has("area_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("area_id")); ?></span>
                <?php endif; ?> 
            </div>
        </div>
       
          <div class="col-m-6">
            <input id="places_autocomplete" class="form-control" type="text" placeholder="<?php echo app('translator')->getFromJson('admincrud.Search vendor location'); ?>">
            <div id="map" style="height:250px;"></div>
        </div>
        <div class="col-md-6" id="lat">
            <div class="form-group <?php echo e(($errors->has("latitude")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("latitude", __('admincrud.Latitude'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("latitude", $model['latitude'], ['class' => 'form-control area_latitude','readonly'])); ?> 
                <?php if($errors->has("latitude")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("latitude.$key")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group <?php echo e(($errors->has("longitude")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("longitude", __('admincrud.Longitude'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("longitude", $model['longitude'], ['class' => 'form-control area_longitude','readonly'])); ?> 
                <?php if($errors->has("longitude")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("longitude")); ?></span>
                <?php endif; ?>                    
            </div>
        </div> 
        <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("commission_type")) ? 'has-error' : ''); ?>">
            <?php echo e(Form::label("commission_type", __('admincrud.Commission Type'))); ?>

            <?php $model->commission_type = ($model->exists) ? $model->commission_type : VENDOR_COMMISSION_TYPE_PERCENTAGE ?>                    
            <?php echo e(Form::radio('commission_type', VENDOR_COMMISSION_TYPE_PERCENTAGE, ($model->commission_type == VENDOR_COMMISSION_TYPE_PERCENTAGE), ['class' => 'hide', 'id' => 'commission_percentage'])); ?>

            <?php echo e(Form::label("commission_percentage", __('admincrud.Percentage'), ['class' => 'radio'])); ?> 
            <?php echo e(Form::radio('commission_type', VENDOR_COMMISSION_TYPE_AMOUNT, ($model->commission_type == VENDOR_COMMISSION_TYPE_AMOUNT), ['class' => 'hide', 'id' => 'commission_amount'])); ?>

            <?php echo e(Form::label("commission_amount", __('admincrud.Amount'), ['class' => 'radio'])); ?> 
             <?php if($errors->has("commission_type")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("commission_type")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
         <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("commission")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("commission", __('admincrud.Commission'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("commission", $model['commission'], ['class' => 'form-control'])); ?> 
                <?php if($errors->has("commission")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("commission")); ?></span>
                <?php endif; ?>                    
            </div>
        </div> 
        <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("tax")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("tax", __('admincrud.Tax'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("tax", $model['tax'], ['class' => 'form-control'])); ?> 
                <?php if($errors->has("tax")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("tax")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("service_tax")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("service_tax", __('admincrud.Service Tax'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("service_tax", $model['service_tax'], ['class' => 'form-control'])); ?> 
                <?php if($errors->has("service_tax")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("service_tax")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("min_order_value")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("min_order_value", __('admincrud.Min Order Value'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("min_order_value", $model['min_order_value'], ['class' => 'form-control'])); ?> 
                <?php if($errors->has("min_order_value")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("min_order_value")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        
        <div class="col-md-6">
            
            <div class="form-group <?php echo e(($errors->has("payment_option")) ? 'has-error' : ''); ?>"> 
            <?php echo e(Form::label('shopbeneficiary_id', __('admincrud.Payment Option'),['class' => 'required'])); ?>

            <?php echo e(Form::select('payment_option[]',$order->paymentTypes(),explode(',', ($model->payment_option == null) ? '' : $model->payment_option ),['multiple'=>'multiple','class' => 'selectpicker','title' => __('admincommon.Nothing selected')])); ?>

            <?php if($errors->has("shopbeneficiary_id")): ?>
                <span class="help-block error-help-block"><?php echo e($errors->first("payment_option")); ?></span>                
            <?php endif; ?> 
            </div> 
        
        </div>

        <div class = "clearfix"></div>
         <div class="col-md-6">  
            <div class="form-group <?php echo e(($errors->has("color_code")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("color_code", __('admincrud.Color Code'),['class' => 'required'])); ?>

                <?php echo e(Form::text("color_code", $model['color_code'], ['class' => 'form-control jscolor'])); ?> 
                <?php if($errors->has("color_code")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("color_code")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-md-6">  
            <div class="form-group <?php echo e(($errors->has("sort_no")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("sort_no", __('admincrud.Sort No'))); ?>

                <?php echo e(Form::text("sort_no", $model['sort_no'], ['class' => 'form-control'])); ?> 
                <?php if($errors->has("sort_no")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("sort_no")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        
        <div class="col-md-6"> 
            <div class="form-group <?php echo e(($errors->has("approved_status")) ? 'has-error' : ''); ?>">
            <?php echo e(Form::label("approved_status", __('admincrud.Approved Status'))); ?>

            <?php $model->approved_status = ($model->exists) ? $model->approved_status : BRANCH_APPROVED_STATUS_PENDING  ?>
                <?php $__currentLoopData = $model->approvedStatus(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e(Form::radio('approved_status', $key , ($model->approved_status == $key ), ['class' => 'hide', 'id' => "approved_status_pending$key"])); ?>

                    <?php echo e(Form::label("approved_status_pending$key", $value, ['class' => 'radio'])); ?> 
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>            
                <?php if($errors->has("approved_status")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("approved_status")); ?></span>
                <?php endif; ?> 
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group radio_group<?php echo e(($errors->has("status")) ? 'has-error' : ''); ?>">
                <?php echo e(Form::label("vendor_status", __('admincrud.Vendor Status'))); ?>

                <?php $model->status = ($model->exists) ? $model->status : ITEM_ACTIVE ?>
                <?php echo e(Form::radio('status', ITEM_ACTIVE, ($model->status == ITEM_ACTIVE), ['class' => 'hide','id'=> 'statuson' ])); ?>

                <?php echo e(Form::label("statuson", __('admincommon.Active'), ['class' => ' radio'])); ?>


                <?php echo e(Form::radio('status', ITEM_INACTIVE, ($model->status == ITEM_INACTIVE), ['class' => 'hide','id'=>'statusoff'])); ?>

                <?php echo e(Form::label("statusoff", __('admincommon.Inactive'), ['class' => 'radio'])); ?>

                <?php if($errors->has("status")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("status")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>                                      
    </div>
  <!-- /.box-body -->
    <div class="box-footer">
        <?php echo e(Html::link(route('vendor.index'), __('admincommon.Cancel'),['class' => 'btn btn-default'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\VendorRequest', '#vendor-form'); ?>


<script> 
$(document).ready(function(){
    $('#Vendor-country_id').change(function()
    {   
        $.ajax({
            url: "<?php echo e(route('city-by-country')); ?>",
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

    $('.vendor_upload').on('change',function () {
        var image = $(this).val();
        var lang = $(this).attr('lang');
        var preview = $('#vendor_lang_image'+lang);
        var file    = document.querySelector('input[name="vendor_logo['+lang+']"]').files[0];
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
function loadArea(cityID)
{
    $.ajax({
        url: "<?php echo e(route('area-by-city')); ?>",
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
        url: "<?php echo e(route('delivery-area-by-area')); ?>",
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

function initMap() {

    var latitude = $('.area_latitude').val();
    var longitude = $('.area_longitude').val();
    if(latitude != '' && longitude != ''){
        var latlng = new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
    }else{
        var latlng = new google.maps.LatLng(<?php echo e(config('webconfig.app_latitude')); ?>, <?php echo e(config('webconfig.app_longitude')); ?>);
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
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('webconfig.map_key')); ?>&callback=initMap&libraries=drawing,places&sensor=false"></script>



