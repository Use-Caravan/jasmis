<div class="detail-info">
    <div class="box-border wow fadeInUp">
        <h4 class="mb-0"><?php echo e(__('Info')); ?></h4>
    </div>

    <div class="bordered wow fadeInUp">
        <h5><?php echo e($branchDetails->branch_name); ?></h5>

        <p class="f18"><?php echo e($branchDetails->branch_cuisine); ?></p>

        <p><?php echo e($branchDetails->branch_description); ?></p>
    </div>

    <div class="row wow fadeInUp">
        <div class="col-md-6">
            <div class="bordered shadow-sm">
                <h4><?php echo e(__('Delivery Hours')); ?></h4>
                <table>
                    <tbody>
                        <?php $__currentLoopData = $branchDetails->time_info->delivery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e(date("N", strtotime(date('Y-m-d H:i:s'))) == $value->day_no ? 'active' : ''); ?>">
                            <td><?php echo e($value->day_name); ?></td>
                            <td><?php echo e($value->time_slot); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bordered shadow-sm">
                <h4><?php echo e(__('Pickup Hours')); ?></h4>
                <table>
                    <tbody>
                        <?php $__currentLoopData = $branchDetails->time_info->pickup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e(date("N", strtotime(date('Y-m-d H:i:s'))) == $value->day_no ? 'active' : ''); ?>">
                            <td><?php echo e($value->day_name); ?></td>
                            <td><?php echo e($value->time_slot); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="full_row wow fadeInUp padding-15  shadow-sm mt-15">
        <ul class="detail-price reset">
            <li>
                <div class="icon"><i class="fee"></i></div>
                <div class="name"><?php echo e(__('Delivery fee')); ?></div>
                <div class="price"><?php echo e($branchDetails->delivery_cost); ?></div>
            </li>
            <li>
                <div class="icon"><i class="material-icons">access_time</i></div>
                <div class="name"><?php echo e(__('Pickup Time')); ?></div>
                <div class="price"><?php echo e($branchDetails->pickup_time." Mins"); ?></div>
            </li>
            <li>
                <div class="icon"><i class="material-icons">access_time</i></div>
                <div class="name"><?php echo e(__('Delivery Time')); ?></div>
                <div class="price"><?php echo e($branchDetails->delivery_time." Mins"); ?></div>
            </li>
            <li>
                <div class="icon"><i class="min_ord"></i></div>
                <div class="name"><?php echo e(__('Min Order')); ?></div>
                <div class="price"><?php echo e($branchDetails->min_order_value); ?></div>
            </li>
            <li>
                <div class="icon"><i class="fee"></i></div>
                <div class="name"><?php echo e(__('Payment')); ?></div>
                <div class="price"><?php echo e($branchDetails->payment_option); ?></div>
            </li>
        </ul>
    </div>

    <div class="full_row wow fadeInUp address mt-15">
        <h4><i class="icon-location-pin" aria-hidden="true"></i> <?php echo e(__('Address')); ?></h4>
        <address>
            <?php echo e($branchDetails->branch_address); ?>

            
        </address>
    </div>

</div>