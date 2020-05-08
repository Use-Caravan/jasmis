<!-- group elements -->
<?php if($delivery_type == 2): ?>
<div class="dl_group">
    <h4 class="heading_border fadeInUp">Delivery type</h4>
    <div class="full_row fadeInUp">                
        <input type="radio" class="radio" name="delivery_type" value="<?php echo e(DELIVERY_TYPE_ASAP); ?>" id="DT<?php echo e(DELIVERY_TYPE_ASAP); ?>">
        <label class="radio" for="DT<?php echo e(DELIVERY_TYPE_ASAP); ?>">ASAP</label>                
        <input type="radio" class="radio" name="delivery_type" value="<?php echo e(DELIVERY_TYPE_PRE_ORDER); ?>" id="DT<?php echo e(DELIVERY_TYPE_PRE_ORDER); ?>">
        <label class="radio" for="DT<?php echo e(DELIVERY_TYPE_PRE_ORDER); ?>">Pre Order</label>        
    </div>
</div>
<?php endif; ?>
<?php if(empty($days) || $days == null): ?>
Service is not available
<?php else: ?> 
<!-- group elements -->
<div class="dl_group" id="pre_order_div" style="<?php echo e(($delivery_type == 2) ? 'display:none;' : ''); ?>">
    <h4 class="heading_border fadeInUp">Date & Time</h4>
    <div class="row fadeInUp">
        <div class="col-md-6">
            <div class="form-group">
                <label class="icons"><i class="material-icons">date_range</i></label>
                <select class="form-control" placeholder="Selected Date" prop="date" name="delivery_date" id="delivery_date">
                    <option>Select Date</option>
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value['date']); ?>"><?php echo e($value['date']); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="icons"><i class="material-icons">access_time</i></label>
                <select class="form-control" name="delivery_time" id="delivery_time" prop="time" placeholder="Selected Time">
                    <option>Select Time</option>
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $value['times']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                            
                            <option style="display:none" data-date="<?php echo e($value['date']); ?>" value="<?php echo e($time); ?>"><?php echo e($time); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>
</div>
<!-- group elements -->
<?php endif; ?>