
<?php echo e(Form::open(['url' => $url, 'id' => 'delivery-area-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ])); ?>

    <div class="box-body">
       
        <div class="col-md-4">
            <div class="form-group <?php echo e(($errors->has("country_id")) ? 'has-error' : ''); ?>">            
            <?php echo e(Form::label("country_id", __('admincrud.Country Name'), ['class' => 'required'])); ?> 
            <?php echo e(Form::select('country_id', $countryList, $model->country_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Country'),'id'=>'DeliveryArea-country_id'] )); ?>

                <?php if($errors->has("country_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("country_id")); ?></span>
                <?php endif; ?> 
            </div>
        </div>   
        <div class="col-md-4">
            <div class="form-group <?php echo e(($errors->has("city_id")) ? 'has-error' : ''); ?>">            
            <?php echo e(Form::label("city_id", __('admincrud.City Name'), ['class' => 'required'])); ?> 
            <?php echo e(Form::select('city_id', $cityList, $model->city_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose City'),'id' => 'DeliveryArea-city_id'] )); ?>

                <?php if($errors->has("city_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("city_id")); ?></span>
                <?php endif; ?> 
            </div>
        </div>             
        <div class="col-md-4">
            <div class="form-group <?php echo e(($errors->has("area_id")) ? 'has-error' : ''); ?>">            
            <?php echo e(Form::label("area_id", __('admincrud.Area Name'), ['class' => 'required'])); ?> 
            <?php echo e(Form::select('area_id', $areaList, $model->area_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Area'),'id' => 'DeliveryArea-area_id'] )); ?>

                <?php if($errors->has("area_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("area_id")); ?></span>
                <?php endif; ?> 
            </div>
        </div>             
        <ul class="nav nav-tabs full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                             
                <li <?php if($key == App::getLocale()): ?> class="active" <?php endif; ?> haserror="<?php echo e($errors->has("delivery_area_name.$key")); ?>"> 
                    <a data-toggle="tab" href="#tab<?php echo e($key); ?>"><?php echo e($language); ?> </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <div class="tab-content full_row">            
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div id="tab<?php echo e($key); ?>" class="tab-pane fade <?php if($key == App::getLocale()): ?> active in <?php endif; ?>">
                    <div class="form-group <?php echo e(($errors->has("delivery_area_name.$key")) ? 'has-error' : ''); ?>" >
                        <div class="col-md-12">
                            <?php echo e($modelLang[$key]); ?>

                            <?php echo e(Form::label("delivery_area_name[$key]", __('admincrud.Delivery Area'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text("delivery_area_name[$key]", $modelLang['delivery_area_name'][$key], ['class' => 'form-control'])); ?> 
                            <?php if($errors->has("delivery_area_name.$key")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("delivery_area_name.$key")); ?></span>
                            <?php endif; ?>                    
                        </div>
                    </div>                    
                </div>                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div> <!--tab-pane-->  
        <?php
            $model->zone_type = (!$model->exists) ?  DELIVERY_AREA_ZONE_CIRCLE : $model->zone_type;
        ?>
        <?php echo e(Form::hidden('zone_type',$model->zone_type,[ 'id' => 'zone_type' ])); ?>

        <?php echo e(Form::hidden('circle_latitude',$model->circle_latitude,[ 'id' => 'circle_latitude', 'class' => 'area_latitude' ])); ?>

        <?php echo e(Form::hidden('circle_longitude',$model->circle_longitude,[ 'id' => 'circle_longitude', 'class' => 'area_longitude' ])); ?>

        <?php echo e(Form::hidden('zone_radius',$model->zone_radius,[ 'id' => 'zone_radius' ])); ?>

        <?php echo e(Form::hidden('zone_latlng', '' ,[ 'id' => 'zone_latlng' ])); ?>

        <div class="col-md-12">
            <input id="places_autocomplete" class="form-control" type="text" placeholder="<?php echo app('translator')->getFromJson('admincrud.Search your location'); ?>">
            <div id="map" style="height:500px;"></div>
            <?php if(old('zone_type') == DELIVERY_AREA_ZONE_CIRCLE && ( $errors->has("circle_latitude") || $errors->has("circle_longitude")  || $errors->has("zone_radius") ) ): ?>
                <span class="help-block error-help-block">Please create delivery circel</span>
            <?php endif; ?>
            <?php if(old('zone_type') == DELIVERY_AREA_ZONE_POLYGON && $errors->has("zone_latlng")): ?>
                <span class="help-block error-help-block">Please create delivery polygon</span>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div><br/>
        <div class="form-group radio_group<?php echo e(($errors->has("status")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
                <?php $model->status = ($model->exists) ? $model->status : ITEM_ACTIVE ?>

                <?php echo e(Form::label("status", __('admincommon.Status'), ['class' => 'required'])); ?>


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
        <?php echo e(Html::link(route('delivery-area.index'), __('admincommon.Cancel'),['class' => 'btn btn-default'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\DeliveryAreaRequest', '#delivery-area-form'); ?>


<script type="text/javascript">
$(document).ready(function(){    
    $('#DeliveryArea-country_id').change(function()
    { 
        $.ajax({
            url: "<?php echo e(route('city-by-country')); ?>",
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
            url: "<?php echo e(route('area-by-city')); ?>",
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
<?php echo $__env->make('admin.delivery-area._map-script', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

