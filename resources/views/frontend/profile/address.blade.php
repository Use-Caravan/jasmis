@extends('frontend.layouts.layout')
@section('content')
<section class="padd-20">
    <div class="container">
        <!-- breadcums -->
        <div class="breadcums mb-0 wow fadeInUp">
            <ul class="reset">
                <li><a href="{{route('frontend.index')}}">{{__('Home')}}</a></li>
                <li><span>{{__('User Address')}}</span></li>
            </ul>
        </div>
        <!-- breadcums -->
    </div>
</section>
<!-- listing restaurant -->
<section class="myaccount-page">
    <div class="container">
        @include('frontend.layouts.partials._profile-section')
        <div class="border-boxed">
            <div class="full_row">
                <!-- myaccount box -->
                @include('frontend.layouts.partials._profile_sidemenu')
                <div class="account-content">
                    <h2 class="account-title wow fadeInUp">{{__('Address Book')}}</h2>
                    <div class="row-1">
                        <ul class="reset address_list wow fadeInUp">                            
                            @foreach($addressDetails as $key => $value)
                            <li>
                                <div class="box wow fadeInUp">
                                    <h4>{{$value->address_type_name}}</h4>
                                    <address> 
                                        <i class="icon-location-pin"></i>
                                        {{$value->address_line_one}},{{$value->address_line_two}}
                                    </address>
                                    <div class="options">
                                        <a href="javascript:" data-action="{{ route('address.show',[$value->user_address_key]) }}" class="editaddress" data-toggle="modal" data-target="#edit_address"><i  data-toggle="tooltip" title="Edit" class="icon-pencil" ></i></a>
                                        <a href="javascript:" data-action="{{ route('address.destroy',[$value->user_address_key]) }}" class="deleteaddress" ><i data-toggle="tooltip" title="Remove" class="icon-trash"></i></a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- myaccount box -->
            </div>
        </div>
        <div class="full_row mt-3 mb-3 text-right">
            <button class="shape-btn wow fadeInUp shape1 addAddress"><span class="shape">{{__('Add Address')}}</span></button>
        </div>
    </div>
</section>
<!-- listing restaurant -->
@include('frontend.layouts.partials._addressmodel')
@endsection
