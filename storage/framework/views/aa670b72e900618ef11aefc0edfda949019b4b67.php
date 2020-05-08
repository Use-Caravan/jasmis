<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
          <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <h3 class="box-title"><?php echo app('translator')->getFromJson('admincrud.App Configuration'); ?></h3>
          </div>            
          <?php echo e(Form::open(['url' => route('admin-app-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ])); ?>          
          <?php echo e(Form::hidden('config_name',CONFIG_APP)); ?>

            <div class="box-body"> 
                <div class="col-md-6">
                    <div class="form-group <?php echo e(($errors->has('app_name')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_name", __('admincrud.App Name'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('app_name', config('webconfig.app_name'), ['class' => 'form-control','placeholder' => __('admincrud.App Name')]  )); ?>

                            <?php if($errors->has("app_name")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_name")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_description')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_description", __('admincrud.App Description'), ['class' => 'required'])); ?>

                            <?php echo e(Form::textarea('app_description', config('webconfig.app_description'), ['class' => 'form-control','placeholder' => __('admincrud.App Description') ]  )); ?>

                            <?php if($errors->has("app_description")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_description")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_meta_keywords')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_meta_keywords", __('admincrud.App Meta Keywords'), ['class' => 'required'])); ?>

                            <?php echo e(Form::textarea('app_meta_keywords', config('webconfig.app_meta_keywords'), ['class' => 'form-control','placeholder' => __('admincrud.App Meta Keywords') ]  )); ?>

                            <?php if($errors->has("app_meta_keywords")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_meta_keywords")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_meta_description')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_meta_description", __('admincrud.App Meta Description'), ['class' => 'required'])); ?>

                            <?php echo e(Form::textarea('app_meta_description', config('webconfig.app_meta_description'), ['class' => 'form-control','placeholder' => __('admincrud.App Meta Description') ]  )); ?>

                            <?php if($errors->has("app_meta_description")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_meta_description")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('play_store_link')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("play_store_link", __('admincrud.App Playstore Link'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('play_store_link', config('webconfig.play_store_link'), ['class' => 'form-control','placeholder' => __('admincrud.App Playstore Link')]  )); ?>

                            <?php if($errors->has("play_store_link")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("play_store_link")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_store_link')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_store_link", __('admincrud.App store Link'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('app_store_link', config('webconfig.app_store_link'), ['class' => 'form-control','placeholder' => __('admincrud.App store Link')]  )); ?>

                            <?php if($errors->has("app_store_link")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_store_link")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    
                     <div class="form-group <?php echo e(($errors->has('app_address')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_address", __('admincrud.App Address'), ['class' => 'required'])); ?>

                            <?php echo e(Form::textarea('app_address', config('webconfig.app_address'), ['class' => 'form-control','placeholder' => __('admincrud.App Address') ]  )); ?>

                            <?php if($errors->has("app_address")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_address")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>                                       
                </div>
                <div class="col-md-6">
                    <div class="form-group <?php echo e(($errors->has('app_logo')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_logo", __('admincrud.App Logo')."  (147 X 37)", ['class' => 'required'])); ?>

                            <?php echo e(Form::file('app_logo', ['class' => 'form-control','placeholder' => __('admincrud.App Logo')]  )); ?>

                            <?php if($errors->has("app_logo")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_logo")); ?></span>
                            <?php endif; ?> 
                            <ul class="uploads reset">
                                <li class="uploaded">                                
                                    <label for="upload1" style="background:url(<?php echo e(FileHelper::loadImage(config('webconfig.app_logo'))); ?>)"></label>                                
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_favicon')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_favicon", __('admincrud.App Favicon'), ['class' => 'required'])); ?>

                            <?php echo e(Form::file('app_favicon', ['class' => 'form-control','placeholder' => __('admincrud.App Favicon')]  )); ?>

                            <?php if($errors->has("app_favicon")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_favicon")); ?></span>
                            <?php endif; ?> 
                            <ul class="uploads reset">
                                <li class="uploaded">                                
                                    <label for="upload1" style="background:url(<?php echo e(FileHelper::loadImage(config('webconfig.app_favicon'))); ?>)"></label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_email')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_email", __('admincrud.App Email'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('app_email', config('webconfig.app_email'), ['class' => 'form-control','placeholder' => __('admincrud.App Email')]  )); ?>

                            <?php if($errors->has("app_email")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_email")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_contact_number')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_contact_number", __('admincrud.App Contact Number'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('app_contact_number', config('webconfig.app_contact_number'), ['class' => 'form-control','placeholder' => __('admincrud.App Contact Number')]  )); ?>

                            <?php if($errors->has("app_contact_number")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_contact_number")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_primary_color')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_primary_color", __('admincrud.App Primary Color'), ['class' => 'required '])); ?>

                            <?php echo e(Form::text('app_primary_color', config('webconfig.app_primary_color'), ['class' => 'jscolor form-control','placeholder' => __('admincrud.App Primary Color')]  )); ?>

                            <?php if($errors->has("app_primary_color")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_primary_color")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>    
                    <div class="form-group <?php echo e(($errors->has('map_key')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("map_key", __('admincrud.App Map Key'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('map_key', config('webconfig.map_key'), ['class' => 'form-control','placeholder' => __('admincrud.App Map Key')]  )); ?>

                            <?php if($errors->has("map_key")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("map_key")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_latitude')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_latitude", __('admincrud.App Default Latitude'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('app_latitude', config('webconfig.app_latitude'), ['class' => 'form-control','placeholder' => __('admincrud.App Default Latitude')]  )); ?>

                            <?php if($errors->has("app_latitude")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_latitude")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('app_longitude')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("app_longitude", __('admincrud.App Default Longitude'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('app_longitude', config('webconfig.app_longitude'), ['class' => 'form-control','placeholder' => __('admincrud.App Default Longitude')]  )); ?>

                            <?php if($errors->has("app_longitude")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("app_longitude")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>                                    
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <?php echo e(Html::link(route('admin-app-settings'), __('admincommon.Cancel'),['class' => 'btn btn-default'])); ?>        
                <?php echo e(Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

            </div>
            <!-- /.box-footer -->
            <?php echo e(Form::close()); ?>

            <?php echo JsValidator::formRequest('App\Http\Requests\Admin\ConfigurationRequest', '#app-settings-form'); ?>

        </div>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>