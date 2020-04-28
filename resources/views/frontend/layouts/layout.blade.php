@php
session_start();
@endphp
<!DOCTYPE HTML>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="_url" content="{{url('/')}}" />
    <meta name="_routeName" content="{{  strstr(Route::currentRouteName(), '.' , true) }}" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">  
    <title>{{ config('webconfig.app_name') }}</title> 
    <link rel="shortcut icon" href="{{ FileHelper::loadImage(config('webconfig.app_favicon')) }}" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    {!! AssetHelper::loadFrontendAsset(1) !!}
    <script src="{{ asset('resources/assets/general/ajax-init.js')}}"></script>
</head>

<body>
    
    <!-- header -->

    <header class="top-header">
    <div class="re_overlay header-menu-overlay"></div>
        <div class="container">
            <div class="logo">
            <a href="javascript:void(0);" class="responsive-menu"><i class="material-icons">menu</i></a>
            <a href="/"><img src="{{ FileHelper::loadImage(config('webconfig.app_logo')) }}" class="img-responsive"></a>
            </div>
            <div class="navigation">
            <!-- mobile responsive -->
                <div class="mobile-responsivebox">
                <a href="/"><img src="{{ FileHelper::loadImage(config('webconfig.app_logo')) }}" class="img-responsive"></a>
                <span class="close-header-menu">&times;</span>
                </div>
                <!-- mobile responsive -->
                <ul>
                    <li>
                        <a href="javascript:" id="corporate_offer_toggle">{{ __('Corporate Offers') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.offers') }}">{{ __('Offers') }}</a>
                    </li>
                    <li><a href="{{ route('frontend.branch.index',['type'=>'all']) }}">{{__('All Restaurants') }}</a></li>
                   {{--<li><a href="javascript:" data-toggle="modal" data-target="#driver_registration">{{ __('Become a driver') }}</a></li>--}}
                    @foreach($languages as $key => $value)
                        <li><a href="javascript:void(0);" class="language" style="color:{{ Config::get('app.locale') == $key ? '#fe1509' : '' }}" data="{{$key}}">{{$key}}</a></li>
                    @endforeach

                    @if(Auth::guard(GUARD_USER)->check() && Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CORPORATES)
                    <li class="login">
                        <a href="{{route('frontend.signout') }}" class="bg-border"> <i class="material-icons fly_icon">exit_to_app</i> {{__('Exit Corporate') }}</a>
                    </li>                    
                    @endif

                    @if(!Auth::guard(GUARD_USER)->check())
                    <!-- before Login -->
                    <li class="login">
                        <a href="javascript:" class="bg-border loginModel" url="{{route('frontend.signout') }}"> <i class="material-icons fly_icon">person_outline</i> {{__('Login') }}</a>
                    </li>                    
                    <!-- before Login -->                    
                    @elseif(Auth::guard(GUARD_USER)->check() && Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CUSTOMER)
                    <!-- after login -->
                    <li class="my_account dropdown">
                        <a href="#" id="user-profile" data-toggle="dropdown" class="dd-down"> <i class="material-icons fly_icon">keyboard_arrow_down</i> {{__('My Account') }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="user-profile">
                            <a class="dropdown-item" href="javascript:" data-toggle="modal" data-target="#edit-profile">{{__('My Profile') }}</a>
                            <a class="dropdown-item" href="{{route('address.index')}}">{{__('Address Book') }}</a>
                            <a class="dropdown-item" href="{{route('frontend.myorder')}}">{{__('My Orders') }}</a>
                            <a class="dropdown-item" href="{{route('frontend.wishlist')}}">{{__('Favourite Restaurants') }}</a>
                            <a class="dropdown-item" href="{{route('frontend.wallet')}}">{{__('C wallet') }}</a>
                            <a class="dropdown-item" href="{{route('frontend.loyalty-points')}}">{{__('loyalty Points') }}</a>
                            @foreach($cms as $key => $value)
                                @if($value->position == 3)
                                    <a class="dropdown-item" href="{{route('frontend.cms', $value->slug)}}">{{__('Help') }}</a>
                                @endif
                            @endforeach
                            <a class="dropdown-item" href="{{route('frontend.signout')}}">{{__('Logout') }}</a>
                        </div>

                    </li>
                    <!-- after login -->
                    @endif
                    
                    @if(Auth::guard(GUARD_USER)->check())
                        @php
                            $cartLayout = Common::cartCount( Auth::guard(GUARD_USER)->user()->user_id );                        
                        @endphp
                        @if($cartLayout['cart_count'] > 0)
                            <li class="last" id="cartIconLi" branch-key="{{ $cartLayout['branch_key']}}"><a href="{{route('frontend.checkout',[$cartLayout['branch_slug'] ])}}" class="cart-navigation"><span id="cartCountSpan">{{ $cartLayout['cart_count'] }}</span></a></li>
                        @else 
                            <li class="last" id="cartIconLi" branch-key=""><a href="" class="cart-navigation"><span id="cartCountSpan">0</span></a></li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </header>

    <!-- header -->
    @yield('content')
    <!-- footer -->
   
    <footer class="footer full_row">
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- col-sm-4 -->
                <div class="col-sm-4">
                    <h3 class="wow fadeInUp">{{__('Useful Links') }}</h3>
                    <div class="expand_section wow fadeInUp">
                        <ul class="quick_links">
                            @foreach($cms as $key => $value)
                            <li><a href="{{route('frontend.cms', $value->slug)}}">{{$value->title}}</a></li>
                            @endforeach
                            <li><a href="{{route('frontend.faq')}}">{{__('FAQ') }}</a></li>
                            <li><a href="{{route('contact.index')}}">{{__('Contact Us') }}</a></li>
                        </ul>
                    </div>
                </div>
                <!-- col-sm-4 -->
                <div class="col-sm-4">
                    <h3 class="wow fadeInUp">{{__('Contact Info') }}</h3>
                    <div class="expand_section wow fadeInUp">
                        <ul class="quick_contact">
                            <li><i class="material-icons">location_on</i> {{ config('webconfig.app_address') }}</li>
                            <li><i class="material-icons">call</i>{{ config('webconfig.app_contact_number') }}</li>
                            <li><i class="material-icons">mail</i><a href="mailto:{{ config('webconfig.app_email') }}">{{ config('webconfig.app_email') }}</a></li>
                        </ul>
                    </div>
                </div>
                <!-- col-sm-4 -->
                <div class="col-sm-4">
                    <h3 class="wow fadeInUp">{{__('Social Media') }}</h3>
                    <div class="expand_section wow fadeInUp">
                        <div class="social-icons">
                            <a href="{{ (preg_match("/http/",config('webconfig.social_facebook'))) ? config('webconfig.social_facebook') : 'http://'.config('webconfig.social_facebook') }}" target="_blank"><i class="fa fa-facebook"></i>{{__('Facebook') }}</a>
                            <a href="{{ (preg_match("/http/",config('webconfig.social_twitter'))) ? config('webconfig.social_twitter') : 'http://'.config('webconfig.social_twitter') }}" target="_blank"><i class="fa fa-twitter"></i>{{__('Twitter') }}</a>
                            <a href="{{ (preg_match("/http/",config('webconfig.social_instagram'))) ? config('webconfig.social_instagram') : 'http://'.config('webconfig.social_instagram') }}" target="_blank"><i class="fa fa-instagram"></i>{{__('Instagram') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col-sm-4 -->
        </div>
        <!-- row -->
        <div class="copy-rights text-center">
            <div class="container">
                <p class="wow fadeInUp">{{__('© 2019 caravan . All rights reserved Powered by caravan') }}</p>
            </div>
        </div>
    </footer>    

    @includeWhen((!Auth::guard(GUARD_USER)->check()), 'frontend.layouts.partials._authmodel')
    @includeWhen((Auth::guard(GUARD_USER)->check()) , 'frontend.layouts.partials._editprofile')
    @include('frontend.layouts.partials._corporate_offer')


    <div class="modal login_modal fade" id="driver_registration">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h5 class="modal-title">{{__('Driver Registration')}}</h5>
                    <div class="icons-add"><img src="{{ asset(FRONT_END_BASE_PATH.'img/icon2.png') }}"></div>

                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => 'frontend.driver-registration', 'id' => 'driver-register-form', 'class' => 'form-horizontal', 'method' => 'POST']) }}
                    <div class="form-box floating_label">
                        <div class="form-group">
                            {{ Form::label("d-username", __('Name'), ['class' => 'required' ]) }}
                            {{ Form::text("username",'', ['class' => 'form-control','id' => "d-username",'maxlength'=>'100']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label("d-email", __('Email'), ['class' => 'required' ]) }}
                            {{ Form::text("email",'', ['class' => 'form-control','id' => "d-email",'maxlength'=>'100']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label("d-mobile_number", __('Mobile Number'), ['class' => 'required' ]) }}
                            {{ Form::text("mobile_number",'', ['class' => 'form-control','id' => "d-mobile_number",'maxlength'=>'15']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label("d-license", __('License Number'), ['class' => 'required' ]) }}
                            {{ Form::text("license",'', ['class' => 'form-control','id' => "d-license",'maxlength'=>'30']) }}
                        </div> 


                        <div class="form-group">
                            {{ Form::label("d-vehicle_number", __('Vehicle Number'), ['class' => 'required' ]) }}
                            {{ Form::text("vehicle_number",'', ['class' => 'form-control','id' => "d-vehicle_number",'maxlength'=>'30']) }}
                        </div> 

                        <div class="form-group">
                            {{ Form::label("d-password", __('Password'), ['class' => 'required' ]) }}
                            {{ Form::password("password", ['class' => 'form-control','id' => "d-password",'maxlength'=>'20']) }} 
                        </div>
                        <div class="form-group">
                            {{ Form::label("d-confirm_password", __('Confirm Password'), ['class' => 'required' ]) }}
                            {{ Form::password("confirm_password", ['class' => 'form-control','id' => "d-confirm_password",'maxlength'=>'20']) }} 
                        </div>

                       <div class="check-group mb-4">                                
                            {{ Form::checkbox('terms',1,null,[ 'id' => 'driver-registration', "class" => "checkbox" ]) }}
                            {!! Html::decode( Form::label('driver-registration', __('I Accept the').' '.Html::link('link', 'Terms and Conditions', ['']), ['class' => 'checkbox f18']) )  !!}                                
                        </div>

                        <div class="text-right mb-4">
                            {!! Html::decode( Form::button('<span class="shape">Submit</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ) !!}
                        </div>
                        {{ form::close() }}
                        {!! JsValidator::formRequest('App\Http\Requests\Frontend\DriverRegisterRequest', '#driver-register-form')  !!} 
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- footer -->
    {!! AssetHelper::loadFrontendAsset() !!}
    </body>

</html>
<script>
$(document).ready(function(){
    $('.language').on('click',function (e) {
        e.preventDefault();
        var language = $(this).attr('data');
        $.ajax({ 
            url: "{{route('frontend.language')}}",
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
            url: "{{route('frontend.corporate-login')}}",
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