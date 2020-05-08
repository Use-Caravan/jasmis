<?php if($branchList->branches !== null && count($branchList->branches) > 0): ?>
<h2 class="heading-1"><?php echo e(__('All Restaurants')); ?></h2> 
<div class="row">
    <!-- col-md-6 -->
    <?php $__currentLoopData = $branchList->branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-6">
        <!-- box -->
        <div class="box wow zoomIn">
                
            <!-- listing top -->
        
            <div class="listing-top">
                <?php if($value->branch_count > 1): ?>
                <a href="#" class="img bg_style background_img restaurent_popup" data-action="<?php echo e(url('get-near-branches')); ?>" data-key="<?php echo e($value->vendor_key); ?>" data-img="<?php echo e($value->branch_logo); ?>" data-count="<?php echo e($value->branch_count); ?>"  style="background-image:url(<?php echo e($value->branch_logo); ?>);">
                </a>
                <h4 class="text-overflow"><a href="#" class="restaurent_popup" data-action="<?php echo e(url('get-near-branches')); ?>" data-key="<?php echo e($value->vendor_key); ?>" data-img="<?php echo e($value->branch_logo); ?>" data-count="<?php echo e($value->branch_count); ?>" ><?php echo e($value->vendor_name); ?></a></h4>
                <?php else: ?>
                <a href="<?php echo e(route('frontend.branch.show',[$value->branch_slug])); ?>" class="img bg_style background_img" style="background-image:url(<?php echo e($value->branch_logo); ?>);">
                </a>
                <h4 class="text-overflow"><a href="<?php echo e(route('frontend.branch.show',[$value->branch_slug])); ?>" ><?php echo e($value->vendor_name); ?></a></h4>
                <?php endif; ?>
                <p class="text-overflow"><?php echo e($value->branch_cuisine); ?></p>
                <div class="star d-flex w100 mb5">
                    <form>
                        <span class="star-rating">
                            <?php for($i = 1; $i <= 5; $i++ ): ?>
                                <input id="star-<?php echo e($i.$value->branch_key); ?>" type="checkbox" <?php echo e((round($value->branch_avg_rating) == $i) ? 'checked="true"' : ''); ?> name="star">
                                <label class="star" for="star-<?php echo e($value->branch_key); ?>" ></label>
                            <?php endfor; ?>                                            
                        </span>
                    </form>
                    <span class="rt">( <?php echo e($value->branch_rating_count); ?>  <?php echo e(__('Ratings')); ?> )</span> 
                </div>
                <p>Pay by: <?php echo e($value->payment_option); ?> </p>
            </div>  
            <!-- listing top -->
            <div class="listing-bottom full_row">

                <p class="bt-border">
                    <span> <i class="material-icons">access_time</i> <?php echo e(__('Pickup Time')); ?>: <?php echo e($value->pickup_time.__("Mins")); ?></span>
                    <span> <i class="material-icons">access_time</i> <?php echo e(__('Delivery Time')); ?>: <?php echo e($value->delivery_time.__("Mins")); ?></span>
                    <?php if(!Auth::guard(GUARD_USER)->check()): ?>
                        <button class="fav loginModel"><i class="material-icons">favorite</i></button>
                    <?php else: ?>
                        <button class="fav wishlist_heart <?php echo e(($value->is_wishlist == 1) ? 'added' : ''); ?>" data-wishlist="<?php echo e($value->is_wishlist == 1 ? 1 : 0); ?>" value="<?php echo e($value->branch_key); ?>" ><i class="material-icons">favorite</i></button>
                    <?php endif; ?>
                </p>
                <p> 
                    <span><i class="min_ord" ></i> <?php echo e(__('Min Order')); ?>: <?php echo e($value->min_order_value); ?></span>
                    <span> <i class="fee"></i> <?php echo e(__('Delivery Fee')); ?>: <?php echo e($value->delivery_cost); ?></span>
                    <?php if($value->branch_count > 1): ?>
                    <span><i class="fee1"></i><a class="res_outlet restaurent_popup" id="restaurent_popup" data-action="<?php echo e(url('get-near-branches')); ?>" data-key="<?php echo e($value->vendor_key); ?>" data-img="<?php echo e($value->branch_logo); ?>" data-count="<?php echo e($value->branch_count); ?>"  >
                 <?php echo e($value->branch_count); ?> <?php echo e(__('Outlets')); ?>

                </a></span>
                <?php endif; ?>
                </p>
              
            </div>
            
        </div>
        <!-- box -->
    </div> 
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                    
    <!-- col-md-6 -->
</div>
 <div class="modal fade respopup" id="res_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"> 
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                
            </div>
        </div>  
</div> 
 <!-- restaurant popup -->
     
<?php else: ?> 
    <h2 class="heading-1"><?php echo e(__('No Restaurants match found for your search...')); ?></h2>
<?php endif; ?>

   