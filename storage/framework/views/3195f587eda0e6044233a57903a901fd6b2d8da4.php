<?php $__currentLoopData = $cartItem->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
<tr>
    <td class="name">
        <div class="min-height">
            <span class="img bg_style" style="background-image:url(<?php echo e($value->item_image); ?>);"></span>
            <h4><?php echo e($value->item_name); ?></h4>
            <p><?php echo e($value->ingredient_name); ?></p>
        </div>
    </td>
    <td class="qt">
        <span class="quantity">
            <button class="min <?php echo e(auth()->guard(GUARD_USER)->check() ? 'quantity_minimum' : 'loginModel'); ?>" branchKey="<?php echo e($branchDetails->branch_key); ?>" cartItemKey="<?php echo e($value->cart_item_key); ?>" itemKey="<?php echo e($value->item_key); ?>"  action="minus"><i class="material-icons">remove</i></button>
                <input type="text" class="quantity_text" readonly value="<?php echo e($value->quanity); ?>">
            <button class="max <?php echo e(auth()->guard(GUARD_USER)->check() ? 'quantity_maximum' : 'loginModel'); ?>" branchKey="<?php echo e($branchDetails->branch_key); ?>" cartItemKey="<?php echo e($value->cart_item_key); ?>" itemKey="<?php echo e($value->item_key); ?>" action="plus"><i class="material-icons">add</i></button>
        </span>

    </td>
    <td class="price text-right">
        
    </td>
    <td class="rm"><a href="javascript:" class="remove-btn" onclick="updatecartQuantity('<?php echo e($value->cart_item_key); ?>', 0)"><i class="fa fa-times-circle" aria-hidden="true"></i></a></td> 
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


