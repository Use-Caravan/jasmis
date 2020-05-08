<?php echo e(Form::open(['url' => $url, 'id' => 'loyaltypoint-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ])); ?>

    <div class="box-body">
        
        <div class="form-group <?php echo e(($errors->has("from_amount")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
                <?php echo e(Form::label("from_amount", __('admincrud.From Amount'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("from_amount", $model->from_amount, ['class' => 'form-control'])); ?> 
                <?php if($errors->has("from_amount")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("from_amount")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("to_amount")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
                <?php echo e(Form::label("to_amount", __('admincrud.To Amount'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("to_amount", $model->to_amount, ['class' => 'form-control'])); ?> 
                <?php if($errors->has("to_amount")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("to_amount")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("point")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
                <?php echo e(Form::label("point", __('admincrud.Point'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("point", $model->point, ['class' => 'form-control'])); ?> 
                <?php if($errors->has("point")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("point")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group radio_group<?php echo e(($errors->has("status")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                                          
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
        <?php echo e(Html::link(route('loyaltypoint.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
   
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>

<?php echo JsValidator::formRequest('App\Http\Requests\Admin\LoyaltyPointRequest', '#loyaltypoint-form'); ?>


