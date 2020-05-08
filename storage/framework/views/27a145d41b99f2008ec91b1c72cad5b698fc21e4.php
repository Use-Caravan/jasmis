<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <h3 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Social Media Configuration'); ?></h3>                        
          </div>            
            <?php echo e(Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ])); ?>

            <?php echo e(Form::hidden('config_name',CONFIG_SOCIAL_MEDIA)); ?>

            <div class="box-body"> 
                <div class="col-md-12">
                    <div class="form-group <?php echo e(($errors->has('social_twitter')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("social_twitter", __('admincrud.Twitter'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('social_twitter', config('webconfig.social_twitter'), ['class' => 'form-control','placeholder' => __('admincrud.Twitter') ]  )); ?>

                            <?php if($errors->has("social_twitter")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("social_twitter")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('social_facebook')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("social_facebook", __('admincrud.Facebook'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('social_facebook', config('webconfig.social_facebook'), ['class' => 'form-control','placeholder' => __('admincrud.Facebook')]  )); ?>

                            <?php if($errors->has("social_facebook")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("social_facebook")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('social_instagram')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("social_instagram", __('admincrud.Instagram'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('social_instagram', config('webconfig.social_instagram'), ['class' => 'form-control','placeholder' => __('admincrud.Instagram')]  )); ?>

                            <?php if($errors->has("social_instagram")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("social_instagram")); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </div>                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <?php echo e(Html::link(route('admin-social-media-settings'), __('admincommon.Cancel'), ['class' => 'btn btn-default'])); ?>        
                <?php echo e(Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

            </div>
            <!-- /.box-footer -->
            <?php echo e(Form::close()); ?>

            <?php echo JsValidator::formRequest('App\Http\Requests\Admin\SocialMediaConfigRequest', '#app-settings-form'); ?>

        </div>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>