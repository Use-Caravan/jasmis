@extends('frontend.layouts.layout')
@section('content')
<section class="padd-20">
    <div class="container">
        <!-- breadcums -->
        <div class="breadcums mb-0 wow fadeInUp">
            <ul class="reset">
                <li><a href="{{route('frontend.index')}}">{{__('Home')}}</a></li>
                <li><span>{{__('Loyalty Points')}}</span></li>
            </ul>
        </div>
        <!-- breadcums -->
    </div>
</section>
<section class="myaccount-page">
    <div class="container">
        @include('frontend.layouts.partials._profile-section')
            <div class="border-boxed">
                <div class="full_row">
                    @include('frontend.layouts.partials._profile_sidemenu')
                    <div class="account-content">
                <h2 class="account-title wow fadeInUp">{{__('Loyalty loyalty-points')}}</h2>

            <!-- box -->
                    <div class="loyalty_box wow fadeInUp">
                        <div class="icons"><img src="{{ asset(FRONT_END_BASE_PATH.'img/icon-loyal.png')}}"></div>
                        <div class="full_row">
                            <h4>{{__('You Have Collected')}}</h4>
                            <div class="amount" id="redeem_amount">
                                {{ Auth::guard(GUARD_USER)->user()->loyalty_points }}
                            </div>
                        </div>
                        <div class="full_row">
                            <img src="{{ asset(FRONT_END_BASE_PATH.'img/level1.png')}}">
                            <h5 class="level">{{__('Level')}} : <span id="loyaltyname">{{$loyaltyLevelName->data->loyalty_level_name}}</span></h5>
                            <a href="javascript:void(0);" class="link">{{__('Redeem Reward Points ?')}}</a>
                        </div>
                    </div>
                    <!-- box -->
                    <!-- row -->
                    <div class="full_row reedam_reward wow fadeInUp">
                        <h4>{{__('Redeem Reward Points')}} </h4>
                        <form action="{{url(route('frontend.redeempoint'))}}" id="redeem-form" method="POST">
                        <div class="full_row">
                            <div class="form-group">
                                <input type="text" id="redeem_points" name="points" class="form-control" value="" placeholder="0.00">
                            </div>
                            <button class="shape-btn shape1" data-target="#warning"  data-toggle="modal"><span class="shape">{{__('Proceed')}}</span></button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
<!-- row -->

                    