 <!-- restaurant popup -->
 <div class="modal-body">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="popup-img" style="background-image:url(https://www.seriouseats.com/recipes/images/2015/07/20150728-homemade-whopper-food-lab-35.jpg)"></div>

       <div class="content">
        <h4><?php echo e(__("Choose Outlet")); ?></h4>
        <p><span id="branch_count"></span> <?php echo e(__("Outlets near you")); ?></p>
        <ul>
            <?php $__currentLoopData = $branchList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <div class="poplist">
                    
                    <p><a href="<?php echo e(route('frontend.branch.show',[$value->branch_slug])); ?>"><?php echo e($value->branch_name); ?> </a></p>
                </div>
                 <div class="poplist show">
                    
                    <span class="rating"><i class="fa fa-star" aria-hidden="true"></i><span><?php echo e($value->branch_avg_rating); ?></span></span>
                    
                    <span class="time_min"><?php echo e($value->delivery_time.__("Mins")); ?></span>
                </div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
   
 