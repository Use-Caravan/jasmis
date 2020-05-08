<?php echo e(Form::open(['url' => $url, 'id' => 'area-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ])); ?>

    <div class="box-body">        
        <div class="form-group <?php echo e(($errors->has("country_id")) ? 'has-error' : ''); ?>">
            <div class="col-md-12">
            <?php echo e(Form::label("country_id", __('admincrud.Country Name'), ['class' => 'required'])); ?> 
            <?php echo e(Form::select('country_id', $countryList, $model->country_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Country'),'id'=>'Area-country_id'] )); ?>

                <?php if($errors->has("country_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("country_id")); ?></span>
                <?php endif; ?> 
            </div>
        </div>   
        <div class="form-group <?php echo e(($errors->has("city_id")) ? 'has-error' : ''); ?>">
            <div class="col-md-12">
            <?php echo e(Form::label("city_id", __('admincrud.City Name'), ['class' => 'required'])); ?> 
            <?php echo e(Form::select('city_id', $cityList, $model->city_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose City'),'id' => 'Area-city_id'] )); ?>

                <?php if($errors->has("city_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("city_id")); ?></span>
                <?php endif; ?> 
            </div>
        </div>     
        
        <ul class="nav nav-tabs full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                             
                <li <?php if($key == App::getLocale()): ?> class="active" <?php endif; ?> haserror="<?php echo e($errors->has("area_name.$key")); ?>"> 
                    <a data-toggle="tab" href="#tab<?php echo e($key); ?>"><?php echo e($language); ?> </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <div class="tab-content full_row">            
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div id="tab<?php echo e($key); ?>" class="tab-pane fade <?php if($key == App::getLocale()): ?> active in <?php endif; ?>">
                    <div class="form-group <?php echo e(($errors->has("area_name.$key")) ? 'has-error' : ''); ?>" >
                        <div class="col-md-12">
                            <?php echo e(Form::label("area_name[$key]", __('admincrud.Area Name'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text("area_name[$key]", $modelLang['area_name'][$key], ['class' => 'form-control'])); ?> 
                            <?php if($errors->has("area_name.$key")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("area_name.$key")); ?></span>
                            <?php endif; ?>                    
                        </div>
                    </div>                    
                </div>                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div> <!--tab-pane-->                        
        <div class="col-m-12">
            <input id="places_autocomplete" class="form-control" type="text" placeholder="<?php echo app('translator')->getFromJson('admincrud.Search your location'); ?>">
            <div id="map" style="height:250px;"></div>
        </div>
        <div class="form-group <?php echo e(($errors->has("latitude")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12" id="lat">                          
                <?php echo e(Form::label("latitude", __('admincrud.Latitude'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("latitude", $model['latitude'], ['class' => 'form-control area_latitude','readonly'])); ?> 
                <?php if($errors->has("latitude")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("latitude.$key")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("longitude")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
                <?php echo e(Form::label("longitude", __('admincrud.Longitude'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("longitude", $model['longitude'], ['class' => 'form-control area_longitude','readonly'])); ?> 
                <?php if($errors->has("longitude")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("longitude")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group radio_group<?php echo e(($errors->has("status")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">
                <?php echo e(Form::label("status", __('admincommon.Status'),['class' => 'required'])); ?>                                          
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
        <?php echo e(Html::link(route('area.index'), __('admincommon.Cancel'),['class' => 'btn btn-default'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\AreaRequest', '#area-form'); ?>


<script type="text/javascript">
$(document).ready(function(){
    $('#Area-country_id').change(function()
    {
        $.ajax({
            url: "<?php echo e(route('city-by-country')); ?>",
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

