
<?php $__currentLoopData = $cartItem->payment_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td><?php echo e($value->name); ?></td>
    <td class="text-right"><?php echo e($value->price); ?> </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if(session::has('corporate_voucher')): ?>

<td><?php echo e($cartItem->total->name); ?></td>
    <td class="text-right"><?php echo e(Common::currency($cartItem->total->cprice -$cartItem->sub_total->cprice)); ?></td>
</tr>

<?php else: ?>
<td><?php echo e($cartItem->total->name); ?></td>
    <td class="text-right"><?php echo e($cartItem->total->price); ?></td>
</tr>
<?php endif; ?>