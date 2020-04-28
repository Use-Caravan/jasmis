@extends('frontend.layouts.layout')
@section('content')

    <!-- listing restaurant -->

    <section class="order-confirmation">
        <div class="container">
            <!-- breadcums -->
            <div class="breadcums wow fadeInUp">
                <ul class="reset">
                    <li><a href="{{route('frontend.index')}}">{{__('Home')}}</a></li>
                    <li><a href="{{route('frontend.branch.index')}}">{{__('Restaurants')}}</a></li>
                    {{-- <li><a href="{{route('frontend.detail')}}">Jasmis</a></li> --}}
                    {{-- <li><a href="{{route('frontend.checkout')}}">Checkout</a></li> --}}
                    <li><span>{{__('Order Confirmation')}}</span></li>
                </ul>
            </div>
            <!-- breadcums -->
            <!-- complete box -->

            <div class="complete-box">

                <div class="full_row">
                    <div class="bg_style wow zoomIn" style="background-image:url({{ FileHelper::loadImage($order->branch_logo) }});"></div>
                </div>
                <div class="full_row">
                    <img src="{{ asset(FRONT_END_BASE_PATH.'img/smile.png')}}" class="wow zoomIn">
                </div>

                <h2 class="wow fadeInUp">{{__('Order Placed Successfully')}}.</h2>
                <p class="sub wow fadeInUp">{{__('Order ID')}}:<span>#{{$order->order_number}}</span></p>
                <p class="sub wow fadeInUp">{{__('Order Amount')}}:<span>{{ Common::currency($order->order_total) }}</span></p>
                <div class="divider "></div>
                <p class="breaks wow fadeInUp">{{__('A confirmation email has been sent to')}} <span>{{$order->user_email}}</span></p>

                
                @if(Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CUSTOMER)
                    <a class="shape-btn wow fadeInUp loader shape1" href="{{ route('frontend.myorder') }}"><span class="shape">{{__('Go to Orders')}}</span></a>
                @else
                    <a class="shape-btn wow fadeInUp loader shape1" href="{{ route('frontend.signout') }}"><span class="shape">{{__('Exit Corporate')}}</span></a>
                @endif

            </div>

            <!-- complete box -->
        </div>
    </section>

    <!-- listing restaurant -->
@endsection
    