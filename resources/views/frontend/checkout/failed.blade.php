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
                    <img src="{{ asset(FRONT_END_BASE_PATH.'img/sad-smiley.png')}}" class="wow zoomIn">
                </div>

                <h2 class="wow fadeInUp">{{__('Order payment failed')}}.</h2>
                <p class="sub wow fadeInUp">{{__('Order ID')}}:<span>#{{$order->order_number}}</span></p>
                <p class="sub wow fadeInUp">{{__('Order Amount')}}:<span>{{ Common::currency($order->order_total) }}</span></p>
            </div>

            <!-- complete box -->
        </div>
    </section>

    <!-- listing restaurant -->
@endsection
    