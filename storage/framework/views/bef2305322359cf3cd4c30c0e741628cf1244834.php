<?php $__env->startSection('content'); ?>
<section class="padd-20">
    <div class="container">        
        <div class="breadcums mb-0 wow fadeInUp">
            <ul class="reset">
                <li><a href="<?php echo e(route('frontend.index')); ?>"><?php echo e(('Home')); ?></a></li>
                <li><span><?php echo e(__('C wallet')); ?></span></li>
            </ul>
        </div>        
    </div>
</section>

<section class="myaccount-page">
    <div class="container">                
        <?php if($transaction !== null): ?>
            <?php if($transaction->status === TRANSACTION_STATUS_SUCCESS): ?>
                <div class="flash-message">
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>	    
                        <?php echo e(__("apimsg.Payment has been success")); ?>

                    </div>
                </div>
            <?php else: ?>
            <div class="flash-message">
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>	    
                    <?php echo e(__("apimsg.Payment cannot capture")); ?>

                </div>
            </div>  
            <?php endif; ?>              
        <?php endif; ?>
        <?php echo $__env->make('frontend.layouts.partials._profile-section', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="border-boxed">                
                <div class="full_row">
                    <?php echo $__env->make('frontend.layouts.partials._profile_sidemenu', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <div class="account-content">
                        <h2 class="account-title wow fadeInUp"><?php echo e(__('C wallet')); ?></h2>                            
                        <div class="greybox-wallet wow fadeInUp">
                            <div class="icons"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/c-wallet-logo.png')); ?>"></div>
                                <div class="full_row">
                                    <div class="amount wow fadeInUp" id="wallet_amount">
                                    <?php echo e(Common::currency(Auth::guard(GUARD_USER)->user()->wallet_amount)); ?>   
                                    </div>
                                </div>
                        </div>

                        <!-- box -->
                        <h2 class="account-title wow fadeInUp"><?php echo e(__('Add Money')); ?></h2>
                        <?php echo e(Form::open(['route' => 'frontend.wallet-add', 'id' => 'wallet-form', 'class' => 'form-horizontal', 'method' => 'POST'])); ?>

                        <div class="full_row add_money wow fadeInUp">
                            <div class="form-group">
                            <?php echo e(Form::text("amount",'',['class' => 'form-control','id' => "c-amount",'maxlength'=>'10',"placeholder" => '0.00'])); ?>

                            <span class="fly_price">BD</span>
                            </div>
                            <?php echo Html::decode( Form::button('<span class="shape">'.__('Proceed').'</span>', ['type'=>'submit', 'class' => 'shape-btn alert-trigger shape1']) ); ?>

                        </div>
                        <?php echo e(form::close()); ?>

                    </div>
                </div>
            </div>
    </div>
</section>
    <!-- row end -->
<script>
$(document).ready(function()
{
    setTimeout(function(){
        $('.flash-message').hide('slow');
        var url = window.location.href;
        var a = url.indexOf("?");
        var b =  url.substring(a);
        var c = url.replace(b,"");
        url = c;
        window.history.pushState(null, null, url);
    },3000);
})
</script>
<?php $__env->stopSection(); ?>
          

    


<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>