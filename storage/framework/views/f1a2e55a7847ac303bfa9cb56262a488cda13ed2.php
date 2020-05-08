<?php $__env->startSection('content'); ?>
<section class="padd-20">
    <div class="container">
        <!-- breadcums -->
        <div class="breadcums mb-0 wow fadeInUp">
            <ul class="reset">
                <li><a href="<?php echo e(route('frontend.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                <li><span><?php echo e(__('Loyalty Points')); ?></span></li>
            </ul>
        </div>
        <!-- breadcums -->
    </div>
</section>
<section class="myaccount-page">
    <div class="container">
        <?php echo $__env->make('frontend.layouts.partials._profile-section', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="border-boxed">
                <div class="full_row">
                    <?php echo $__env->make('frontend.layouts.partials._profile_sidemenu', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <div class="account-content">
                <h2 class="account-title wow fadeInUp"><?php echo e(__('Loyalty loyalty-points')); ?></h2>

            <!-- box -->
                    <div class="loyalty_box wow fadeInUp">
                        <div class="icons"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/icon-loyal.png')); ?>"></div>
                        <div class="full_row">
                            <h4><?php echo e(__('You Have Collected')); ?></h4>
                            <div class="amount" id="redeem_amount">
                                <?php echo e(Auth::guard(GUARD_USER)->user()->loyalty_points); ?>

                            </div>
                        </div>
                        <div class="full_row">
                            <img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/level1.png')); ?>">
                            <h5 class="level"><?php echo e(__('Level')); ?> : <span id="loyaltyname"><?php echo e($loyaltyLevelName->data->loyalty_level_name); ?></span></h5>
                            <a href="javascript:void(0);" class="link"><?php echo e(__('Redeem Reward Points ?')); ?></a>
                        </div>
                    </div>
                    <!-- box -->
                    <!-- row -->
                    <div class="full_row reedam_reward wow fadeInUp">
                        <h4><?php echo e(__('Redeem Reward Points')); ?> </h4>
                        <form action="<?php echo e(url(route('frontend.redeempoint'))); ?>" id="redeem-form" method="POST">
                        <div class="full_row">
                            <div class="form-group">
                                <input type="text" id="redeem_points" name="points" class="form-control" value="" placeholder="0.00">
                            </div>
                            <button class="shape-btn shape1" data-target="#warning"  data-toggle="modal"><span class="shape"><?php echo e(__('Proceed')); ?></span></button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<!-- row -->

                    
<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>