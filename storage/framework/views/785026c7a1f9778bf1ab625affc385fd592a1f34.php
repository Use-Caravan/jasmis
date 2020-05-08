<div class="modal-header text-center">
    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
    <h5 class="modal-title"><?php echo e(__('Add Item Choices')); ?></h5>
</div>
<div class="modal-body">

    <!-- item information -->

    <div class="item-information">
        <div class="img-menu bg_style" style="background-image:url(<?php echo e($itemDetails->item_image); ?>);"></div>
        <h4><?php echo e($itemDetails->item_name); ?></h4>
        <p><?php echo e($itemDetails->item_description); ?></p>
    
          <div class="price-menu add_modal">
            <span class="quantity">
        <button class="min quantity_min" hasIngredient="1" branchKey="<?php echo e($itemDetails->branch_key); ?>" itemKey="<?php echo e($itemDetails->item_key); ?>" action="minus"><i class="material-icons">remove</i></button>
            <input type="text" class="quantity_text" readonly value="1">
        <button class="max quantity_max" hasIngredient="1" branchKey="<?php echo e($itemDetails->branch_key); ?>" itemKey="<?php echo e($itemDetails->item_key); ?>" action="plus"><i class="material-icons">add</i></button>
    </span>
    <?php if($itemDetails->offer_enable === true): ?>
            <p class="price"> </p>
            <?php if($itemDetails->offer_value < $itemDetails->item_price): ?>
                <p class="price flat_price"  flatPrice="<?php echo e($itemDetails->flat_offer_price); ?>" value=""> <?php echo e($itemDetails->offer_price); ?> </p>
                <input type="hidden" value="<?php echo e($itemDetails->flat_offer_price); ?>" id='item-price'>
            <?php else: ?>
                <p class="price flat_price"  flatPrice="<?php echo e($itemDetails->flat_item_price); ?>" value=""> <?php echo e($itemDetails->item_price); ?> </p>
                <input type="hidden" value="<?php echo e($itemDetails->flat_item_price); ?>" id='item-price'>
            <?php endif; ?>
        <?php else: ?> 
            <p class="price flat_price"  flatPrice="<?php echo e($itemDetails->flat_item_price); ?>" value=""> <?php echo e($itemDetails->item_price); ?> </p>
            <input type="hidden" value="<?php echo e($itemDetails->flat_item_price); ?>" id='item-price'>
        <?php endif; ?>  
</div>
              
    </div>

    <!-- item information -->

    <div class="group-item">

        <?php $__currentLoopData = $itemDetails->ingrdient_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gkey => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
        <!-- fullrow -->
        <div class="full_row">
            <!-- box -->
            <div class="box">
                <h4><?php echo e($group->ingredient_group_name); ?> <span>(Choose from min <?php echo e($group->minimum); ?> upto <?php echo e($group->maximum); ?> items) </span> </h4>
            </div>
            <!-- box -->
            <!-- ul -->
            <div class="row-1 ingredient_groups" id="<?php echo e($group->ingredient_group_key); ?>" minimum="<?php echo e($group->minimum); ?>" maximum="<?php echo e($group->maximum); ?>">
                <ul class="reset">
                    <?php $__currentLoopData = $group->ingredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ikey => $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <input type="checkbox" groupKey="<?php echo e($group->ingredient_group_key); ?>" ingredientKey="<?php echo e($ingredient->ingredient_key); ?>" id="<?php echo e($group->ingredient_group_id.$ingredient->ingredient_key); ?>" ingredientFlatPrice="<?php echo e($ingredient->flat_ingredient_price); ?>" class="checkbox <?php echo e($group->ingredient_group_key); ?> ingredients">
                            <label for="<?php echo e($group->ingredient_group_id.$ingredient->ingredient_key); ?>" class="checkbox"> <?php echo e($ingredient->ingredient_name); ?> 
                                <span class="price" ingredientFlatPrice="<?php echo e($ingredient->flat_ingredient_price); ?>"><?php echo e($ingredient->price); ?></span>
                            </label>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                </ul>
            </div>
            <!-- ul -->
            <span style="color:chocolate;" id="error<?php echo e($group->ingredient_group_key); ?>"></span>
        </div>
        <!-- row -->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        
            
                <div class="full_row mt-15">
                    <div class="form-group">
                        
                        <?php echo e(Form::textarea('item_instruction', '', ['class' => 'form-control','id' => $itemDetails->item_key.'-item_instruction','placeholder' => 'Instructions'])); ?>

                    </div>
                </div>

    </div>
</div>
<div class="modal-footer item_quantity">
  
    <button class="shape-btn loader shape1" hasIngredient="1"  branchKey="<?php echo e($itemDetails->branch_key); ?>" itemKey="<?php echo e($itemDetails->item_key); ?>" id="<?php echo e(auth()->guard(GUARD_USER)->check() ? 'add_to_cart' : ''); ?>"><span class="shape"><?php echo e(__('Add to Cart')); ?></span></button>
</div>
