<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <h3 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Currency Configuration'); ?></h3>                        
          </div>            
            <?php echo e(Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ])); ?>

            <?php echo e(Form::hidden('config_name',CONFIG_CURRENCY)); ?>            
            <div class="box-body"> 
                <div class="col-md-12">
                    <div class="form-group <?php echo e(($errors->has('currency_code')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("currency_code", __('admincrud.Currency Code'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('currency_code', config('webconfig.currency_code'), ['class' => 'form-control','placeholder' => __('admincrud.Currency Code') ]  )); ?>

                            <?php if($errors->has("currency_code")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("currency_code")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>
                    <div class="form-group <?php echo e(($errors->has('currency_symbol')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("currency_symbol", __('admincrud.Currency Symbol'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text('currency_symbol', config('webconfig.currency_symbol'), ['class' => 'form-control','placeholder' => __('admincrud.Currency Symbol')]  )); ?>

                            <?php if($errors->has("currency_symbol")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("currency_symbol")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>                    
                    <div class="form-group <?php echo e(($errors->has('currency_position')) ? 'has-error' : ''); ?>">
                        <div class="col-md-12">
                            <?php echo e(Form::label("currency_position", __('admincrud.Currency Position'), ['class' => 'required'])); ?>

                            <?php echo e(Form::select('currency_position', $model->currencyPositions(),config('webconfig.currency_position'), ['class' => 'form-control']  )); ?>

                            <?php if($errors->has("currency_position")): ?>
                                <span class="help-block error-help-block"><?php echo e($errors->first("currency_position")); ?></span>
                            <?php endif; ?> 
                        </div>
                    </div>                    
                </div>                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <?php echo e(Html::link(route('admin-currency-settings'), __('admincommon.Cancel'), ['class' => 'btn btn-default'])); ?>        
                <?php echo e(Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

            </div>
            <!-- /.box-footer -->
            <?php echo e(Form::close()); ?>

            <?php echo JsValidator::formRequest('App\Http\Requests\Admin\CurrencyConfigRequest', '#app-settings-form'); ?>

        </div>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>