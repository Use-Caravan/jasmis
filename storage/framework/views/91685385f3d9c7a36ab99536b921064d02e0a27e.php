<?php $__env->startSection('content'); ?>
    
    <body class="top-popup">
        <div class="driver-banner">
            <p class="content"><?php echo e(__('Become a Caravan rider')); ?> <a href="javascript:" class="btn btn-primary" id="apply_driver" data-toggle="modal" data-target="#driver_registration"><?php echo e(__('Apply Now')); ?></span>
                <a class="smal-close"><i class="fa" aria-hidden="true">&times;</i> </a>
            </p>
        </div>
    </body>
    <!-- bannner start -->

    <section class="banner">
        <div class="container">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="banner-inner">
                        <h1 class="wow fadeInDown"> <?php echo e(($bannerImage !== null) ? $bannerImage['banner_name'] : ''); ?></h1>

                        <div class="banner-search">
                            <?php echo e(Form::open(['route' => 'frontend.branch.index', 'id' => 'branch-listing', 'class' => 'form-horizontal', 'method' => 'GET'])); ?>

                           <div class="form-group boxed wow fadeInUp">
                                <?php echo e(Form::text("",'' ,['class' => 'form-control','placeholder' => __('Enter your delivery location'),'id' => 'delivery-location'])); ?>

                                <?php echo e(Form::hidden('latitude','', ['id' => 'latitude'])); ?>

                                <?php echo e(Form::hidden('longitude','', ['id' => 'longitude'])); ?>

                                <?php echo e(Form::hidden('location','', ['id' => 'location'])); ?>

                                <a href="javascript:void(0);" class="gps" id="get-mylocation" ><i class="material-icons">my_location</i></a>
                            </div>
                            <div class="form-group radio-btn wow fadeInUp">
                                <div class="radio-box">
                                    <input type="radio" name="order_type" value="<?php echo e(ORDER_TYPE_DELIVERY); ?>" id="order_type<?php echo e(ORDER_TYPE_DELIVERY); ?>" checked>
                                    <label for="order_type<?php echo e(ORDER_TYPE_DELIVERY); ?>"><?php echo e(__('Delivery')); ?></label>
                                    <input type="radio" name="order_type" value="<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>" id="order_type<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>">
                                    <label for="order_type<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>"><?php echo e(__('Pickup & Dine In')); ?></label>
                                    <input type="radio" name="order_type" value="<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>" id="order_type<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>">
                                    <label for="order_type<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>" data-toggle="modal" data-target="#discount_modal"><?php echo e(__('Use Corporate Discount')); ?></label>
                                </div>
                                <div class="btn-box wow fadeInUp">
                                    
                                    <?php echo Html::decode( Form::button('<span class="shape">'.__('Search').'</span>', ['type'=>'submit', 'class' => 'shape-btn']) ); ?> 
                                </div>
                            </div>
                            <?php echo e(form::close()); ?>

                            <?php echo JsValidator::formRequest('App\Http\Requests\Frontend\DeliveryLocationRequest', '#branch-listing'); ?>

                        </div>
                        
                        <?php if($bannerImage !== null): ?> 
                            <img src="<?php echo e(FileHelper::loadImage($bannerImage['banner_file'])); ?>" class="burger wow zoomIn">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- bannner end -->

    <!-- how it works -->

    <section class="how-it-works full_row">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="box">
                        <div class="icons wow zoomIn">
                            <img src="<?php echo e(asset(FRONT_END_BASE_PATH.'/img/how1.png')); ?>">
                        </div>
                        <h3 class="wow fadeInUp"><?php echo e(__('Find')); ?></h3>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="box">
                        <div class="icons wow zoomIn">
                            <img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/how2.png')); ?>" >
                        </div>
                        <h3 class="wow fadeInUp"><?php echo e(__('Order')); ?></h3>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="box">
                        <div class="icons wow zoomIn">
                            <img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/how3.png')); ?>">
                        </div>
                        <h3 class="wow fadeInUp"><?php echo e(__('Enjoy')); ?></h3>
                    </div>
                </div>
            </div>
    </section>

    <!-- how it works -->

    <!-- all restaurants -->

    <section class="all-restaurants">
        <div class="container">

            
            
            <div class="offer-slider owl-carousel">
                <?php $__currentLoopData = $offerItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('frontend.branch.show',[$item->branch_slug])); ?>">
                <div class="slider" style="background: url(../resources/assets/frontend/img/bg.jpg);">
                    <div class="left-img">
                        <img src="<?php echo e($item->offer_banner); ?>" alt="<?php echo e($item->offer_name); ?>" /> 
                        <span><?php echo e($item->offer_value); ?></span>
                    </div>
                    <div class="right-txt">
                        <h1><?php echo e($item->offer_name); ?></h1>
                        <h2><?php echo e($item->item_name); ?></h2>                        
                    </div>
                    <div class="right-img">
                        <img src="<?php echo e($item->branch_logo); ?>" alt="" /> 
                    </div>
                </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
            </div>
            <!-- offer-slider -->

            <h2 class="heading-1 text-center wow fadeInUp"><?php echo e(__('All Restaurants')); ?></h2>

            <div class="restaurant-slider owl-carousel">
                <?php $__currentLoopData = $branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <div class="box">
                    <?php if($value->branch_count > 1): ?>
                        <a href="" class="restaurent_popup" data-action="<?php echo e(url('get-near-branches')); ?>" data-key="<?php echo e($value->vendor_key); ?>" data-img="<?php echo e($value->branch_logo); ?>" data-count="<?php echo e($value->branch_count); ?>" data-id="<?php echo e($value->vendor_id); ?>">
                    <?php else: ?>
                        <a href="<?php echo e(route('frontend.branch.show',[$value->branch_slug])); ?>">
                    <?php endif; ?>
                        <img src="<?php echo e($value->branch_logo); ?>" class="wow zoomIn" id="popup_img">
                        <div><?php echo e($value->vendor_name); ?></div>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

             
                

            </div>

        </div>
    </section>
    <!-- all restaurants -->

    <!-- download apps -->

    <div class="modal fade respopup" id="res_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"> 
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        
                    </div>
                </div>  
            </div>
  <section class="download-apps">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="box">
                        <h2 class="wow fadeInUp"><?php echo e(__('Download Caravan')); ?></h2>
                        <p class="wow fadeInUp"><?php echo e(__('We Bring your favourite Food to your Door')); ?></p>
                        <p class="apps wow fadeInUp">
                            <a href="<?php echo e(url(config('webconfig.play_store_link'))); ?>" target="_blank"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/play_store.png')); ?>"></a>
                            <a href="<?php echo e(url(config('webconfig.app_store_link'))); ?>" target="_blank"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/app_store.png')); ?>"></a>
                        </p>
                      
                    </div>
                </div>
                <div class="col-sm-6  mobile">
                     <img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/home-foodora-apps.png')); ?>">
                   
                </div>
            </div>
        </div>
    </section>
    <!-- download apps -->

    <!-- email news letter -->

    <section class="news_letter">
        <div class="container">

            <div class="row">
                <div class="col-sm-5">
                    <h4 class="wow fadeInUp"><?php echo e(__('Be the First to Know Latest News, Promo Codes, and Offers')); ?> </h4>
                </div>
                <div class="col-sm-7">
                    <?php echo e(Form::open(['route' => 'frontend.newsletter', 'id' => 'newsletter-form', 'class' => 'form-horizontal', 'method' => 'POST'])); ?>

                    <div class="box floating_label wow fadeInUp ">
                        <div class="form-group mb-0">
                            <?php echo e(Form::label("n-email", __('Email'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("email",'', ['class' => 'form-control','id' => "n-email"])); ?>

                        </div>

                        <?php echo Html::decode( Form::button('<span class="shape">'.__('Submit').'</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ); ?>

                    </div>
                    <?php echo e(form::close()); ?>

                    <?php echo JsValidator::formRequest('App\Http\Requests\Frontend\NewsletterRequest', '#newsletter-form'); ?> 

                </div>
            </div>
        </div>
    </section>

    <!-- email news letter -->
    
       <!-- Signup modal -->

    <!-- Corporate discount -->
    <div class="modal login_modal fade" id="discount_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h5 class="modal-title"><?php echo e(__('Corporate Discount')); ?></h5>
                    <div class="icons-add"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/discount.png')); ?>"></div>
                </div>
                <div class="modal-body">
                    <?php echo e(Form::open(['route' => 'frontend.corporate-voucher', 'id' => 'corporate-voucher-form', 'class' => 'form-horizontal', 'method' => 'POST'])); ?>

                    <div class="form-box floating_label">
                        <div class="form-group">
                            <?php echo e(Form::label("d-coupon", __('Enter voucher code'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("voucher_code",'', ['class' => 'form-control','id' => "d-coupon",'maxlength'=>'100'])); ?>

                        </div>
                        <div class="text-right mb-4">
                            <?php if(Auth::guard(GUARD_USER)->check()): ?>
                                <?php echo Html::decode( Form::button('<span class="shape">Submit</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ); ?>

                            <?php else: ?> 
                                <a href="javascript:" class="shape-btn loader shape1 loginModel" url="<?php echo e(route('frontend.signout')); ?>"><span class="shape"><i class="material-icons fly_icon">person_outline</i>  Login</span></a>
                            <?php endif; ?>
                        </div>
                        <?php echo e(form::close()); ?>

                      
                    </div>

                </div>
            </div>
        </div>
    </div>
<script>
function initPlacesLibrary()
{
    var input = document.getElementById('delivery-location');    
    var options = {
        componentRestrictions: {country: "bh"}
    };
    var autocomplete = new google.maps.places.Autocomplete(input,options);
    autocomplete.addListener('place_changed', function() { 
        var place = autocomplete.getPlace();
        $('#latitude').val(place.geometry.location.lat());
        $('#longitude').val(place.geometry.location.lng());
        $('#location').val(place.formatted_address);
    });
}
$(document).ready(function()
{
    $('#branch-listing').submit(function(e) {
        e.preventDefault();
        setTimeout(function() {
            if( $('#latitude').val() == '' || $('#longitude').val() == '') {                
                errorNotify(" <?php echo e(__('Please choose your location')); ?> ");
                return ;
            }
            $('#branch-listing').unbind('submit').submit();
        },1000);        
    });
    $("#get-mylocation" ).click( function(e) {
        e.preventDefault();
        /* HTML5 Geolocation */
        navigator.geolocation.getCurrentPosition(
            function( position ){ // success cb
 
                /* Current Coordinate */
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                var google_map_pos = new google.maps.LatLng( lat, lng );
 
                /* Use Geocoder to get address */
                var google_maps_geocoder = new google.maps.Geocoder();
                var input = document.getElementById('delivery-location');
                google_maps_geocoder.geocode(
                    { 'latLng': google_map_pos },
                    function( results, status ) {
                        if ( status == google.maps.GeocoderStatus.OK && results[0] ) {
                            $('#latitude').val(lat);
                            $('#longitude').val(lng);
                            $('#location').val(results[0].formatted_address);
                            $('#delivery-location').val(results[0].formatted_address);
                        }
                    }
                );
            },
            function(){ // fail cb
                console.log("Your browser doesn't support");
            }
        );
    });
    $('#corporate-voucher-form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({ 
            url: "<?php echo e(route('frontend.corporate-voucher')); ?>",
            type: "POST",            
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.status === 1) {
                    window.location.href = response.redirect_url;
                } else {
                    errorNotify(response.message);
                }
            }
        });
    })
})
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('webconfig.map_key')); ?>&libraries=places&callback=initPlacesLibrary"></script>
<?php $__env->stopSection(); ?>
   
<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>