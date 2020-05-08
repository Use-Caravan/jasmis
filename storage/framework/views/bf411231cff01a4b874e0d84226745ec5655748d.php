<?php if($vocher_for === 1): ?>
<div class="modal-header text-center">
    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
    <h5 class="modal-title"><?php echo e(__('Branch Vouchers')); ?></h5>
</div>
<div class="modal-body voucher_pop">
    <ul class="offfer_ul">        
        <?php $__currentLoopData = $vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
            <div class="img" style="background: url(<?php echo e($value->vendor_logo); ?>) no-repeat center center"></div>
            <p><?php echo e($value->offer_title); ?></p>
            <span><?php echo e($value->offer_expiry_msg); ?></span>
            <button type="submit" class="shape-btn pull-right use_voucher" voucher_code="<?php echo e($value->promo_code); ?>"><span class="shape">Use Code</span></button>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php elseif($vocher_for === 2): ?>
<div class="modal-header text-center">
    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
    <h5 class="modal-title"><?php echo e(__('Corporate Vouchers')); ?></h5>
</div>
<div class="modal-body voucher_pop">
    <ul class="offfer_ul">        
        <?php $__currentLoopData = $vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
            <div class="img" style="background: url(<?php echo e(FileHelper::loadImage($value->offer_banner)); ?>) no-repeat center center"></div>
            <p><?php echo e($value->offer_name); ?></p>
            <?php $offerTypeText = ($value->offer_type === 1) ? $value->offer_level." Quantity" : Common::currency($value->offer_level)." amount" ?>
            <span><?php echo e($value->offer_value."% offer, If you purchase more than ".$offerTypeText); ?></span>
            <button type="submit" class="shape-btn pull-right use_voucher" voucher_code="<?php echo e($value->corporate_offer_key); ?>"><span class="shape">Use Code</span></button>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>