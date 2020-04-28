
@extends('frontend.layouts.layout')
@section('content')
    
    <body class="top-popup">
        <div class="driver-banner">
            <p class="content">{{__('Become a Caravan rider')}} {{--<button type="button" class="btn btn-primary">Apply Now</button>--}}<a href="javascript:" class="btn btn-primary" id="apply_driver" data-toggle="modal" data-target="#driver_registration">{{__('Apply Now')}}</span>
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
                        <h1 class="wow fadeInDown"> {{ ($bannerImage !== null) ? $bannerImage['banner_name'] : '' }}</h1>

                        <div class="banner-search">
                            {{ Form::open(['route' => 'frontend.branch.index', 'id' => 'branch-listing', 'class' => 'form-horizontal', 'method' => 'GET']) }}
                           <div class="form-group boxed wow fadeInUp">
                                {{ Form::text("",'' ,['class' => 'form-control','placeholder' => __('Enter your delivery location'),'id' => 'delivery-location']) }}
                                {{ Form::hidden('latitude','', ['id' => 'latitude']) }}
                                {{ Form::hidden('longitude','', ['id' => 'longitude']) }}
                                {{ Form::hidden('location','', ['id' => 'location']) }}
                                <a href="javascript:void(0);" class="gps" id="get-mylocation" ><i class="material-icons">my_location</i></a>
                            </div>
                            <div class="form-group radio-btn wow fadeInUp">
                                <div class="radio-box">
                                    <input type="radio" name="order_type" value="{{ ORDER_TYPE_DELIVERY }}" id="order_type{{ ORDER_TYPE_DELIVERY}}" checked>
                                    <label for="order_type{{ ORDER_TYPE_DELIVERY}}">{{__('Delivery')}}</label>
                                    <input type="radio" name="order_type" value="{{ ORDER_TYPE_PICKUP_DINEIN }}" id="order_type{{ ORDER_TYPE_PICKUP_DINEIN }}">
                                    <label for="order_type{{ ORDER_TYPE_PICKUP_DINEIN }}">{{__('Pickup & Dine In')}}</label>
                                    <input type="radio" name="order_type" value="{{ ORDER_TYPE_PICKUP_DINEIN }}" id="order_type{{ ORDER_TYPE_PICKUP_DINEIN }}">
                                    <label for="order_type{{ ORDER_TYPE_PICKUP_DINEIN }}" data-toggle="modal" data-target="#discount_modal">{{__('Use Corporate Discount')}}</label>
                                </div>
                                <div class="btn-box wow fadeInUp">
                                    {{-- <button onClick="location.href='{{ route('frontend.listing') }}'" class="shape-btn loader"><span class="shape">Search</span></button> --}}
                                    {!! Html::decode( Form::button('<span class="shape">'.__('Search').'</span>', ['type'=>'submit', 'class' => 'shape-btn']) ) !!} 
                                </div>
                            </div>
                            {{ form::close() }}
                            {!! JsValidator::formRequest('App\Http\Requests\Frontend\DeliveryLocationRequest', '#branch-listing')  !!}
                        </div>
                        {{-- <img src="{{ asset(FRONT_END_BASE_PATH.'/img/banner-burger.png') }}" class="burger wow zoomIn"> --}}
                        @if($bannerImage !== null) 
                            <img src="{{ FileHelper::loadImage($bannerImage['banner_file']) }}" class="burger wow zoomIn">
                        @endif
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
                            <img src="{{ asset(FRONT_END_BASE_PATH.'/img/how1.png') }}">
                        </div>
                        <h3 class="wow fadeInUp">{{__('Find')}}</h3>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="box">
                        <div class="icons wow zoomIn">
                            <img src="{{ asset(FRONT_END_BASE_PATH.'img/how2.png') }}" >
                        </div>
                        <h3 class="wow fadeInUp">{{__('Order')}}</h3>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="box">
                        <div class="icons wow zoomIn">
                            <img src="{{ asset(FRONT_END_BASE_PATH.'img/how3.png') }}">
                        </div>
                        <h3 class="wow fadeInUp">{{__('Enjoy')}}</h3>
                    </div>
                </div>
            </div>
    </section>

    <!-- how it works -->

    <!-- all restaurants -->

    <section class="all-restaurants">
        <div class="container">

            {{-- <div class="row mb-4">
                <div class="col-sm-8">
                    <a href="#"><img src="{{ asset(FRONT_END_BASE_PATH.'img/caravan-banner1.png') }}" class="wow fadeInUp"></a>
                </div>
                <div class="col-sm-4">
                    <a href="#"><img src="{{ asset(FRONT_END_BASE_PATH.'img/caravan-banner2.png') }}" class="wow fadeInUp"></a>
                </div>
            </div>  --}}
            
            <div class="offer-slider owl-carousel">
                @foreach($offerItems as $key => $item)
                <a href="{{ route('frontend.branch.show',[$item->branch_slug]) }}">
                <div class="slider" style="background: url(../resources/assets/frontend/img/bg.jpg);">
                    <div class="left-img">
                        <img src="{{$item->offer_banner}}" alt="{{$item->offer_name}}" /> 
                        <span>{{$item->offer_value}}</span>
                    </div>
                    <div class="right-txt">
                        <h1>{{$item->offer_name}}</h1>
                        <h2>{{$item->item_name}}</h2>                        
                    </div>
                    <div class="right-img">
                        <img src="{{$item->branch_logo}}" alt="" /> 
                    </div>
                </div>
                </a>
                @endforeach
                {{-- <div class="slider" style="background: url(../resources/assets/frontend/img/bg.jpg);">
                    <div class="left-img">
                        <img src="../resources/assets/frontend/img/shavarma.png" alt="" /> 
                        <span>20 % OFF</span>
                    </div>
                    <div class="right-txt">
                        <h1>BIG "J" VALUE MEAL</h1>
                        <h2>CHICKEN OR BEEF</h2>
                    </div>
                    <div class="right-img">
                        <img src="../resources/assets/frontend/img/o1.png" alt="" /> 
                    </div>
                </div> --}}
            </div>
            <!-- offer-slider -->

            <h2 class="heading-1 text-center wow fadeInUp">{{__('All Restaurants')}}</h2>

            <div class="restaurant-slider owl-carousel">
                @foreach($branch as $key => $value)
                
                <div class="box">
                    @if($value->branch_count > 1)
                        <a href="{{--{{ route('frontend.branch.show',[$value->branch_slug]) }}--}}" class="restaurent_popup" data-action="{{url('get-near-branches')}}" data-key="{{$value->vendor_key}}" data-img="{{$value->branch_logo}}" data-count="{{$value->branch_count}}" data-id="{{$value->vendor_id}}">
                    @else
                        <a href="{{ route('frontend.branch.show',[$value->branch_slug]) }}">
                    @endif
                        <img src="{{$value->branch_logo }}" class="wow zoomIn" id="popup_img">
                        <div>{{$value->vendor_name}}</div>
                    </a>
                </div>
                @endforeach

             
                {{-- <div class="box">
                    <a href="">
                        <img src="{{ asset(FRONT_END_BASE_PATH.'img/re2.png') }}" class="wow zoomIn">
                        <div>Dajajio</div>
                    </a>
                </div>

                <div class="box">
                    <a href="">
                        <img src="{{ asset(FRONT_END_BASE_PATH.'img/re3.png') }}" class="wow zoomIn">
                        <div>Marash</div>
                    </a>
                </div>

                <div class="box">
                    <a href="">
                        <img src="{{ asset(FRONT_END_BASE_PATH.'img/re4.png') }}" class="wow zoomIn">
                        <div>Tony Roma's</div>
                    </a>
                </div>

                <div class="box">
                    <a href="">
                        <img src="{{ asset(FRONT_END_BASE_PATH.'img/re5.png') }}" class="wow zoomIn">
                        <div>Le Chocolat</div>
                    </a>
                </div>

                <div class="box">
                    <a href="">
                        <img src="{{ asset(FRONT_END_BASE_PATH.'img/re1.png') }}" class="wow zoomIn">
                        <div>Jasmi’s</div>
                    </a>
                </div> --}}

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
                        <h2 class="wow fadeInUp">{{__('Download Caravan')}}</h2>
                        <p class="wow fadeInUp">{{__('We Bring your favourite Food to your Door')}}</p>
                        <p class="apps wow fadeInUp">
                            <a href="{{url(config('webconfig.play_store_link'))}}" target="_blank"><img src="{{ asset(FRONT_END_BASE_PATH.'img/play_store.png') }}"></a>
                            <a href="{{url(config('webconfig.app_store_link'))}}" target="_blank"><img src="{{ asset(FRONT_END_BASE_PATH.'img/app_store.png') }}"></a>
                        </p>
                      
                    </div>
                </div>
                <div class="col-sm-6  mobile">
                     <img src="{{ asset(FRONT_END_BASE_PATH.'img/home-foodora-apps.png') }}">
                   {{--  <div class="box rg">
                        <h2 class="wow fadeInUp">{{__('For Drivers')}}</h2>
                        <p class="wow fadeInUp">{{__('Make great money and set your own schedule')}}</p>
                        <button class="shape-btn wow fadeInUp" data-toggle="modal" data-target="#driver_registration"><span class="shape">{{__('Become a driver')}}</span></button>
                        <img src="{{ asset(FRONT_END_BASE_PATH.'img/app-right.png') }}" class="fly wow fadeInUp">
                    </div> --}}
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
                    <h4 class="wow fadeInUp">{{__('Be the First to Know Latest News, Promo Codes, and Offers')}} </h4>
                </div>
                <div class="col-sm-7">
                    {{ Form::open(['route' => 'frontend.newsletter', 'id' => 'newsletter-form', 'class' => 'form-horizontal', 'method' => 'POST']) }}
                    <div class="box floating_label wow fadeInUp ">
                        <div class="form-group mb-0">
                            {{ Form::label("n-email", __('Email'), ['class' => 'required' ]) }}
                            {{ Form::text("email",'', ['class' => 'form-control','id' => "n-email"]) }}
                        </div>

                        {!! Html::decode( Form::button('<span class="shape">'.__('Submit').'</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ) !!}
                    </div>
                    {{ form::close() }}
                    {!! JsValidator::formRequest('App\Http\Requests\Frontend\NewsletterRequest', '#newsletter-form')  !!} 

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
                    <h5 class="modal-title">{{__('Corporate Discount')}}</h5>
                    <div class="icons-add"><img src="{{ asset(FRONT_END_BASE_PATH.'img/discount.png') }}"></div>
                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => 'frontend.corporate-voucher', 'id' => 'corporate-voucher-form', 'class' => 'form-horizontal', 'method' => 'POST']) }}
                    <div class="form-box floating_label">
                        <div class="form-group">
                            {{ Form::label("d-coupon", __('Enter voucher code'), ['class' => 'required' ]) }}
                            {{ Form::text("voucher_code",'', ['class' => 'form-control','id' => "d-coupon",'maxlength'=>'100']) }}
                        </div>
                        <div class="text-right mb-4">
                            @if(Auth::guard(GUARD_USER)->check())
                                {!! Html::decode( Form::button('<span class="shape">Submit</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ) !!}
                            @else 
                                <a href="javascript:" class="shape-btn loader shape1 loginModel" url="{{route('frontend.signout') }}"><span class="shape"><i class="material-icons fly_icon">person_outline</i>  Login</span></a>
                            @endif
                        </div>
                        {{ form::close() }}
                      {{--  {!! JsValidator::formRequest('App\Http\Requests\Frontend\DriverRegisterRequest', '#driver-register-form')  !!} --}}
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
                errorNotify(" {{ __('Please choose your location') }} ");
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
            url: "{{route('frontend.corporate-voucher')}}",
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
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('webconfig.map_key') }}&libraries=places&callback=initPlacesLibrary"></script>
@endsection
   