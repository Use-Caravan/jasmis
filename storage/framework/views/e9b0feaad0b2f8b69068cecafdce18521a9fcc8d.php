<?php echo e(Form::open(['url' => $url, 'id' => 'user-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ])); ?>

    <div class="box-body">
        <div class="form-group <?php echo e(($errors->has("first_name")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
                <?php echo e(Form::label("first_name", __('admincommon.First Name'),['class' => 'required'])); ?>

                <?php echo e(Form::text("first_name", $model->first_name, ['class' => 'form-control'])); ?>

                <?php if($errors->has("first_name")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("first_name")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("last_name")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
                <?php echo e(Form::label("last_name", __('admincommon.Last Name'),['class' => 'required'])); ?>

                <?php echo e(Form::text("last_name", $model->last_name, ['class' => 'form-control'])); ?>

                <?php if($errors->has("last_name")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("last_name")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("username")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
                <?php echo e(Form::label("username", __('admincommon.User Name'),['class' => 'required'])); ?>

                <?php echo e(Form::text("username", $model->username, ['class' => 'form-control'])); ?>

                <?php if($errors->has("username")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("username")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("email")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
                <?php echo e(Form::label("email", __('admincommon.Email'),['class' => 'required'])); ?>

                <?php echo e(Form::text("email", $model->email, ['class' => 'form-control'])); ?>

                <?php if($errors->has("email")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("email")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="col-md-12">  
            <div class="form-group <?php echo e(($errors->has("password")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("password", __('admincommon.Password'), ($model->exists)?'':['class' => 'required'])); ?>

                <?php echo e(Form::password("password", ['class' => 'form-control'])); ?> 
                <?php if($errors->has("password")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("password")); ?></span>
                <?php endif; ?>                    
            </div>
        </div> 
         <div class="col-md-12">  
            <div class="form-group <?php echo e(($errors->has("confirm_password")) ? 'has-error' : ''); ?>">                    
                <?php echo e(Form::label("confirm_password", __('admincommon.Confirm Password'), ($model->exists)?'':['class' => 'required'])); ?>

                <?php echo e(Form::password("confirm_password", ['class' => 'form-control'])); ?> 
                <?php if($errors->has("confirm_password")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("confirm_password")); ?></span>
                <?php endif; ?>                    
            </div>
        </div> 
        <div class="form-group <?php echo e(($errors->has("phone_number")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
                <?php echo e(Form::label("phone_number", __('admincrud.Phone Number'),['class' => 'required'])); ?>

                <?php echo e(Form::text("phone_number", $model->phone_number, ['class' => 'form-control'])); ?>

                <?php if($errors->has("phone_number")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("phone_number")); ?></span>
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
        <?php echo e(Html::link(route('user.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>


<?php echo JsValidator::formRequest('App\Http\Requests\Admin\UserRequest', '#user-form'); ?>