<?php $__env->startSection('content'); ?>
<section class="padd-20">
    <div class="container">
        <!-- breadcums -->
        <div class="breadcums mb-0 wow fadeInUp">
            <ul class="reset">
                <li><a href="<?php echo e(route('frontend.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                <li><span><?php echo e(__('Favourite Restaurants')); ?></span></li>
            </ul>
        </div>
        <!-- breadcums -->
    </div>
</section>
<section class="myaccount-page">
    <div class="container">
        <?php echo $__env->make('frontend.layouts.partials._profile-section', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="border-boxed">
                <div class="full_row">
                    <?php echo $__env->make('frontend.layouts.partials._profile_sidemenu', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <div class="account-content">
                        <h2 class="account-title wow fadeInUp"><?php echo e(__('Favourite Restaurants')); ?></h2>
                            <div class="row-1">
                                <ul class="fav_lists wow fadeInUp reset">
                        <!-- li loop -->
                        <?php $__currentLoopData = $wishListDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                            <!-- box -->
                                <div class="box wow fadeInUp">
                                <a href="<?php echo e(route('frontend.branch.show',[$value->branch_slug])); ?>" class="img bg_style" style="background-image:url(<?php echo e($value->branch_logo); ?>);"></a>
                                
                                <h4><a href="<?php echo e(route('frontend.branch.show',[$value->branch_slug])); ?>"><?php echo e($value->branch_name); ?></a></h4>
                                <p class="text-overflow"><?php echo e($value->cuisines); ?></p>
                                <div class="star full_row ">
                                    <form>
                                        <span class="star-rating view_only">
                                       <?php for($i = 1; $i <= 5; $i++ ): ?>
                                            <input id="star-<?php echo e($value->branch_key.$i); ?>" type="checkbox" <?php echo e((round($value->branch_avg_rating) == $i) ? 'checked="true"' : ''); ?> name="star">
                                            <label class="star" for="star-<?php echo e($value->branch_key.$i); ?>" ></label> 
                                        <?php endfor; ?>
                                        
                                </span>
                                    </form>                                    
                                </div>
                                    <button class="fav added wishlist_heart" value=<?php echo e($value->branch_key); ?>><i class="material-icons">favorite</i></button>
                                </div>
                                <!-- box -->
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <!-- li loop -->
                                 <!-- li loop -->
                          
                            <!-- li loop -->
                        </ul>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </section>
<script>
$(document).ready(function()
{    
    $('.wishlist_heart').on('click',function (e) {
        e.preventDefault();
        var branchKey = $(this).val();
        var ths = $(this);
        $.ajax({ 
            url: "<?php echo e(route('frontend.wishlist')); ?>",
            type: "PUT",
            data: { branch_key : branchKey },
            success: function(result) {
                if(result.status == HTTP_SUCCESS ){
                    successNotify(result.message);                    
                    ths.closest('li').remove();
                }else{
                    var message = result.message;
                    errorNotify(message.replace(",","<br/>"));
                }
            }
        });  
    });
})
</script>
<?php $__env->stopSection(); ?>

                 

        

   



   
<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>