<?php $__env->startSection('content'); ?>

    <!-- listing restaurant -->

    <section class="order-confirmation">
        <div class="container">
            <!-- breadcums -->
            <div class="breadcums wow fadeInUp">
                <ul class="reset">
                    <li><a href="<?php echo e(route('frontend.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                    <li><a href="<?php echo e(route('frontend.branch.index')); ?>"><?php echo e(__('Restaurants')); ?></a></li>
                    
                    
                    <li><span><?php echo e(__('Order Confirmation')); ?></span></li>
                </ul>
            </div>
            <!-- breadcums -->
            <!-- complete box -->

            <div class="complete-box">

                <div class="full_row">
                    <div class="bg_style wow zoomIn" style="background-image:url(<?php echo e(FileHelper::loadImage($order->branch_logo)); ?>);"></div>
                </div>
                <div class="full_row">
                    <img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/smile.png')); ?>" class="wow zoomIn">
                </div>

                <h2 class="wow fadeInUp"><?php echo e(__('Order Placed Successfully')); ?>.</h2>
                <p class="sub wow fadeInUp"><?php echo e(__('Order ID')); ?>:<span>#<?php echo e($order->order_number); ?></span></p>
                <p class="sub wow fadeInUp"><?php echo e(__('Order Amount')); ?>:<span><?php echo e(Common::currency($order->order_total)); ?></span></p>
                <div class="divider "></div>
                <p class="breaks wow fadeInUp"><?php echo e(__('A confirmation email has been sent to')); ?> <span><?php echo e($order->user_email); ?></span></p>

                
                <?php if(Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CUSTOMER): ?>
                    <a class="shape-btn wow fadeInUp loader shape1" href="<?php echo e(route('frontend.myorder')); ?>"><span class="shape"><?php echo e(__('Go to Orders')); ?></span></a>
                <?php else: ?>
                    <a class="shape-btn wow fadeInUp loader shape1" href="<?php echo e(route('frontend.signout')); ?>"><span class="shape"><?php echo e(__('Exit Corporate')); ?></span></a>
                <?php endif; ?>

            </div>

            <!-- complete box -->
        </div>
    </section>

    <!-- listing restaurant -->
<?php $__env->stopSection(); ?>
    
<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>