<!-- col md 6 -->

<div class="col-md-6 col-sm-12">
    <div class="box" id="<?php echo e($iValue->item_key); ?>">
        <div class="img-menu bg_style" style="background-image:url(<?php echo e($iValue->item_image); ?>);"></div>
        <h4><?php echo e($iValue->item_name); ?></h4>
        <div class="price-menu">
            <?php if($iValue->offer_enable === true): ?>
                <?php if($iValue->offer_value < $iValue->item_price): ?>
                    <p> <strike> <?php echo e($iValue->item_price); ?> </strike></p>
                    <p><?php echo e($iValue->offer_price); ?></p>
                <?php else: ?> 
                    <p><?php echo e($iValue->item_price); ?></p>
                <?php endif; ?>
            <?php else: ?> 
            <p><?php echo e($iValue->item_price); ?></p>
            <?php endif; ?>
            <?php if($branchDetails->availability_status === AVAILABILITY_STATUS_OPEN): ?>
            <div class="menu-button-box <?php echo e(($iValue->ingrdient_groups == null || empty($iValue->ingrdient_groups) ) ? 'in' : ''); ?>">
                <a href="javascript:void(0);" itemKey="<?php echo e($iValue->item_key); ?>" branchKey="<?php echo e($iValue->branch_key); ?>" class="btn white-shadow <?php echo e(auth()->guard(GUARD_USER)->check() ? 'addItemIngredient' : 'loginModel'); ?>"><?php echo e(__('Add')); ?></a>
                <span class="quantity item_quantity">
                    <button class="min <?php echo e(auth()->guard(GUARD_USER)->check() ? 'quantity_min' : 'loginModel'); ?>" hasIngredient="0" branchKey="<?php echo e($branchDetails->branch_key); ?>" itemKey="<?php echo e($iValue->item_key); ?>" action="minus"><i class="material-icons">remove</i></button>
                        <input type="text" name="<?php echo e($iValue->item_key); ?>" class="quantity_text"  readonly value="<?php echo e($iValue->in_cart); ?>">                        
                    <button class="max <?php echo e(auth()->guard(GUARD_USER)->check() ? 'quantity_max' : 'loginModel'); ?>" hasIngredient="0" branchKey="<?php echo e($branchDetails->branch_key); ?>" itemKey="<?php echo e($iValue->item_key); ?>" action="plus"><i class="material-icons">add</i></button>
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- col md 6 -->