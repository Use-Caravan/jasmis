<?php echo e(Form::open(['url' => $url, 'id' => 'loyaltylevel-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST', 'files' => 1 ])); ?>

    <div class="box-body">
        <ul class="nav nav-tabs full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                             
                <li <?php if($key == App::getLocale()): ?> class="active" <?php endif; ?> haserror="<?php echo e($errors->has("country_name.$key")); ?>"> 
                    <a data-toggle="tab" href="#tab<?php echo e($key); ?>"><?php echo e($language); ?> </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <div class="tab-content full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="tab<?php echo e($key); ?>" class="tab-pane fade <?php if($key == App::getLocale()): ?> active in <?php endif; ?>">
                <div class="form-group <?php echo e(($errors->has("loyalty_level_name.$key")) ? 'has-error' : ''); ?>"> 
                    <div class="col-sm-12">                   
                        <?php echo e(Form::label("loyalty_level_name[$key]", __('admincrud.Loyalty Level Name'), ['class' => 'required'])); ?>

                        <?php echo e(Form::text("loyalty_level_name[$key]", $modelLang['loyalty_level_name'][$key], ['class' => 'form-control'])); ?>

                        <?php if($errors->has("loyalty_level_name.$key")): ?>
                            <span class="help-block error-help-block"><?php echo e($errors->first("loyalty_level_name.$key")); ?></span>
                        <?php endif; ?>                    
                    </div>
                </div>
            </div> <!--tab-pane-->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="form-group <?php echo e(($errors->has("from_point")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
                <?php echo e(Form::label("from_point", __('admincrud.From Point'),['class' => 'required'])); ?>

                <?php echo e(Form::text("from_point", $model->from_point, ['class' => 'form-control'])); ?>

                <?php if($errors->has("from_point")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("from_point")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("to_point")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
                <?php echo e(Form::label("to_point", __('admincrud.To Point'),['class' => 'required'])); ?>

                <?php echo e(Form::text("to_point", $model->to_point, ['class' => 'form-control'])); ?>

                <?php if($errors->has("to_point")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("to_point")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("redeem_amount_per_point")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
                <?php echo e(Form::label("redeem_amount_per_point", __('admincrud.Redeem Amount Per Point'),['class' => 'required'])); ?>

                <?php echo e(Form::text("redeem_amount_per_point", $model->redeem_amount_per_point, ['class' => 'form-control'])); ?>

                <?php if($errors->has("redeem_amount_per_point")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("redeem_amount_per_point")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>  
        <div class="form-group <?php echo e(($errors->has("card_image")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
            <?php echo e(Form::label("card_image", __('admincrud.Card Image')." ( 550W x 356H )", ['class' => (!$model->exists) ? 'required' : ''])); ?>

            <?php echo e(Form::file("card_image", ['class' => 'form-control',"accept" => "image/*"])); ?>

            <?php if($errors->has("card_image")): ?>
                <span class="help-block error-help-block"><?php echo e($errors->first("card_image")); ?></span>
            <?php endif; ?>  
            </div>
        </div>
        
        <?php if($model->exists): ?>
        <div class = "clearfix">
        <div class="form-group <?php echo e(($errors->has("card_image")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
            <?php echo e(Form::label("card_image", __('admincrud.Exist Image')."")); ?>

            <img src="<?php echo e(FileHelper::loadImage($model->card_image)); ?>" style="width: 150px;">
            </div>
        </div>
        <?php endif; ?>
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
        <?php echo e(Html::link(route('loyaltylevel.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\LoyaltyLevelRequest', '#loyaltylevel-form'); ?>