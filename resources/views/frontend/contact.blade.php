@extends('frontend.layouts.layout')
@section('content')
    <section class="cms-breadcums">
        <div class="container">
            <!-- breadcums -->
            <div class="breadcums wow fadeInUp">
                <ul class="reset">
                    <li><a href="{{route('frontend.index')}}">{{__('Home')}}</a></li>
                    <li><span>{{__('Contact Us')}}</span></li>
                </ul>
            </div>
            <!-- breadcums -->
        </div>
    </section>

    <!-- listing restaurant -->

    <section class="contact-page">
        <div class="container">
            <!-- white box -->
            <div class="white-box shadow-sm wow fadeInUp ">
                <h2 class="heading">{{__('Contact Us')}}</h2>
                <!-- Row -->
                <div class="row">
                    <div class="col-md-7">
                        {{ Form::open(['route' => 'contact.store', 'id' => 'contact', 'class' => 'form-horizontal', 'method' => 'POST']) }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::text("first_name",'', ['class' => 'form-control','placeholder' => __('Name')]) }} 
                                    </div>
                                </div>
                                {{--<div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::text("last_name",'', ['class' => 'form-control','placeholder' => __('Last Name')]) }} 
                                    </div>
                                </div> --}}
                            </div> 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::text("email",'', ['class' => 'form-control','placeholder' => __('Email')]) }} 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::text("phone_number",'', ['class' => 'form-control','placeholder' => __('Phone Number')]) }} 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::textarea("comments",'', ['class' => 'form-control','placeholder' => __('Your Comments')]) }} 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                {!! Html::decode( Form::button('<span class="shape">'.__('Send').'</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ) !!}
                            </div>
                        {{ form::close() }}
                        {!! JsValidator::formRequest('App\Http\Requests\Frontend\ContactRequest', '#contact')  !!} 
                    </div>
                    <div class="col-md-5">
                        <h5>{{__('CARAVAN')}}</h5>
                        <ul class="quick_contact">
                            <li><i class="material-icons">location_on</i>{{ config('webconfig.app_address') }}</li>
                            <li><i class="material-icons">call</i><a href="{{ config('webconfig.app_contact_number') }}">{{ config('webconfig.app_contact_number') }}</a></li>
                            <li><i class="material-icons">mail</i><a href="{{ config('webconfig.app_email') }}">{{ config('webconfig.app_email') }}</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Row -->
                <div class="map-contact" id="map"></div>
                <!-- row -->
            </div>
            <!-- white box -->
        </div>
    </section>

    <!-- listing restaurant -->

    
    <script>
        function initMap() {
            var mapCanvas = document.getElementById("map");
            var myCenter = new google.maps.LatLng({{ config('webconfig.app_latitude') }},{{ config('webconfig.app_longitude') }});
            var mapOptions = {
                center: myCenter,
                zoom: 10
            };
            var map = new google.maps.Map(mapCanvas, mapOptions);
            var marker = new google.maps.Marker({
                position: myCenter,
                icon: "{{ asset(FRONT_END_BASE_PATH.'img/map_pin.png')}}",
                animation: google.maps.Animation.BOUNCE
            });
            marker.setMap(map);
        }
    </script>
 <script src="https://maps.googleapis.com/maps/api/js?key={{ config('webconfig.map_key') }}&callback=initMap&sensor=false"></script>
    
@endsection