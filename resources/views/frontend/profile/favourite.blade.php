@extends('frontend.layouts.layout')
@section('content')
<section class="padd-20">
    <div class="container">
        <!-- breadcums -->
        <div class="breadcums mb-0 wow fadeInUp">
            <ul class="reset">
                <li><a href="{{route('frontend.index')}}">{{__('Home')}}</a></li>
                <li><span>{{__('Favourite Restaurants')}}</span></li>
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
                        <h2 class="account-title wow fadeInUp">{{__('Favourite Restaurants')}}</h2>
                            <div class="row-1">
                                <ul class="fav_lists wow fadeInUp reset">
                        <!-- li loop -->
                        @foreach($wishListDetails as $key => $value)
                            <li>
                            <!-- box -->
                                <div class="box wow fadeInUp">
                                <a href="{{route('frontend.branch.show',[$value->branch_slug])}}" class="img bg_style" style="background-image:url({{$value->branch_logo}});"></a>
                                
                                <h4><a href="{{route('frontend.branch.show',[$value->branch_slug])}}">{{$value->branch_name}}</a></h4>
                                <p class="text-overflow">{{$value->cuisines}}</p>
                                <div class="star full_row ">
                                    <form>
                                        <span class="star-rating view_only">
                                       @for($i = 1; $i <= 5; $i++ )
                                            <input id="star-{{$value->branch_key.$i}}" type="checkbox" {{ (round($value->branch_avg_rating) == $i) ? 'checked="true"' : '' }} name="star">
                                            <label class="star" for="star-{{$value->branch_key.$i}}" ></label> 
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
                                </div>
                                    <button class="fav added wishlist_heart" value={{$value->branch_key}}><i class="material-icons">favorite</i></button>
                                </div>
                                <!-- box -->
                            </li>
                            @endforeach
                            <!-- li loop -->
                                 <!-- li loop -->
                          {{--  <li>
                            <!-- box -->
                                <div class="box wow fadeInUp">
                                <a href="{{route('frontend.detail')}}" class="img bg_style" style="background-image:url({{ asset(FRONT_END_BASE_PATH.'img/list-2.png')}});"></a>
                                <h4><a href="{{route('frontend.detail')}}">Dajajio</a></h4>
                                <p>Burgers, Softserve, salads,</p>
                                <div class="star full_row">
                                    <form>
                                        <span class="star-rating view_only">
                                        <input id="star-5" type="radio" name="star">
                                        <label class="star" for="star-5"></label>
                                        <input id="star-4" type="radio" checked="" name="star">
                                        <label class="star" for="star-4"></label>
                                        <input id="star-3" type="radio" name="star">
                                        <label class="star" for="star-3"></label>
                                        <input id="star-2" type="radio" name="star">
                                        <label class="star" for="star-2"></label>
                                        <input id="star-1" type="radio" name="star">
                                        <label class="star" for="star-1"></label>
                                </span>
                                    </form>                                    
                                </div>
                                    <button class="fav added"><i class="material-icons">favorite</i></button>
                                </div>
                                <!-- box -->
                            </li>
                            <!-- li loop -->
                                 <!-- li loop -->
                            <li>
                            <!-- box -->
                                <div class="box wow fadeInUp">
                                <a href="{{route('frontend.detail')}}" class="img bg_style" style="background-image:url({{ asset(FRONT_END_BASE_PATH.'img/list-3.png')}});"></a>
                                <h4><a href="{{route('frontend.detail')}}">Marash</a></h4>
                                <p>Burgers, Softserve, salads,</p>
                                <div class="star full_row">
                                    <form>
                                        <span class="star-rating view_only">
                                        <input id="star-5" type="radio" name="star">
                                        <label class="star" for="star-5"></label>
                                        <input id="star-4" type="radio" checked="" name="star">
                                        <label class="star" for="star-4"></label>
                                        <input id="star-3" type="radio" name="star">
                                        <label class="star" for="star-3"></label>
                                        <input id="star-2" type="radio" name="star">
                                        <label class="star" for="star-2"></label>
                                        <input id="star-1" type="radio" name="star">
                                        <label class="star" for="star-1"></label>
                                </span>
                                    </form>                                    
                                </div>
                                <button class="fav added"><i class="material-icons">favorite</i></button>
                                </div>
                                <!-- box -->
                            </li>
                            <!-- li loop -->
                                 <!-- li loop -->
                            <li>
                            <!-- box -->
                                <div class="box wow fadeInUp">
                                <a href="{{route('frontend.detail')}}" class="img bg_style" style="background-image:url({{ asset(FRONT_END_BASE_PATH.'img/list-4.png')}});"></a>
                                <h4><a href="{{route('frontend.detail')}}">Jasmi's Coffee</a></h4>
                                <p>Burgers, Softserve, salads,</p>
                                <div class="star full_row">
                                    <form>
                                        <span class="star-rating view_only">
                                        <input id="star-5" type="radio" name="star">
                                        <label class="star" for="star-5"></label>
                                        <input id="star-4" type="radio" checked="" name="star">
                                        <label class="star" for="star-4"></label>
                                        <input id="star-3" type="radio" name="star">
                                        <label class="star" for="star-3"></label>
                                        <input id="star-2" type="radio" name="star">
                                        <label class="star" for="star-2"></label>
                                        <input id="star-1" type="radio" name="star">
                                        <label class="star" for="star-1"></label>
                                </span>
                                    </form>                                    
                                </div>
                                <button class="fav added"><i class="material-icons">favorite</i></button>
                                </div>
                                <!-- box -->
                            </li>
                            <!-- li loop -->
                                 <!-- li loop -->
                            <li>
                            <!-- box -->
                                <div class="box wow fadeInUp">
                                <a href="{{route('frontend.detail')}}" class="img bg_style" style="background-image:url({{ asset(FRONT_END_BASE_PATH.'img/list-5.png')}});"></a>
                                <h4><a href="{{route('frontend.detail')}}">Le Chocolat</a></h4>
                                <p>Burgers, Softserve, salads,</p>
                                <div class="star full_row">
                                    <form>
                                        <span class="star-rating view_only">
                                        <input id="star-5" type="radio" name="star">
                                        <label class="star" for="star-5"></label>
                                        <input id="star-4" type="radio" checked="" name="star">
                                        <label class="star" for="star-4"></label>
                                        <input id="star-3" type="radio" name="star">
                                        <label class="star" for="star-3"></label>
                                        <input id="star-2" type="radio" name="star">
                                        <label class="star" for="star-2"></label>
                                        <input id="star-1" type="radio" name="star">
                                        <label class="star" for="star-1"></label>
                                </span>
                                    </form>                                    
                                </div>
                                <button class="fav added"><i class="material-icons">favorite</i></button>
                                </div>
                                <!-- box -->
                            </li>
                            <!-- li loop -->
                                 <!-- li loop -->
                            <li>
                            <!-- box -->
                                <div class="box wow fadeInUp">
                                <a href="{{route('frontend.detail')}}" class="img bg_style" style="background-image:url({{ asset(FRONT_END_BASE_PATH.'img/list-6.png')}});"></a>
                                <h4><a href="{{route('frontend.detail')}}">Wood Pizza Pasta</a></h4>
                                <p>Burgers, Softserve, salads,</p>
                                <div class="star full_row">
                                    <form>
                                        <span class="star-rating view_only">
                                        <input id="star-5" type="radio" name="star">
                                        <label class="star" for="star-5"></label>
                                        <input id="star-4" type="radio" checked="" name="star">
                                        <label class="star" for="star-4"></label>
                                        <input id="star-3" type="radio" name="star">
                                        <label class="star" for="star-3"></label>
                                        <input id="star-2" type="radio" name="star">
                                        <label class="star" for="star-2"></label>
                                        <input id="star-1" type="radio" name="star">
                                        <label class="star" for="star-1"></label>
                                </span>
                                    </form>                                    
                                </div>
                                <button class="fav added"><i class="material-icons">favorite</i></button>
                                </div>
                                <!-- box -->
                            </li>
                            <!-- li loop -->
                                 <!-- li loop -->
                            <li>
                            <!-- box -->
                                <div class="box wow fadeInUp">
                                <a href="{{route('frontend.detail')}}" class="img bg_style" style="background-image:url({{ asset(FRONT_END_BASE_PATH.'img/list-7.png')}});"></a>
                                <h4><a href="{{route('frontend.detail')}}">Tony Roma's</a></h4>
                                <p>Burgers, Softserve, salads,</p>
                                <div class="star full_row">
                                    <form>
                                        <span class="star-rating view_only">
                                        <input id="star-5" type="radio" name="star">
                                        <label class="star" for="star-5"></label>
                                        <input id="star-4" type="radio" checked="" name="star">
                                        <label class="star" for="star-4"></label>
                                        <input id="star-3" type="radio" name="star">
                                        <label class="star" for="star-3"></label>
                                        <input id="star-2" type="radio" name="star">
                                        <label class="star" for="star-2"></label>
                                        <input id="star-1" type="radio" name="star">
                                        <label class="star" for="star-1"></label>
                                </span>
                                    </form>                                    
                                </div>
                                <button class="fav added"><i class="material-icons">favorite</i></button>
                                </div>
                                <!-- box -->
                            </li> --}}
                            <!-- li loop -->
                        </ul>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </section>
<script>
$(document).ready(function()
{    
    $('.wishlist_heart').on('click',function (e) {
        e.preventDefault();
        var branchKey = $(this).val();
        var ths = $(this);
        $.ajax({ 
            url: "{{route('frontend.wishlist')}}",
            type: "PUT",
            data: { branch_key : branchKey },
            success: function(result) {
                if(result.status == HTTP_SUCCESS ){
                    successNotify(result.message);                    
                    ths.closest('li').remove();
                }else{
                    var message = result.message;
                    errorNotify(message.replace(",","<br/>"));
                }
            }
        });  
    });
})
</script>
@endsection

                 

        

   



   