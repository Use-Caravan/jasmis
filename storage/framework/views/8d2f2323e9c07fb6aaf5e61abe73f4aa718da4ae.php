<div class="account-links">
    <ul class="reset wow fadeInUp">
        <li> <a class="<?php echo e((Route::currentRouteName() == "frontend.myorder") ? "active" : ''); ?>" href="<?php echo e(route('frontend.myorder')); ?>"><i class="icon-notebook"></i> <?php echo e(__('My Orders')); ?>  </a></li>
        <li> <a class="<?php echo e((Route::currentRouteName() == "address.index") ? "active" : ''); ?>" href="<?php echo e(route('address.index')); ?>"><i class="icon-location-pin"></i> <?php echo e(__('Address Book')); ?>  </a></li>
        <li> <a class="<?php echo e((Route::currentRouteName() == "frontend.wishlist") ? "active" : ''); ?>" href="<?php echo e(route('frontend.wishlist')); ?>"><i class="icon-heart"></i> <?php echo e(__('Favourite Restaurants')); ?> </a></li>
        <li> <a class="<?php echo e((Route::currentRouteName() == "frontend.wallet") ? "active" : ''); ?>" href="<?php echo e(route('frontend.wallet')); ?>"><i class="icon-wallet"></i> <?php echo e(__('C wallet')); ?>  </a></li>
        <li> <a class="<?php echo e((Route::currentRouteName() == "frontend.loyalty-points") ? "active" : ''); ?>" href="<?php echo e(route('frontend.loyalty-points')); ?>"><i class="icon-star"></i> <?php echo e(__('Loyalty Points')); ?>  </a></li>
        <li> <a class="<?php echo e((Route::currentRouteName() == "frontend.signout") ? "active" : ''); ?>" href="<?php echo e(route('frontend.signout')); ?>"><i class="icon-logout"></i> <?php echo e(__('Logout')); ?>  </a></li>
    </ul>
</div>                