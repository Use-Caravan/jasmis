<?php
session_start();
?>
<!DOCTYPE HTML>
<html lang="<?php echo e(App::getLocale()); ?>">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="_url" content="<?php echo e(url('/')); ?>" />
    <meta name="_routeName" content="<?php echo e(strstr(Route::currentRouteName(), '.' , true)); ?>" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">  
    <title><?php echo e(config('webconfig.app_name')); ?></title> 
    <link rel="shortcut icon" href="<?php echo e(FileHelper::loadImage(config('webconfig.app_favicon'))); ?>" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php echo AssetHelper::loadFrontendAsset(1); ?>

    <script src="<?php echo e(asset('resources/assets/general/ajax-init.js')); ?>"></script>
</head>

<body>
    
    <!-- header -->

    <header class="top-header">
    <div class="re_overlay header-menu-overlay"></div>
        <div class="container">
            <div class="logo">
            <a href="javascript:void(0);" class="responsive-menu"><i class="material-icons">menu</i></a>
            <a href="/"><img src="<?php echo e(FileHelper::loadImage(config('webconfig.app_logo'))); ?>" class="img-responsive"></a>
            </div>
            <div class="navigation">
            <!-- mobile responsive -->
                <div class="mobile-responsivebox">
                <a href="/"><img src="<?php echo e(FileHelper::loadImage(config('webconfig.app_logo'))); ?>" class="img-responsive"></a>
                <span class="close-header-menu">&times;</span>
                </div>
                <!-- mobile responsive -->
                <ul>
                    <li>
                        <a href="javascript:" id="corporate_offer_toggle"><?php echo e(__('Corporate Offers')); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('frontend.offers')); ?>"><?php echo e(__('Offers')); ?></a>
                    </li>
                    <li><a href="<?php echo e(route('frontend.branch.index',['type'=>'all'])); ?>"><?php echo e(__('All Restaurants')); ?></a></li>
                   
                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="javascript:void(0);" class="language" style="color:<?php echo e(Config::get('app.locale') == $key ? '#fe1509' : ''); ?>" data="<?php echo e($key); ?>"><?php echo e($key); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if(Auth::guard(GUARD_USER)->check() && Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CORPORATES): ?>
                    <li class="login">
                        <a href="<?php echo e(route('frontend.signout')); ?>" class="bg-border"> <i class="material-icons fly_icon">exit_to_app</i> <?php echo e(__('Exit Corporate')); ?></a>
                    </li>                    
                    <?php endif; ?>

                    <?php if(!Auth::guard(GUARD_USER)->check()): ?>
                    <!-- before Login -->
                    <li class="login">
                        <a href="javascript:" class="bg-border loginModel" url="<?php echo e(route('frontend.signout')); ?>"> <i class="material-icons fly_icon">person_outline</i> <?php echo e(__('Login')); ?></a>
                    </li>                    
                    <!-- before Login -->                    
                    <?php elseif(Auth::guard(GUARD_USER)->check() && Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CUSTOMER): ?>
                    <!-- after login -->
                    <li class="my_account dropdown">
                        <a href="#" id="user-profile" data-toggle="dropdown" class="dd-down"> <i class="material-icons fly_icon">keyboard_arrow_down</i> <?php echo e(__('My Account')); ?>

                        </a>
                        <div class="dropdown-menu" aria-labelledby="user-profile">
                            <a class="dropdown-item" href="javascript:" data-toggle="modal" data-target="#edit-profile"><?php echo e(__('My Profile')); ?></a>
                            <a class="dropdown-item" href="<?php echo e(route('address.index')); ?>"><?php echo e(__('Address Book')); ?></a>
                            <a class="dropdown-item" href="<?php echo e(route('frontend.myorder')); ?>"><?php echo e(__('My Orders')); ?></a>
                            <a class="dropdown-item" href="<?php echo e(route('frontend.wishlist')); ?>"><?php echo e(__('Favourite Restaurants')); ?></a>
                            <a class="dropdown-item" href="<?php echo e(route('frontend.wallet')); ?>"><?php echo e(__('C wallet')); ?></a>
                            <a class="dropdown-item" href="<?php echo e(route('frontend.loyalty-points')); ?>"><?php echo e(__('loyalty Points')); ?></a>
                            <?php $__currentLoopData = $cms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($value->position == 3): ?>
                                    <a class="dropdown-item" href="<?php echo e(route('frontend.cms', $value->slug)); ?>"><?php echo e(__('Help')); ?></a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <a class="dropdown-item" href="<?php echo e(route('frontend.signout')); ?>"><?php echo e(__('Logout')); ?></a>
                        </div>

                    </li>
                    <!-- after login -->
                    <?php endif; ?>
                    
                    <?php if(Auth::guard(GUARD_USER)->check()): ?>
                        <?php
                            $cartLayout = Common::cartCount( Auth::guard(GUARD_USER)->user()->user_id );                        
                        ?>
                        <?php if($cartLayout['cart_count'] > 0): ?>
                            <li class="last" id="cartIconLi" branch-key="<?php echo e($cartLayout['branch_key']); ?>"><a href="<?php echo e(route('frontend.checkout',[$cartLayout['branch_slug'] ])); ?>" class="cart-navigation"><span id="cartCountSpan"><?php echo e($cartLayout['cart_count']); ?></span></a></li>
                        <?php else: ?> 
                            <li class="last" id="cartIconLi" branch-key=""><a href="" class="cart-navigation"><span id="cartCountSpan">0</span></a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <!-- header -->
    <?php echo $__env->yieldContent('content'); ?>
    <!-- footer -->
   
    <footer class="footer full_row">
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- col-sm-4 -->
                <div class="col-sm-4">
                    <h3 class="wow fadeInUp"><?php echo e(__('Useful Links')); ?></h3>
                    <div class="expand_section wow fadeInUp">
                        <ul class="quick_links">
                            <?php $__currentLoopData = $cms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e(route('frontend.cms', $value->slug)); ?>"><?php echo e($value->title); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e(route('frontend.faq')); ?>"><?php echo e(__('FAQ')); ?></a></li>
                            <li><a href="<?php echo e(route('contact.index')); ?>"><?php echo e(__('Contact Us')); ?></a></li>
                        </ul>
                    </div>
                </div>
                <!-- col-sm-4 -->
                <div class="col-sm-4">
                    <h3 class="wow fadeInUp"><?php echo e(__('Contact Info')); ?></h3>
                    <div class="expand_section wow fadeInUp">
                        <ul class="quick_contact">
                            <li><i class="material-icons">location_on</i> <?php echo e(config('webconfig.app_address')); ?></li>
                            <li><i class="material-icons">call</i><?php echo e(config('webconfig.app_contact_number')); ?></li>
                            <li><i class="material-icons">mail</i><a href="mailto:<?php echo e(config('webconfig.app_email')); ?>"><?php echo e(config('webconfig.app_email')); ?></a></li>
                        </ul>
                    </div>
                </div>
                <!-- col-sm-4 -->
                <div class="col-sm-4">
                    <h3 class="wow fadeInUp"><?php echo e(__('Social Media')); ?></h3>
                    <div class="expand_section wow fadeInUp">
                        <div class="social-icons">
                            <a href="<?php echo e((preg_match("/http/",config('webconfig.social_facebook'))) ? config('webconfig.social_facebook') : 'http://'.config('webconfig.social_facebook')); ?>" target="_blank"><i class="fa fa-facebook"></i><?php echo e(__('Facebook')); ?></a>
                            <a href="<?php echo e((preg_match("/http/",config('webconfig.social_twitter'))) ? config('webconfig.social_twitter') : 'http://'.config('webconfig.social_twitter')); ?>" target="_blank"><i class="fa fa-twitter"></i><?php echo e(__('Twitter')); ?></a>
                            <a href="<?php echo e((preg_match("/http/",config('webconfig.social_instagram'))) ? config('webconfig.social_instagram') : 'http://'.config('webconfig.social_instagram')); ?>" target="_blank"><i class="fa fa-instagram"></i><?php echo e(__('Instagram')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col-sm-4 -->
        </div>
        <!-- row -->
        <div class="copy-rights text-center">
            <div class="container">
                <p class="wow fadeInUp"><?php echo e(__('© 2019 caravan . All rights reserved Powered by caravan')); ?></p>
            </div>
        </div>
    </footer>    

    <?php echo $__env->renderWhen((!Auth::guard(GUARD_USER)->check()), 'frontend.layouts.partials._authmodel', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path'))); ?>
    <?php echo $__env->renderWhen((Auth::guard(GUARD_USER)->check()) , 'frontend.layouts.partials._editprofile', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path'))); ?>
    <?php echo $__env->make('frontend.layouts.partials._corporate_offer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>


    <div class="modal login_modal fade" id="driver_registration">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h5 class="modal-title"><?php echo e(__('Driver Registration')); ?></h5>
                    <div class="icons-add"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/icon2.png')); ?>"></div>

                </div>
                <div class="modal-body">
                    <?php echo e(Form::open(['route' => 'frontend.driver-registration', 'id' => 'driver-register-form', 'class' => 'form-horizontal', 'method' => 'POST'])); ?>

                    <div class="form-box floating_label">
                        <div class="form-group">
                            <?php echo e(Form::label("d-username", __('Name'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("username",'', ['class' => 'form-control','id' => "d-username",'maxlength'=>'100'])); ?>

                        </div>

                        <div class="form-group">
                            <?php echo e(Form::label("d-email", __('Email'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("email",'', ['class' => 'form-control','id' => "d-email",'maxlength'=>'100'])); ?>

                        </div>

                        <div class="form-group">
                            <?php echo e(Form::label("d-mobile_number", __('Mobile Number'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("mobile_number",'', ['class' => 'form-control','id' => "d-mobile_number",'maxlength'=>'15'])); ?>

                        </div>

                        <div class="form-group">
                            <?php echo e(Form::label("d-license", __('License Number'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("license",'', ['class' => 'form-control','id' => "d-license",'maxlength'=>'30'])); ?>

                        </div> 


                        <div class="form-group">
                            <?php echo e(Form::label("d-vehicle_number", __('Vehicle Number'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("vehicle_number",'', ['class' => 'form-control','id' => "d-vehicle_number",'maxlength'=>'30'])); ?>

                        </div> 

                        <div class="form-group">
                            <?php echo e(Form::label("d-password", __('Password'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::password("password", ['class' => 'form-control','id' => "d-password",'maxlength'=>'20'])); ?> 
                        </div>
                        <div class="form-group">
                            <?php echo e(Form::label("d-confirm_password", __('Confirm Password'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::password("confirm_password", ['class' => 'form-control','id' => "d-confirm_password",'maxlength'=>'20'])); ?> 
                        </div>

                       <div class="check-group mb-4">                                
                            <?php echo e(Form::checkbox('terms',1,null,[ 'id' => 'driver-registration', "class" => "checkbox" ])); ?>

                            <?php echo Html::decode( Form::label('driver-registration', __('I Accept the').' '.Html::link('link', 'Terms and Conditions', ['']), ['class' => 'checkbox f18']) ); ?>                                
                        </div>

                        <div class="text-right mb-4">
                            <?php echo Html::decode( Form::button('<span class="shape">Submit</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ); ?>

                        </div>
                        <?php echo e(form::close()); ?>

                        <?php echo JsValidator::formRequest('App\Http\Requests\Frontend\DriverRegisterRequest', '#driver-register-form'); ?> 
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- footer -->
    <?php echo AssetHelper::loadFrontendAsset(); ?>

    </body>

</html>
<script>
$(document).ready(function(){
    $('.language').on('click',function (e) {
        e.preventDefault();
        var language = $(this).attr('data');
        $.ajax({ 
            url: "<?php echo e(route('frontend.language')); ?>",
            type: "POST",            
            data: { language : language },
            success: function(result) {
                location.reload();
            }
        });           
    });
    $('#corporate_offer_toggle').click(function(){
        $('#corporate_offer_modal').modal('toggle');
    });
    $('#corporate-offer-form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({ 
            url: "<?php echo e(route('frontend.corporate-login')); ?>",
            type: "POST",            
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.status === 200) {
                    window.location.href = response.redirect_url;
                }
            }
        });
    });
})
</script>