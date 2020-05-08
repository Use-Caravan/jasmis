<?php echo e(Form::open(['url' => $url, 'id' => 'deliveryboy-form', 'class' => 'form-horizontal', 'method' => $method ])); ?>

    <div class="box-body">        
        <div class="col-sm-6">
            <div class="form-group <?php echo e(($errors->has("name")) ? 'has-error' : ''); ?>"> 
                <?php echo e(Form::label("name", __('admincommon.User Name'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("name", $model->name, ['class' => 'form-control'])); ?>

                <?php if($errors->has("name")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("name")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo e(($errors->has("email")) ? 'has-error' : ''); ?>"> 
                                
                <?php echo e(Form::label("email", __('admincommon.Email'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("email", $model->email, ['class' => 'form-control'])); ?>

                <?php if($errors->has("email")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("email")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo e(($errors->has("phone_number")) ? 'has-error' : ''); ?>"> 
                                
                <?php echo e(Form::label("phone_number", __('admincommon.Mobile Number'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("phone_number", $model->phone_number, ['class' => 'form-control'])); ?>

                <?php if($errors->has("phone_number")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("phone_number")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo e(($errors->has("country")) ? 'has-error' : ''); ?>">                                 
                <?php echo e(Form::label("country", __('admincrud.Country Name'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("country", $model->country, ['class' => 'form-control'])); ?>

                <?php if($errors->has("country")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("country")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group <?php echo e(($errors->has("city")) ? 'has-error' : ''); ?>">                                 
                <?php echo e(Form::label("city", __('admincrud.City Name'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("city", $model->city, ['class' => 'form-control'])); ?>

                <?php if($errors->has("city")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("city")); ?></span>
                <?php endif; ?>                    
            </div>
        </div> 
        <div class="col-sm-6">
            <div class="form-group <?php echo e(($errors->has("address")) ? 'has-error' : ''); ?>">                                 
                <?php echo e(Form::label("address", __('admincommon.Address'), ['class' => 'required'])); ?>

                <?php echo e(Form::textarea("address", $model->address, ['class' => 'form-control'])); ?>

                <?php if($errors->has("address")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("address")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <?php if($model->_id == null): ?>
        <div class="col-sm-6">
            <div class="form-group <?php echo e(($errors->has("password")) ? 'has-error' : ''); ?>">                                 
                <?php echo e(Form::label("password", __('admincommon.Password'), ['class' => 'required'])); ?>

                <?php echo e(Form::password("password", ['class' => 'form-control'])); ?>

                <?php if($errors->has("password")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("password")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <?php endif; ?>
        <?php if($model->_id == null): ?>
        <div class="col-md-6">  
            <div class="form-group <?php echo e(($errors->has("confirm_password")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("confirm_password", __('admincommon.Confirm Password'), ['class' => 'required'])); ?>

                <?php echo e(Form::password("confirm_password", ['class' => 'form-control'])); ?> 
                <?php if($errors->has("confirm_password")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("confirm_password")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <?php endif; ?>
       
    </div>
  <!-- /.box-body -->
    <div class="box-footer">
        <?php echo e(Html::link(route('deliveryboy.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active'])); ?>        
        <?php echo e(Form::submit($method == 'PUT' ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\DeliveryboyRequest', '#deliveryboy-form'); ?>