<div class="modal-body">

<!-- order box top -->
    <div class="view-order-box full_row">
        <h4 id ="branch_name"><?php echo e($response->data->branch_name); ?></h4>
        <span><?php echo e(__('Order ID')); ?> : <p id = "order_id"><?php echo e($response->data->order_number); ?></p></span>
        <p class="date" id = "order_date"><?php echo e($response->data->order_datetime); ?></p>
        <div class="status"><?php echo e(__('Status')); ?> : <span class="completed" id = "order_status"></span><?php echo e($response->data->status); ?></p></div>
    </div>
    <!-- order box top -->
        

    <div class="full_row cart-table">
        <div class="table-responsive">
            <table class="table" >
                <tbody id="item_details">
                    <?php $__currentLoopData = $response->data->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="name">
                            <div class="min-height">
                                <span class="img bg_style" style="background-image:url(<?php echo e($value->item_image_path); ?>);"></span>
                                <h4 id = "item_name"><?php echo e($value->item_name); ?></h4>
                                <p><?php echo e($value->ingredients); ?></p>
                            </div>
                        </td>
                        <td class="qt">
                            <?php echo e($value->item_quantity); ?>

                        </td>
                        <td class="price text-right">
                            <?php echo e($value->item_subtotal); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- cart row end -->

    <div class="full_row total_price">

        <table class="shopping_cart">
            <tbody id="payment_details">                
                <?php $__currentLoopData = $response->data->payment_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                
                <tr>
                    <td><?php echo e($value->name); ?></td>
                    <td class="text-right"><?php echo e($value->price); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($response->data->claim_corporate_offer_booking === 1): ?> 
                <tr>
                    <td>Corporate Voucher Offer</td>
                    <td class="text-right"><?php echo e(Common::currency($response->data->csub_total)); ?></td>
                </tr>
                <tr class="total">
                    <td> <?php echo e($response->data->total_amount->name); ?></td>
                    <td class="text-right"><?php echo e(Common::currency($response->data->corder_total - $response->data->csub_total)); ?> </td>
                </tr>
                <?php else: ?> 
                <tr class="total">
                    <td> <?php echo e($response->data->total_amount->name); ?></td>
                    <td class="text-right"><?php echo e($response->data->total_amount->price); ?> </td>
                </tr>
                <?php endif; ?>
                
                
            </tbody>
        </table>

    </div>

</div>
