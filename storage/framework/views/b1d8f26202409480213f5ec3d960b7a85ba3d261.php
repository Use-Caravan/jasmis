
<div class="box-head">
    <h3><?php echo e(__('Cart')); ?></h3>
    <?php if($cartDetails !== null && !empty($cartDetails)): ?>
        <p><?php echo e($cartDetails->total->cart_total); ?> <?php echo e(__('Items')); ?></p>
    <?php endif; ?>
    <span class="close-cart-toggle"><i class="material-icons">arrow_back</i></span>
</div>  
<?php if($cartDetails !== null && !empty($cartDetails)): ?>
    <?php $__currentLoopData = $cartDetails->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="box-body">
        <table>
            <tbody>
                <div class="price-menu">
                <tr>
                    <td class="title"><?php echo e($value->item_name); ?></td>
                    <td><span class="quantity">
                        <button class="min <?php echo e(auth()->guard(GUARD_USER)->check() ? 'quantity_minimum' : 'loginModel'); ?>" branchKey="<?php echo e($branchDetails->branch_key); ?>" cartItemKey="<?php echo e($value->cart_item_key); ?>" itemKey="<?php echo e($value->item_key); ?>"  action="minus"><i class="material-icons">remove</i></button>
                            <input type="text" class="quantity_text" readonly value="<?php echo e($value->quanity); ?>">
                        <button class="max <?php echo e(auth()->guard(GUARD_USER)->check() ? 'quantity_maximum' : 'loginModel'); ?>" branchKey="<?php echo e($branchDetails->branch_key); ?>" cartItemKey="<?php echo e($value->cart_item_key); ?>" itemKey="<?php echo e($value->item_key); ?>" action="plus"><i class="material-icons">add</i></button>
                        </span>
                    </td>
                    <td class="text-right"><?php echo e($value->subtotal); ?></td>           
                </tr>
                </div>
                <tr>
                    <td colspan="3" class="description"><?php echo e($value->ingredient_name); ?> </td>
                </tr>
                    
            </tbody>
        </table>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="box-footer">
        <table>
            <tbody>                    
                <?php $__currentLoopData = $cartDetails->payment_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="<?php echo e(($value->is_bold == 1) ? 'sub' : ''); ?>">
                    <td><?php echo e($value->name); ?></td>
                    <td class="text-right"><?php echo e($value->price); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                <tr class="sub total">
                    <td><?php echo e($cartDetails->total->name); ?></td>
                    <td class="text-right"><?php echo e($cartDetails->total->price); ?></td>
                </tr>
            </tbody>
        </table>            
        <div class="full_row text-right mt-3 mb-2">
            <a class="shape-btn loader shape1"  href="<?php echo e(route('frontend.checkout',['branch_slug' =>$branchDetails->branch_slug ])); ?>"><span class="shape"><?php echo e(__('Checkout')); ?></span></a>
        </div>            
    </div>

<?php else: ?>
    <p><?php echo e(__('Your cart is empty')); ?></p>
<?php endif; ?>

