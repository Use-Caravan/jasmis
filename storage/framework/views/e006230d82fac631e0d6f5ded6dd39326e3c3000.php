<div class="box">
    <h4 class="mb-0"><?php echo e(__('Ratings')); ?></h4>
    <a href="javascript:" data-toggle="modal" data-target="<?php echo e((Auth::guard(GUARD_USER)->check()) ? "#modal-rating" : "#login_modal"); ?>" ><i class="material-icons">add_circle_outline</i></a>
</div>
<ul class="reset">
    <?php $__currentLoopData = $branchDetails->rating; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li>
        <div class="border-box wow fadeInUp">
            <div class="icon"><i class="material-icons fly_icon">person_outline</i></div>
            <h5><?php echo e($value->name); ?></h5>
            <div class="star-row">
                <form>
                    <span class="star-rating view_only">
                            <?php for($i = 1; $i <= 5; $i++ ): ?>
                            <input id="star-<?php echo e($branchDetails->branch_key); ?>" type="checkbox" <?php echo e(( (int)$value->rating == $i) ? 'checked="true"' : ''); ?> name="star">
                            <label class="star" for="star-<?php echo e($branchDetails->branch_key); ?>" ></label>
                        <?php endfor; ?>
                    </span>
                </form>
            </div>
            <p><?php echo e($value->review); ?></p>
            <span class="date"> <?php echo e($value->created_date); ?> </span>
        </div>
    </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
</ul>
<?php if($itemDetails != null): ?>
<!-- Rating modal -->
<div class="modal modal_ratings fade" id="modal-rating">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                <h5 class="modal-title"><?php echo e(__('Add your rating')); ?></h5>
            </div>
            <div class="modal-body">
                <div class="full_row">
                    <form action="<?php echo e(route('frontend.post-rating')); ?>" id="post-rating" method="POST">
                        <input type="hidden" name="branch_key" value="<?php echo e($branchDetails->branch_key); ?>">
                        <div class="form-group text-center  star-row">                            
                            <span class="star-rating large">
                                <input id="rating-5" type="radio" value="5" name="rating">
                                <label class="star" for="rating-5"></label>
                                <input id="rating-4" type="radio" value="4" name="rating">
                                <label class="star" for="rating-4"></label>
                                <input id="rating-3" type="radio" value="3"  name="rating">
                                <label class="star" for="rating-3"></label>
                                <input id="rating-2" type="radio" value="2" name="rating">
                                <label class="star" for="rating-2"></label>
                                <input id="rating-1" type="radio" value="1" name="rating">
                                <label class="star" for="rating-1"></label>
                            </span>                        
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="review" placeholder="<?php echo e(__('Enter your comments')); ?>"></textarea>
                        </div>
                        <div class="form-group text-right">
                            <button class="shape-btn shape1 shape-dark" data-dismiss="modal"><span class="shape"><?php echo e(__('Cancel')); ?></span></button>
                            <button type="submit" class="shape-btn loader shape1"><span class="shape"><?php echo e(__('Submit')); ?></span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<span> <h2 class="heading-1"><?php echo e(__('No items found you can not give rating.')); ?><h2></span>
<?php endif; ?>
<!-- Rating modal -->