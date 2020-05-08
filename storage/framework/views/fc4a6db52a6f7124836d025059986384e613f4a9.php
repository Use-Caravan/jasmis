<?php echo e(Form::open(['url' => $url, 'id' => 'delivery-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ])); ?>

    <div class="box-body">
        
        <div class="form-group <?php echo e(($errors->has("from_km")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
                <?php echo e(Form::label("from_km", __('admincrud.From Kilometer'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("from_km", $model->from_km, ['class' => 'form-control'])); ?> 
                <?php if($errors->has("from_km")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("from_km")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("to_km")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
                <?php echo e(Form::label("to_km", __('admincrud.To Kilometer'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("to_km", $model->to_km, ['class' => 'form-control'])); ?> 
                <?php if($errors->has("to_km")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("to_km")); ?></span>
                <?php endif; ?>                    
            </div>
        </div>
        <div class="form-group <?php echo e(($errors->has("price")) ? 'has-error' : ''); ?>">                    
            <div class="col-md-12">                          
                <?php echo e(Form::label("price", __('admincrud.Price'), ['class' => 'required'])); ?>

                <?php echo e(Form::text("price", $model->price, ['class' => 'form-control'])); ?> 
                <?php if($errors->has("price")): ?>
                    <span class="help-block error-help-block"><?php echo e($errors->first("price")); ?></span>
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
        <?php echo e(Html::link(route('deliverycharge.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active'])); ?>        
        <?php echo e(Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right'])); ?>

    </div>
   
  <!-- /.box-footer -->
<?php echo e(Form::close()); ?>

<?php echo JsValidator::formRequest('App\Http\Requests\Admin\DeliveryChargeRequest', '#delivery-form'); ?>


