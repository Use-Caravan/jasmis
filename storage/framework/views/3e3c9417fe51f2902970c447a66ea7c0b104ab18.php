<?php echo e(Form::open(['url' => $url, 'id' => 'category-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST','enctype' => 'multipart/form-data' ])); ?>

    <div class="box-body">
        <div class="col-md-12">
            <div class="form-group radio_group<?php echo e(($errors->has("is_main_category")) ? 'has-error' : ''); ?>">                
                <?php echo e(Form::label("category_type", __('admincrud.Category Type'), ['class' => 'required'])); ?> 

                <?php echo e(Form::radio('is_main_category', MAIN_CATEGORY, ($model->is_main_category == MAIN_CATEGORY), ['class' => 'hide','id'=> 'maincategory' ])); ?>

                <?php echo e(Form::label("maincategory", __('admincrud.Main Category'), ['class' => ' radio'])); ?>


                <?php echo e(Form::radio('is_main_category', SUB_CATEGORY, ($model->is_main_category == SUB_CATEGORY), ['class' => 'hide','id'=>'subcategory'])); ?>

                <?php echo e(Form::label("subcategory", __('admincrud.Sub Category'), ['class' => 'radio'])); ?>

                <?php if($errors->has("is_main_category")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("is_main_category")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>  
        <div class="col-md-12 <?php echo e(($model->is_main_category != SUB_CATEGORY || $model->is_main_category = '') ? 'hide' : ''); ?> " id="main_category_id">
            <div class="form-group <?php echo e(($errors->has("main_category_id")) ? 'has-error' : ''); ?>">            
            <?php echo e(Form::label("main_category_id", __('admincrud.Main Category'), ['class' => 'required'])); ?> 
            <?php echo e(Form::select('main_category_id', $mainCategories, $model->main_category_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Category'),'id' => 'category_id'] )); ?>

                <?php if($errors->has("main_category_id")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("main_category_id")); ?></span>
                <?php endif; ?> 
            </div>
        </div>         
        <ul class="nav nav-tabs full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                             
                <li <?php if($key == App::getLocale()): ?> class="active" <?php endif; ?> haserror="<?php echo e($errors->has("category_name.$key")); ?>"> 
                    <a data-toggle="tab" href="#tab<?php echo e($key); ?>"><?php echo e($language); ?> </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <div class="tab-content full_row">            
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div id="tab<?php echo e($key); ?>" class="tab-pane fade <?php if($key == App::getLocale()): ?> active in <?php endif; ?>">
                    <div class="form-group <?php echo e(($errors->has("category_name.$key")) ? 'has-error' : ''); ?>" >
                        <div class="col-md-12">
                            <?php echo e(Form::label("category_name[$key]", __('admincrud.Category Name'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text("category_name[$key]", $modelLang['category_name'][$key], ['class' => 'form-control'])); ?> 
                            <?php if($errors->has("category_name.$key")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("category_name.$key")); ?></span>
                            <?php endif; ?>                    
                        </div>
                    </div>                    
                </div>                
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="form-group <?php echo e(($errors->has('category_image')) ? 'has-error' : ''); ?>">
            <div class="col-md-12">
                <?php echo e(Form::label("category_image", __('admincrud.Category Image'))); ?>

                <?php echo e(Form::file('category_image', ['class' => 'form-control','placeholder' => __('admincrud.Category Image')]  )); ?>

                <?php if($errors->has("category_image")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("category_image")); ?></span>
                <?php endif; ?> 
            </div>
        </div>

        

         <!--tab-pane-->  
        <div class="form-group <?php echo e(($errors->has("sort_no")) ? 'has-error' : ''); ?>">
            <div class="col-sm-12">
                <?php echo e(Form::label('sort_no', __('admincrud.Sort No')), ['class' => 'required']); ?>            
                <?php echo e(Form::text('sort_no', $model->sort_no, ['class' => 'form-control'])); ?>

                <?php if($errors->has("sort_no")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("sort_no")); ?></span>
                <?php endif; ?>
            </div>
        </div>  
        <div class="col-md-12">
            <div class="form-group radio_group<?php echo e(($errors->has("status")) ? 'has-error' : ''); ?>">
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
        <?php echo e(Html::link(route('category.index'), __('admincommon.Cancel'),['class' => 'btn btn-default'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\CategoryRequest','#category-form'); ?>


<script>
$(document).ready(function(){
    $('input[type=radio][name=is_main_category]').change(function() {
        var checkedValue = $("input[type=radio][name=is_main_category]:checked").val();    
        if(checkedValue == <?php echo e(SUB_CATEGORY); ?>){
            $('#main_category_id').removeClass('hide');
        }else{
            $('#main_category_id').addClass('hide');
        }
        
    });
 
}); 
</script>