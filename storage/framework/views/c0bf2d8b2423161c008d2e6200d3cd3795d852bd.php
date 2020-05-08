<?php $__env->startSection('content'); ?>

    <!-- breadcums -->
    <section class="breadcums br-section" branch_key="<?php echo e($branchDetails->branch_key); ?>" id="current_branch_key">
        <div class="container">
            <ul class="reset wow fadeInUp">
                <li><a href="<?php echo e(route('frontend.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                <li><a href="<?php echo e(route('frontend.branch.index')); ?>"><?php echo e(__('Restaurants')); ?></a></li>
                <li><span><?php echo e($branchDetails->branch_name); ?></span></li>
            </ul>
        </div>
    </section>

    <!-- breadcums -->

    <?php echo $__env->make('frontend.branch.branch-general', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <!-- detail -->
    <section class="tab-content-cart">
        <div class="container">
            <!-- fullrow -->
            <div class="full_row" data-sticky_parent>
                <!-- de-content -->
                <div class="de-content" data-sticky_column>
                    <!-- tab content -->
                    <div class="tab-content" id="nav-tabContent">
                        <!-- menu -->
                        <div class="tab-pane fade active show" id="nav-menu" role="tabpanel">

                            <?php echo $__env->make('frontend.branch.branch-items', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                        </div>
                        <!-- menu -->
                        <!-- information -->
                        <div class="tab-pane fade" id="nav-info" role="tabpanel">

                            <?php echo $__env->make('frontend.branch.branch-info', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                        </div>
                        <!-- information -->
                        <!-- ratings -->
                        <div class="tab-pane" id="nav-ratings" role="tabpanel">

                            <div class="de-ratings wow fadeInUp">
                                
                                <?php echo $__env->make('frontend.branch.branch-rating', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                            </div>

                        </div>
                        <!-- ratings -->

                    </div>
                    <!-- tab content -->
                </div>
                <!-- de-content -->
                <!-- cart -->
                <div class="de-cart" data-sticky_column >
                    <!-- cart box -->
                        <div class="box wow fadeInUp" id="cart_div">
                            <?php echo $__env->make('frontend.branch.branch-cart', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>
                </div>

            </div>
            <!-- fullrow -->

        </div>
    </section>
    <!-- detail -->

    
<script type="text/javascript">
$('document').ready(function()
{
    $("body").addClass("detail-page");

    $('.wishlist_heart').click(function()
    {        
        var ths = $(this);
        var isWishlist = ths.data('wishlist');
        var type = (isWishlist == 1) ?  'PUT' : 'POST';
        $.ajax({
            url: "<?php echo e(route('frontend.wishlist')); ?>",
            type: type,
            data : { branch_key: $(this).val() },
            success: function(result) {                
                if(result.status == HTTP_SUCCESS) {
                    successNotify(result.message);                                        
                    if(isWishlist == 1){
                        ths.removeClass('added');
                        ths.data('wishlist',0);
                    } else {                        
                        ths.addClass('added');
                        ths.data('wishlist',1);
                    }
                }else{
                    var message = result.message;
                    errorNotify(result.message.replace(",","<br/>"));
                }
            }
        });
    });
})
</script>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>