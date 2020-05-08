<?php $__env->startSection('content'); ?>


<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <h3 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Mail Configuration'); ?></h3>            
            <?php echo e(Html::link(route('admin-test-mail'), __('admincrud.Send Test Mail'),['class' => 'btn btn-warning'])); ?>        
          </div>            
            <?php echo e(Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ])); ?>

            <?php echo e(Form::hidden('config_name',CONFIG_MAIL)); ?>

            <div class="box-body"> 
                <div class="col-md-12">
                    <div class="form-group <?php echo e(($errors->has('smtp_host')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("smtp_host", __('admincrud.SMTP Host'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('smtp_host', config('webconfig.smtp_host'), ['class' => 'form-control','placeholder' => __('admincrud.SMTP Host') ]  )); ?>

                            <?php if($errors->has("smtp_host")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("smtp_host")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('smtp_username')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("smtp_username", __('admincrud.SMTP Username'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('smtp_username', config('webconfig.smtp_username'), ['class' => 'form-control','placeholder' => __('admincrud.SMTP Username')]  )); ?>

                            <?php if($errors->has("smtp_username")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("smtp_username")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('smtp_password')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("smtp_password", __('admincrud.SMTP Password'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('smtp_password', config('webconfig.smtp_password'), ['class' => 'form-control','placeholder' => __('admincrud.SMTP Password')]  )); ?>

                            <?php if($errors->has("smtp_password")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("smtp_password")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('encryption')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("encryption", __('admincrud.Encryption Method'), ['class' => 'required'])); ?>

                            <?php echo e(Form::select('encryption', $model->encryptionTypes(), config('webconfig.encryption'), ['class' => 'form-control','placeholder' => __('admincrud.Encryption Method')]  )); ?>

                            <?php if($errors->has("encryption")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("encryption")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('port')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("port", __('admincrud.Mail Port'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('port', config('webconfig.port'), ['class' => 'form-control','placeholder' => __('admincrud.Mail Port')]  )); ?>

                            <?php if($errors->has("port")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("port")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>    
                    <div class="form-group radio_group">
                        <div class="col-md-12">                            
                            <?php echo e(Form::label("port", __('admincrud.Is SMTP Enabled'), ['class' => 'required'])); ?>

                            <?php echo e(Form::radio('is_smtp_enabled', 1, (config('webconfig.is_smtp_enabled') == 1), ['class' => 'hide', 'id' => 'port-Yes'])); ?>                            
                            <?php echo e(Form::label("port-Yes", __('admincommon.Yes'), ['class' => 'radio'])); ?>

                            <?php echo e(Form::radio('is_smtp_enabled', 2, (config('webconfig.is_smtp_enabled') == 2), ['class' => 'hide', 'id' => 'port-No'])); ?>                            
                            <?php echo e(Form::label("port-No", __('admincommon.No'), ['class' => 'radio'])); ?>

                        </div>
                    </div> <!--form_group-->                
                </div>                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <?php echo e(Html::link(route('admin-mail-settings'), __('admincommon.Cancel'), ['class' => 'btn btn-default'])); ?>        
                <?php echo e(Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

            </div>
            <!-- /.box-footer -->
            <?php echo e(Form::close()); ?>

            <?php echo JsValidator::formRequest('App\Http\Requests\Admin\MailConfigRequest', '#app-settings-form'); ?>

        </div>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>