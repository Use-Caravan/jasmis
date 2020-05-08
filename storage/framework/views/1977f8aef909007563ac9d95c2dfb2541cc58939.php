<!-- short description -->
    <section class="restaurant-short-information">
        <div class="container">
            <div class="box">
                <div class="box-top">
                                        
                    <?php echo Html::image($branchDetails->branch_logo,$branchDetails->branch_name,['style'=>'width:120px;height:120px;','class' => "img_fly bg_style wow zoomIn" ]);; ?>

                    
                    <h4 class="wow fadeInUp"><?php echo e($branchDetails->branch_name); ?></h4>
                    <p class="wow fadeInUp"><?php echo e($branchDetails->branch_cuisine); ?></p>
                    <p class="wow fadeInUp">Pay by: <?php echo e($branchDetails->payment_option); ?> </p>    
                    <p class="ui-enhance wow fadeInUp">
                        <span> <i class="material-icons">access_time</i> <?php echo e(__('Pickup Time')); ?>: <?php echo e($branchDetails->pickup_time.__("Mins")); ?></span>
                        <span> <i class="material-icons">access_time</i> <?php echo e(__('Delivery Time')); ?>: <?php echo e($branchDetails->delivery_time.__("Mins")); ?></span>
                    </p>
                </div>
                <p class="ui-enhance wow fadeInUp">
                    <span><i class="min_ord"></i> <?php echo e(__('Min Order')); ?>: <?php echo e($branchDetails->min_order_value); ?></span>
                    <span> <i class="fee"></i> <?php echo e(__('Delivery Fee')); ?>: <?php echo e($branchDetails->delivery_cost); ?></span>
                </p>
                
                <!-- righr side information -->
                <div class="box-right">                    

                    <?php if($branchDetails->availability_status === AVAILABILITY_STATUS_OPEN): ?>
                        <p class="status wow fadeInUp open"><span><?php echo e(__('Open')); ?></span></p>
                    <?php elseif($branchDetails->availability_status === AVAILABILITY_STATUS_CLOSED): ?>
                        <p class="status wow fadeInUp closed"><span><?php echo e(__('Closed')); ?></span></p>
                    <?php elseif($branchDetails->availability_status === AVAILABILITY_STATUS_BUSY): ?>
                        <p class="status wow fadeInUp busy"><span><?php echo e(__('Busy')); ?></span></p>
                    <?php elseif($branchDetails->availability_status === AVAILABILITY_STATUS_OUT_OF_SERVICE): ?>
                        <p class="status wow fadeInUp outOfService"><span><?php echo e(__('Out of Service')); ?></span></p>
                    <?php endif; ?>
                    
                    <div class="star-one-row wow fadeInUp">
                        <form>
                            <span class="star-rating view_only">                                
                                <?php for($i = 1; $i <= 5; $i++ ): ?>
                                    <input id="star-<?php echo e($branchDetails->branch_key); ?>" type="checkbox" <?php echo e((round($branchDetails->branch_avg_rating) == $i) ? 'checked="true"' : ''); ?> name="star">
                                    <label class="star" for="star-<?php echo e($branchDetails->branch_key); ?>" ></label>
                                <?php endfor; ?>
                                
                            </span>
                        </form>
                         <span class="rt">( <?php echo e($branchDetails->branch_rating_count); ?> <?php echo e(__('Ratings')); ?> )</span> 
                        
                    </div>
                    <?php if(Auth::guard(GUARD_USER)->check()): ?>
                    <p>
                        <button class="wishlist_heart fav wow fadeInUp <?php echo e($branchDetails->is_wishlist == 1 ? 'added' : ''); ?>"  data-wishlist="<?php echo e($branchDetails->is_wishlist == 1 ? 1 : 0); ?>" value="<?php echo e($branchDetails->branch_key); ?>">
                            <i class="material-icons"><?php echo e(__('favorite')); ?></i>
                        </button>
                    </p>
                    <?php else: ?> 
                    <p>
                        <button class="fav wow fadeInUp loginModel" value="<?php echo e($branchDetails->branch_key); ?>">
                            <i class="material-icons"><?php echo e(__('favorite')); ?></i>
                        </button>
                    </p>
                    <?php endif; ?>
                </div>
                <!-- righr side information -->

            </div>
        </div>
    </section>


<!-- mobile cart -->
<div class="cart-toggle-overlay re_overlay"></div>
<div class="mini-mobile-cart"> 2 <?php echo e(__('Items In Cart')); ?> <span><?php echo e(__('View Cart')); ?> <i class="fa fa-angle-right"></i></span> </div>

    <!-- short description -->

    <section class="detail-tab wow fadeInUp">
        <div class="container">
            <nav>
                <div class="nav nav-tabs wow fadeInUp" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#nav-menu" role="tab"><?php echo e(__('Menu')); ?></a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#nav-info" role="tab"><?php echo e(__('Info')); ?></a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#nav-ratings" role="tab"><?php echo e(__('Ratings')); ?></a>
                </div>
            </nav>

        </div>
    </section>