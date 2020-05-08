<?php echo e(Form::open(['url' => $url, 'id' => 'cuisine-form', 'class' => 'form-horizontal', 'enctype' => "multipart/form-data", 'method' => ($model->exists) ? 'PUT' : 'POST' ])); ?>

    <div class="box-body">
        <ul class="nav nav-tabs full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                             
                <li class="<?php if($key == App::getLocale()): ?> active <?php endif; ?>" haserror="<?php echo e($errors->has("cuisine_name.$key")); ?>">
                    <a data-toggle="tab" href="#tab<?php echo e($key); ?>"><?php echo e($language); ?> </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <div class="tab-content full_row">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="tab<?php echo e($key); ?>" class="tab-pane fade <?php if($key == App::getLocale()): ?> active in <?php endif; ?>">
                <div class="form-group <?php echo e(($errors->has("cuisine_name.$key")) ? 'has-error' : ''); ?>">
                    <div class="col-sm-12">
                        <?php echo e(Form::label("cuisine_name[$key]", __('admincrud.Cuisine Name'), ['class' => 'required'])); ?>

                        <?php echo e(Form::text("cuisine_name[$key]", $modelLang['cuisine_name'][$key], ['class' => 'form-control'])); ?>

                        <?php if($errors->has("cuisine_name.$key")): ?>
                            <span class="help-block error-help-block"><?php echo e($errors->first("cuisine_name.$key")); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div> <!--tab-pane-->
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="form-group <?php echo e(($errors->has("sort_no")) ? 'has-error' : ''); ?>">
            <div class="col-sm-12">
                <?php echo e(Form::label('sort_no', __('admincrud.Sort No'))); ?>            
                <?php echo e(Form::text('sort_no', $model->sort_no, ['class' => 'form-control'])); ?>

                <?php if($errors->has("sort_no")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("sort_no")); ?></span>
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
        <?php echo e(Html::link(route('cuisine.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\CuisineRequest', '#cuisine-form');; ?>