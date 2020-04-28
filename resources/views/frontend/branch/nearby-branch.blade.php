 <!-- restaurant popup -->
 <div class="modal-body">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="popup-img" style="background-image:url(https://www.seriouseats.com/recipes/images/2015/07/20150728-homemade-whopper-food-lab-35.jpg)"></div>

       <div class="content">
        <h4>{{__("Choose Outlet")}}</h4>
        <p><span id="branch_count"></span> {{__("Outlets near you")}}</p>
        <ul>
            @foreach($branchList as $key=>$value)
            <li>
                <div class="poplist">
                    {{--<p><a href="#">RKV colony, rakav garafetioi sai baba colony </a></p>--}}
                    <p><a href="{{route('frontend.branch.show',[$value->branch_slug])}}">{{$value->branch_name}} </a></p>
                </div>
                 <div class="poplist show">
                    {{--@if($value->branch_avg_rating !== null) --}}
                    <span class="rating"><i class="fa fa-star" aria-hidden="true"></i><span>{{$value->branch_avg_rating}}</span></span>
                    {{--@endif--}}
                    <span class="time_min">{{$value->delivery_time.__("Mins")}}</span>
                </div>
            </li>
            @endforeach
        </ul>
      </div>
   
 