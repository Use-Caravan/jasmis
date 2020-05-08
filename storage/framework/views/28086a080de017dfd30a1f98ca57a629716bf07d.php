<?php echo e(Form::open(['url' => $url, 'id' => 'addresstype-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST', 'enctype' => "multipart/form-data"])); ?>

    <div class="box-body">
        <ul class="nav nav-tabs full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                             
                <li <?php if($key == App::getLocale()): ?> class="active" <?php endif; ?> haserror="<?php echo e($errors->has("banner_name.$key").$errors->has("banner_file.$key")); ?>"> 
                    <a data-toggle="tab" href="#tab<?php echo e($key); ?>"><?php echo e($language); ?> </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <div class="tab-content full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="tab<?php echo e($key); ?>" class="tab-pane fade <?php if($key == App::getLocale()): ?> active in <?php endif; ?>">
                <div class="form-group <?php echo e(($errors->has("banner_name.$key")) ? 'has-error' : ''); ?>"> 
                    <div class="col-sm-12">                   
                        <?php echo e(Form::label("banner_name[$key]", __('admincrud.Banner Name'), ['class' => 'required'])); ?>

                        <?php echo e(Form::text("banner_name[$key]", $modelLang['banner_name'][$key], ['class' => 'form-control'])); ?>

                        <?php if($errors->has("banner_name.$key")): ?>
                            <span class="help-block error-help-block"><?php echo e($errors->first("banner_name.$key")); ?></span>
                        <?php endif; ?>                    
                    </div>
                </div>
                <div class="form-group <?php echo e(($errors->has("banner_file.$key")) ? 'has-error' : ''); ?>">                    
                    <div class="col-md-12">                          
                    <?php echo e(Form::label("banner_file[$key]", __('admincrud.Banner Image')." ( 1170W x 170H - 1180W x 180H)", ['class' => (!$model->exists) ? 'required' : ''])); ?>

                    <?php echo e(Form::file("banner_file[$key]", ['class' => 'form-control',"accept" => "image/*"])); ?>

                    <?php if($errors->has("banner_file.$key")): ?>
                        <span class="help-block error-help-block"><?php echo e($errors->first("banner_file.$key")); ?></span>
                    <?php endif; ?>  
                    <?php if(isset($modelLang['banner_file'][$key]) && $modelLang['banner_file'][$key] != null): ?>
                        <ul class="uploads reset">
                            <li class="uploaded">                                
                                <label for="upload1" style="background:url(<?php echo e(FileHelper::loadImage($modelLang['banner_file'][$key])); ?>)"></label>
                            </li>
                        </ul>                   
                    <?php endif; ?>
                    </div>
                </div> 
            </div> <!--tab-pane-->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div> 

         <div class="form-group <?php echo e(($errors->has("redirect_url")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
                <?php echo e(Form::label("redirect_url", __('admincrud.Redirect URL'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("redirect_url", $model['redirect_url'], ['class' => 'form-control area_latitude'])); ?> 
                <?php if($errors->has("redirect_url")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("redirect_url")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group radio_group<?php echo e(($errors->has("is_home_banner")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                 
                <?php echo e(Form::label("is_home_banner", __('admincommon.Display in Home'),['class' => 'required'])); ?>         
                <?php $model->is_home_banner = ($model->exists) ? $model->is_home_banner : 0 ?>
                <?php echo e(Form::radio('is_home_banner', 1, ($model->is_home_banner == 1), ['class' => 'hide','id'=> 'ishomeon' ])); ?>

                <?php echo e(Form::label("ishomeon", __('admincommon.Yes'), ['class' => ' radio'])); ?>

                <?php echo e(Form::radio('is_home_banner', 0, ($model->is_home_banner == 0), ['class' => 'hide','id'=>'ishomeoff'])); ?>

                <?php echo e(Form::label("ishomeoff", __('admincommon.No'), ['class' => 'radio'])); ?>

                <?php if($errors->has("is_home_banner")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("is_home_banner")); ?></span>
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
        <?php echo e(Html::link(route('banner.index'), __('admincommon.Cancel'),['class' => 'btn btn-default'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\BannerRequest', '#addresstype-form'); ?>