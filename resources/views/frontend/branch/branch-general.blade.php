<!-- short description -->
    <section class="restaurant-short-information">
        <div class="container">
            <div class="box">
                <div class="box-top">
                    {{-- <div class="img_fly bg_style wow zoomIn" style="background-image:url({{ asset(FRONT_END_BASE_PATH.'img/list-1.png')}});"></div> --}}                    
                    {!! Html::image($branchDetails->branch_logo,$branchDetails->branch_name,['style'=>'width:120px;height:120px;','class' => "img_fly bg_style wow zoomIn" ]); !!}
                    
                    <h4 class="wow fadeInUp">{{$branchDetails->branch_name}}</h4>
                    <p class="wow fadeInUp">{{$branchDetails->branch_cuisine}}</p>
                    <p class="wow fadeInUp">Pay by: {{$branchDetails->payment_option}} </p>    
                    <p class="ui-enhance wow fadeInUp">
                        <span> <i class="material-icons">access_time</i> {{__('Pickup Time')}}: {{$branchDetails->pickup_time.__("Mins")}}</span>
                        <span> <i class="material-icons">access_time</i> {{__('Delivery Time')}}: {{$branchDetails->delivery_time.__("Mins")}}</span>
                    </p>
                </div>
                <p class="ui-enhance wow fadeInUp">
                    <span><i class="min_ord"></i> {{__('Min Order')}}: {{$branchDetails->min_order_value}}</span>
                    <span> <i class="fee"></i> {{__('Delivery Fee')}}: {{$branchDetails->delivery_cost}}</span>
                </p>
                
                <!-- righr side information -->
                <div class="box-right">                    

                    @if($branchDetails->availability_status === AVAILABILITY_STATUS_OPEN)
                        <p class="status wow fadeInUp open"><span>{{__('Open')}}</span></p>
                    @elseif($branchDetails->availability_status === AVAILABILITY_STATUS_CLOSED)
                        <p class="status wow fadeInUp closed"><span>{{__('Closed')}}</span></p>
                    @elseif($branchDetails->availability_status === AVAILABILITY_STATUS_BUSY)
                        <p class="status wow fadeInUp busy"><span>{{__('Busy')}}</span></p>
                    @elseif($branchDetails->availability_status === AVAILABILITY_STATUS_OUT_OF_SERVICE)
                        <p class="status wow fadeInUp outOfService"><span>{{__('Out of Service')}}</span></p>
                    @endif
                    
                    <div class="star-one-row wow fadeInUp">
                        <form>
                            <span class="star-rating view_only">                                
                                @for($i = 1; $i <= 5; $i++ )
                                    <input id="star-{{$branchDetails->branch_key}}" type="checkbox" {{ (round($branchDetails->branch_avg_rating) == $i) ? 'checked="true"' : '' }} name="star">
                                    <label class="star" for="star-{{$branchDetails->branch_key}}" ></label>
                                @endfor
                                {{--<input id="star-4" type="radio" checked="" name="star">
                                <label class="star" for="star-4"></label>
                                <input id="star-3" type="radio" name="star">
                                <label class="star" for="star-3"></label>
                                <input id="star-2" type="radio" name="star">
                                <label class="star" for="star-2"></label>
                                <input id="star-1" type="radio" name="star">
                                <label class="star" for="star-1"></label> --}}
                            </span>
                        </form>
                         <span class="rt">( {{ $branchDetails->branch_rating_count }} {{__('Ratings')}} )</span> 
                        
                    </div>
                    @if(Auth::guard(GUARD_USER)->check())
                    <p>
                        <button class="wishlist_heart fav wow fadeInUp {{ $branchDetails->is_wishlist == 1 ? 'added' : ''}}"  data-wishlist="{{ $branchDetails->is_wishlist == 1 ? 1 : 0}}" value="{{ $branchDetails->branch_key }}">
                            <i class="material-icons">{{__('favorite')}}</i>
                        </button>
                    </p>
                    @else 
                    <p>
                        <button class="fav wow fadeInUp loginModel" value="{{ $branchDetails->branch_key }}">
                            <i class="material-icons">{{__('favorite')}}</i>
                        </button>
                    </p>
                    @endif
                </div>
                <!-- righr side information -->

            </div>
        </div>
    </section>


<!-- mobile cart -->
<div class="cart-toggle-overlay re_overlay"></div>
<div class="mini-mobile-cart"> 2 {{__('Items In Cart')}} <span>{{__('View Cart')}} <i class="fa fa-angle-right"></i></span> </div>

    <!-- short description -->

    <section class="detail-tab wow fadeInUp">
        <div class="container">
            <nav>
                <div class="nav nav-tabs wow fadeInUp" role="tablist">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#nav-menu" role="tab">{{__('Menu')}}</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#nav-info" role="tab">{{__('Info')}}</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#nav-ratings" role="tab">{{__('Ratings')}}</a>
                </div>
            </nav>

        </div>
    </section>