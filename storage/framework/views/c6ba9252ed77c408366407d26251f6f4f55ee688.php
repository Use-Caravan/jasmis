<?php echo e(Form::open(['url' => $url, 'id' => 'voucher-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST','enctype' => "multipart/form-data" ])); ?>

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
                                <div class="form-group <?php echo e(($errors->has("offer_name.$key")) ? 'has-error' : ''); ?>" >
                                    <?php echo e(Form::label("offer_name[$key]", __('admincrud.Offer Name'), ['class' => 'required'])); ?>

                                    <?php echo e(Form::text("offer_name[$key]", $modelLang['offer_name'][$key], ['class' => 'form-control'])); ?> 
                                    <?php if($errors->has("offer_name.$key")): ?>
                                        <span class="help-block error-help-block"><?php echo e($errors->first("offer_name.$key")); ?></span>
                                    <?php endif; ?>                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group <?php echo e(($errors->has("offer_banner.$key")) ? 'has-error' : ''); ?> vendor "  >
                                <ul class="uploads reset">                                
                                    <li>                               
                                        <?php echo e(Form::label(
                                            "offer_banner[$key]",
                                            __('admincrud.Offer Banner'). " (150 X 150)", 
                                            [
                                                'class' => "required fa fa-plus-circle offer_banner[$key]",
                                                'style' => ($model->exists) ? 'background:url('.FileHelper::loadImage(  isset($modelLang["offer_banner"][$key]) ? $modelLang["offer_banner"][$key] : ''  ).')' : ''
                                            ])); ?>

        
                                        <?php echo e(Form::file("offer_banner[$key]", ['class' => 'form-control vendor_upload'])); ?>

                                       
                                                                        
                                    </li>
                                     <?php if($errors->has("vendor_logo.$key")): ?>
                                            <span class="help-block error-help-block"><?php echo e($errors->first("vendor_logo.$key")); ?></span>
                                        <?php endif; ?> 
                                </ul>
                            </div>
                            </div>                            
                        </div>                
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div> <!--tab-pane-->
        <div class="col-md-6">
            <div class="form-group <?php echo e(($errors->has("offer_type")) ? 'has-error' : ''); ?>">                
            <?php echo e(Form::label("offer_type", __('admincrud.Offer Type'),['class' => 'required'])); ?>

            <?php $model->offer_type = ($model->exists) ? $model->offer_type : VOUCHER_DISCOUNT_TYPE_AMOUNT ?>
            <?php echo e(Form::radio('offer_type', VOUCHER_DISCOUNT_TYPE_AMOUNT, ($model->offer_type == VOUCHER_DISCOUNT_TYPE_AMOUNT), ['class' => 'hide', 'id' => 'offer_flat'])); ?>

            <?php echo e(Form::label("offer_flat", __('admincrud.Amount Based'), ['class' => 'radio'])); ?>

            <?php echo e(Form::radio('offer_type', VOUCHER_DISCOUNT_TYPE_PERCENTAGE, ($model->offer_type == VOUCHER_DISCOUNT_TYPE_PERCENTAGE), ['class' => 'hide', 'id' => 'offer_percentage'])); ?>

            <?php echo e(Form::label("offer_percentage", __('admincrud.Percentage Based'), ['class' => 'radio'])); ?>

            <?php if($errors->has("discount_type")): ?>
                <span class="help-block error-help-block"><?php echo e($errors->first("discount_type")); ?></span>
            <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group <?php echo e(($errors->has("offer_value")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("offer_value", __('admincrud.Offer Value'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("offer_value", $model->offer_value, ['class' => 'form-control','id' => 'offer_value'])); ?> 
                <?php if($errors->has("offer_value")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("offer_value")); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">  
            <div class="form-group <?php echo e(($errors->has("start_datetime")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("start_datetime", __('admincrud.Start Datetime'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("start_datetime",$model->start_datetime, [ 'data-val' => $model->start_datetime, 'class' => 'form-control datetimepicker','id'=>'start_date_picker', "autocomplete" => "off"])); ?> 
                <?php if($errors->has("start_datetime")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("start_datetime")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-md-6">  
            <div class="form-group <?php echo e(($errors->has("end_datetime")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("end_datetime", __('admincrud.Expiry Date'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("end_datetime",$model->end_datetime, [ 'data-val' => $model->end_datetime, 'class' => 'form-control datetimepicker','id'=>'end_date_picker', "autocomplete" => "off"])); ?>

                <?php if($errors->has("end_datetime")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("end_datetime")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>

        <?php if(APP_GUARD === GUARD_ADMIN): ?>
            <div class="col-md-6">  
                <div class="form-group <?php echo e(($errors->has("vendor_id")) ? 'has-error' : ''); ?>">
                    <?php echo e(Form::label("vendor_id", __('admincrud.Vendor Name'), ['class' => 'required'])); ?>

                    <?php echo e(Form::select('vendor_id', $vendorList, $model->vendor_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Vendor'),'id' => 'Offer-vendor_id'] )); ?>

                    <?php if($errors->has("vendor_id")): ?>
                        <span class="help-block error-help-block"><?php echo e($errors->first("vendor_id")); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(APP_GUARD === GUARD_ADMIN || APP_GUARD === GUARD_VENDOR): ?>
            <div class="col-md-6">  
                <div class="form-group <?php echo e(($errors->has("branch_id")) ? 'has-error' : ''); ?>">
                    <?php echo e(Form::label("branch_id", __('admincrud.Branch Name'), ['class' => 'required'])); ?>

                    <?php echo e(Form::select('branch_id', $branchList, $model->branch_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Branch'),'id' => 'Offer-branch_id'] )); ?>

                    <?php if($errors->has("branch_id")): ?>
                        <span class="help-block error-help-block"><?php echo e($errors->first("branch_id")); ?></span>
                    <?php endif; ?>                    
                </div>
            </div>
        <?php endif; ?>        
        <?php if(APP_GUARD === GUARD_VENDOR || APP_GUARD === GUARD_OUTLET): ?>
            <?php echo e(Form::hidden("vendor_id",auth()->guard(GUARD_VENDOR)->user()->vendor_id, ['class' => 'form-control'])); ?>

        <?php endif; ?>
        <?php if(APP_GUARD === GUARD_OUTLET): ?>        
            <?php echo e(Form::hidden("branch_id",$model->branch_id, ['class' => 'form-control'])); ?>

        <?php endif; ?>

        <div class="col-md-6">
            <div class="form-group <?php echo e(($errors->has("item_id")) ? 'has-error' : ''); ?>">
                <?php echo e(Form::label("item_id", __('admincrud.Item Name'), ['class' => 'required'])); ?>

                <?php echo e(Form::select('item_id[]', $itemList, $existsItem ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Item'), 'multiple'=>'multiple', 'id' => 'Offer-item_id'] )); ?>

                <?php if($errors->has("item_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("item_id")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>                
            
        <div class="col-md-6">
                <div class="form-group <?php echo e(($errors->has("display_in_home")) ? 'has-error' : ''); ?>">
            <?php echo e(Form::label("display_in_home", __('admincrud.Do you want to display in home page?'),['class' => 'required'])); ?>

            <?php $model->offer_type = ($model->exists) ? $model->display_in_home : 2 ?>
            <?php echo e(Form::radio('display_in_home', 1, ($model->offer_type == 1), ['class' => 'hide', 'id' => 'yes'])); ?>

            <?php echo e(Form::label("yes", __('admincommon.Yes'), ['class' => 'radio'])); ?>

            <?php echo e(Form::radio('display_in_home', 2, ($model->offer_type == 2), ['class' => 'hide', 'id' => 'no'])); ?>

            <?php echo e(Form::label("no", __('admincommon.No'), ['class' => 'radio'])); ?>

            <?php if($errors->has("display_in_home")): ?>
                <span class="help-block error-help-block"><?php echo e($errors->first("display_in_home")); ?></span>
            <?php endif; ?>
            </div>
        </div>
        <div class="clearfix">
       <div class="col-md-6">
            <div class="form-group radio_group<?php echo e(($errors->has("status")) ? 'has-error' : ''); ?>">
                <?php echo e(Form::label("voucher_status", __('admincrud.Offer Status'),['class' => 'required'])); ?>

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
  
    <div class="box-footer">  
        <?php echo e(Html::link(route('offer.index'), __('admincommon.Cancel'),['class' => 'btn btn-default'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\VoucherRequest', '#voucher-form'); ?>


<script> 
$(document).ready(function() {

    /* var minDate = "<?php echo e(date('Y,m,d,H,i,s')); ?>";
    alert(minDate); */
    $('.datetimepicker').datetimepicker({    
        "minDate" : new Date(),
    });    


    $('#start_date_picker').val('<?php echo e(($model->start_datetime !== null) ? date("m/d/Y h:i A",strtotime($model->start_datetime)) : ''); ?>');
    $('#end_date_picker').val('<?php echo e(($model->end_datetime !== null) ?  date("m/d/Y h:i A",strtotime($model->end_datetime)) : ''); ?>');


    $('#Offer-vendor_id').change(function()
    {   
        $.ajax({
            url: "<?php echo e(route('get-branch-by-vendor')); ?>",
            type: 'post',
            data: { vendor_id: $(this).val() , },
            success: function(result){ 
                 if(result.status == AJAX_SUCCESS){
                    $('#Offer-branch_id').html('');
                    $('#Offer-branch_id').append($('<option>', 'Select branch name','</option>'));
                    $.each(result.data,function(key,title)
                    {  
                        $('#Offer-branch_id').append($('<option>', { value : key }).text(title));                       
                    });
                    $('.selectpicker').selectpicker('refresh');
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    });


    $('#Offer-branch_id').change(function()
    {   
        var offer_value = $('#offer_value').val();
        var offer_type = $('#offer_flat').val();
        var start_datetime = $('#start_date_picker').val();
        var end_datetime = $('#end_date_picker').val();
        $.ajax({
            url: "<?php echo e(route('get-item-by-branch')); ?>",
            type: 'post',
            data: { branch_id: $(this).val(),offer_value : offer_value,offer_type : offer_type,start_datetime : start_datetime, end_datetime : end_datetime },
            success: function(result){ 
                 if(result.status == AJAX_SUCCESS){
                    $('#Offer-item_id').html('');
                    $('#Offer-item_id').append($('<option>', 'Select item','</option>'));
                    $.each(result.data,function(key,title)
                    {  
                        $('#Offer-item_id').append($('<option>', { value : key }).text(title));                       
                    });
                    $('.selectpicker').selectpicker('refresh');
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    });

}); 
</script>

