@if($branchList->branches !== null && count($branchList->branches) > 0)
<h2 class="heading-1">{{__('All Restaurants')}}</h2> 
<div class="row">
    <!-- col-md-6 -->
    @foreach($branchList->branches as $key => $value)
    <div class="col-md-6">
        <!-- box -->
        <div class="box wow zoomIn">
                
            <!-- listing top -->
        
            <div class="listing-top">
                @if($value->branch_count > 1)
                <a href="#{{--{{route('frontend.branch.show',[$value->branch_slug])}}--}}" class="img bg_style background_img restaurent_popup" data-action="{{url('get-near-branches')}}" data-key="{{$value->vendor_key}}" data-img="{{$value->branch_logo}}" data-count="{{$value->branch_count}}"  style="background-image:url({{$value->branch_logo}});">
                </a>
                <h4 class="text-overflow"><a href="#{{--{{route('frontend.branch.show',[$value->branch_slug])}} --}}" class="restaurent_popup" data-action="{{url('get-near-branches')}}" data-key="{{$value->vendor_key}}" data-img="{{$value->branch_logo}}" data-count="{{$value->branch_count}}" >{{$value->vendor_name}}</a></h4>
                @else
                <a href="{{route('frontend.branch.show',[$value->branch_slug])}}" class="img bg_style background_img" style="background-image:url({{$value->branch_logo}});">
                </a>
                <h4 class="text-overflow"><a href="{{route('frontend.branch.show',[$value->branch_slug])}}" >{{$value->vendor_name}}</a></h4>
                @endif
                <p class="text-overflow">{{$value->branch_cuisine}}</p>
                <div class="star d-flex w100 mb5">
                    <form>
                        <span class="star-rating">
                            @for($i = 1; $i <= 5; $i++ )
                                <input id="star-{{$i.$value->branch_key}}" type="checkbox" {{ (round($value->branch_avg_rating) == $i) ? 'checked="true"' : '' }} name="star">
                                <label class="star" for="star-{{$value->branch_key}}" ></label>
                            @endfor                                            
                        </span>
                    </form>
                    <span class="rt">( {{ $value->branch_rating_count }}  {{__('Ratings')}} )</span> 
                </div>
                <p>Pay by: {{$value->payment_option}} </p>
            </div>  
            <!-- listing top -->
            <div class="listing-bottom full_row">

                <p class="bt-border">
                    <span> <i class="material-icons">access_time</i> {{__('Pickup Time')}}: {{$value->pickup_time.__("Mins")}}</span>
                    <span> <i class="material-icons">access_time</i> {{__('Delivery Time')}}: {{$value->delivery_time.__("Mins")}}</span>
                    @if(!Auth::guard(GUARD_USER)->check())
                        <button class="fav loginModel"><i class="material-icons">favorite</i></button>
                    @else
                        <button class="fav wishlist_heart {{ ($value->is_wishlist == 1) ? 'added' : '' }}" data-wishlist="{{ $value->is_wishlist == 1 ? 1 : 0}}" value="{{$value->branch_key}}" ><i class="material-icons">favorite</i></button>
                    @endif
                </p>
                <p> 
                    <span><i class="min_ord" ></i> {{__('Min Order')}}: {{$value->min_order_value}}</span>
                    <span> <i class="fee"></i> {{__('Delivery Fee')}}: {{$value->delivery_cost}}</span>
                    @if($value->branch_count > 1)
                    <span><i class="fee1"></i><a class="res_outlet restaurent_popup" id="restaurent_popup" data-action="{{url('get-near-branches')}}" data-key="{{$value->vendor_key}}" data-img="{{$value->branch_logo}}" data-count="{{$value->branch_count}}"  >
                 {{$value->branch_count}} {{__('Outlets')}}
                </a></span>
                @endif
                </p>
              
            </div>
            
        </div>
        <!-- box -->
    </div> 
    @endforeach                    
    <!-- col-md-6 -->
</div>
 <div class="modal fade respopup" id="res_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"> 
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                
            </div>
        </div>  
</div> 
 <!-- restaurant popup -->
    {{--  <div class="modal fade respopup" id="res_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
     
      <div class="modal-body">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="popup-img" style="background-image:url(https://www.seriouseats.com/recipes/images/2015/07/20150728-homemade-whopper-food-lab-35.jpg)"></div>
       
       <div class="content">
        <h4>Choose outlet</h4>
        <p>4 outlet near you</p>
        <ul>
            <li>
                <div class="poplist">
                    <p><a href="#">RKV colony, rakav garafetioi sai baba colony </a></p>
                </div>
                 <div class="poplist show">
                    <span class="rating"><i class="fa fa-star" aria-hidden="true"></i><span>4.3</span></span>
                    <span class="time_min">33 mins</span>
                </div>
              
            </li>
             <li>
                <div class="poplist">
                      <p><a href="#">sai baba colony</a></p>
                </div>
                 <div class="poplist show">
                    <span class="rating"><i class="fa fa-star" aria-hidden="true"></i><span>4.3</span></span>
                    <span class="time_min">33 mins</span>
                </div>
              
            </li>
             
              <li>
                <div class="poplist">
                    <p><a href="#">sai baba colony</a></p>
                </div>
                 <div class="poplist show">
                    <span class="rating"><i class="fa fa-star" aria-hidden="true"></i><span>4.3</span></span>
                    <span class="time_min">33 mins</span>
                </div>
              
            </li>
             
              <li>
                <div class="poplist">
                      <p><a href="#">sai baba colony</a></p>
                </div>
                 <div class="poplist show">
                    <span class="rating"><i class="fa fa-star" aria-hidden="true"></i><span>4.3</span></span>
                    <span class="time_min">33 mins</span>
                </div>
              
            </li>

        </ul>
      </div>
   
    </div>
  </div>
</div> --}} 
@else 
    <h2 class="heading-1">{{__('No Restaurants match found for your search...')}}</h2>
@endif

   