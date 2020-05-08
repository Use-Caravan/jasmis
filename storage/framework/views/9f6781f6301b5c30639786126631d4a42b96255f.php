<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <h3 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Delivery boy Configuration'); ?></h3>
          </div>                             
          <?php  
            $currentUrlWithEnv = \Request::fullUrl();
            $currentUrl = \URL::current();
          ?>
          <?php echo e(Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ])); ?>          
          <?php echo e(Form::hidden('config_name',DELIVERY_BOY)); ?>

            <div class="box-body">                 
                <div class="form-group <?php echo e(($errors->has('order_accept_time_limit')) ? 'has-error' : ''); ?>">
                    <div class="col-md-12">
                        <?php echo e(Form::label("order_accept_time_limit", __('admincrud.Order Accept Time Limit'), ['class' => 'required'])); ?>

                        <?php echo e(Form::text('order_accept_time_limit', config('webconfig.order_accept_time_limit'), ['class' => 'form-control','placeholder' => __('admincrud.Order Accept Time Limit') ]  )); ?>

                        <?php if($errors->has("order_accept_time_limit")): ?>
                            <span class="help-block error-help-block"><?php echo e($errors->first("order_accept_time_limit")); ?></span>
                        <?php endif; ?> 
                    </div>
                </div>
                <div class="form-group <?php echo e(($errors->has('request_radius')) ? 'has-error' : ''); ?>">
                    <div class="col-md-12">
                        <?php echo e(Form::label("request_radius", __('admincrud.Order Request Radius'), ['class' => 'required'])); ?>

                        <?php echo e(Form::text('request_radius', config('webconfig.request_radius'), ['class' => 'form-control','placeholder' => __('admincrud.Order Request Radius') ]  )); ?>

                        <?php if($errors->has("request_radius")): ?>
                            <span class="help-block error-help-block"><?php echo e($errors->first("request_radius")); ?></span>
                        <?php endif; ?> 
                    </div>
                </div> 
                <div class="form-group <?php echo e(($errors->has('order_assign_type')) ? 'has-error' : ''); ?>">
                    <div class="col-md-12">
                        <?php echo e(Form::label("order_assign_type", __('admincrud.Order Assign Type'), ['class' => 'required'])); ?>

                        <?php echo e(Form::select('order_assign_type', [ORDER_ASSIGN_TYPE_AUTOMATIC => 'Automatic', ORDER_ASSIGN_TYPE_MANUAL => 'Manual'], config('webconfig.order_assign_type'), ['class' => 'form-control','placeholder' => __('admincrud.Order Assign Type')]  )); ?>

                        <?php if($errors->has("order_assign_type")): ?>
                            <span class="help-block error-help-block"><?php echo e($errors->first("order_assign_type")); ?></span>
                        <?php endif; ?> 
                    </div>
                </div>
                <?php if($currentUrlWithEnv === $currentUrl.'?env=dev'): ?>  
                <div class="form-group <?php echo e(($errors->has('deliveryboy_url')) ? 'has-error' : ''); ?>">
                    <div class="col-md-12">
                        <?php echo e(Form::label("deliveryboy_url", __('admincrud.Delivery boy URL'), ['class' => 'required'])); ?>

                        <?php echo e(Form::text('deliveryboy_url',  config('webconfig.deliveryboy_url') , ['class' => 'form-control','placeholder' => __('admincrud.Delivery boy URL')]  )); ?>

                        <?php if($errors->has("deliveryboy_url")): ?>
                            <span class="help-block error-help-block"><?php echo e($errors->first("deliveryboy_url")); ?></span>
                        <?php endif; ?> 
                    </div>
                </div>
                <div class="form-group <?php echo e(($errors->has('company_id')) ? 'has-error' : ''); ?>">
                    <div class="col-md-12">
                        <?php echo e(Form::label("company_id", __('admincrud.Company ID'), ['class' => 'required'])); ?>

                        <?php echo e(Form::text('company_id',  config('webconfig.company_id') , ['class' => 'form-control','placeholder' => __('admincrud.Company ID') ]  )); ?>

                        <?php if($errors->has("company_id")): ?>
                            <span class="help-block error-help-block"><?php echo e($errors->first("company_id")); ?></span>
                        <?php endif; ?> 
                    </div>
                </div>                                                        
                <div class="form-group <?php echo e(($errors->has('auth_token')) ? 'has-error' : ''); ?>">
                    <div class="col-md-12">
                        <?php echo e(Form::label("auth_token", __('admincrud.Delivery boy Auth Token'), ['class' => 'required'])); ?>

                        <?php echo e(Form::textarea('auth_token',  config('webconfig.auth_token') , ['class' => 'form-control','placeholder' => __('admincrud.Delivery boy Auth Token') ]  )); ?>

                        <?php if($errors->has("auth_token")): ?>
                            <span class="help-block error-help-block"><?php echo e($errors->first("auth_token")); ?></span>
                        <?php endif; ?> 
                    </div>
                </div>   
                <?php endif; ?>                          
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <?php echo e(Html::link(route('admin-app-settings'), __('admincommon.Cancel'),['class' => 'btn btn-default'])); ?>        
                <?php echo e(Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

            </div>
            <!-- /.box-footer -->
            <?php echo e(Form::close()); ?>

            <?php echo JsValidator::formRequest('App\Http\Requests\Admin\DeliveryboySettingRequest', '#app-settings-form'); ?>

        </div>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>