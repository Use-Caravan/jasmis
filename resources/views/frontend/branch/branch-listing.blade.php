@extends('frontend.layouts.layout')
@section('content')

 <!-- listing restaurant -->

    <section class="listing-restaurant">
        <div class="container">

            <!-- breadcums -->
            <div class="breadcums wow fadeInUp">
                <ul class="reset">
                    <li><a href="{{route('frontend.index')}}">{{__('Home')}}</a></li>
                    <li><span>{{__('Restaurants')}}</span></li>
                </ul>
            </div>
            <!-- breadcums -->

            @if($bannerImage != null) 
            <!-- advertisement Banner -->            
            <div class="advertisement-slider wow fadeInUp owl-carousel">
               @foreach($bannerImage as $key => $value)
                <div class="slide">
                    @if(preg_match('/http/',$value['redirect_url']) || preg_match('/https/',$value['redirect_url']))
                     <a href="{{$value['redirect_url']}}" target="_blank" />
                    @else
                     <a href="http://{{$value['redirect_url']}}" target="_blank" /> 
                    @endif       
                        {!! Html::image( FileHelper::loadImage($value['banner_file']),$value['banner_name']); !!}   
                        </a>
                </div>
                @endforeach
                {{--<div class="slide">
                    <a href="#">
                        <img src="{{ asset(FRONT_END_BASE_PATH.'img/caravan-banner.png') }}">
                    </a>
                </div> --}}
            </div>            
            <!-- advertisement Banner -->
            @endif

            <!-- search and filter -->
            {{ Form::open(['route' => 'frontend.branch.index','id' => 'branch-search-form', 'class' => 'form-horizontal', 'method' => 'GET']) }}
            <!-- search filter overlay -->
            <div class="filter-overlay"></div>
            <div class="search-and-filter wow fadeInUp">
                <div class="d-table w100">                    
                    {{ Form::hidden('latitude',request()->latitude) }}
                    {{ Form::hidden('longitude',request()->longitude) }}
                    {{ Form::hidden('location',request()->location) }}
                    {{ Form::hidden('order_type',request()->order_type) }}
                    {{ Form::hidden('cuisine',request()->cuisine)  }}
                    <div class="d-table-cell">
                        <div class="search-form" id = "listing">
                            <i class="fa fa-search flyo"></i>
                            <input type="text" name="branch_name" value="{{request()->branch_name}}" class="form-control listing" id="branch_name" placeholder="{{__('Search')."..."}}">
                            <button type="button" id="filter-toggle"> <i class="fa fa-sliders" aria-hidden="true"></i> {{__('Filter')}} <i class="fa fa-angle-down"></i></button>
                        </div>
                    </div>
                
                    <div class="d-table-cell text-right">
                        <div class="p f18"><i class="fa fa-map-marker" aria-hidden="true"></i> {{request()->location}} <a href="{{ route('frontend.index')}}">{{__('Change')}}</a></div>
                    </div>
                </div>

                <!-- search sidebar -->
                                
                <div class="dd-menu">

                    <div class="filter-drop cuisine-filter">

                        <div class="full_row ">
                            <h4>{{__('Sort by')}}</h4>

                            <input type="checkbox" name="orderby_popularity" class="checkbox order_by_check" value= "desc" id="f1" {{ request()->orderby_popularity !== null ? 'checked' : '' }}>
                            <label class="checkbox" for="f1">{{__('Popularity')}}</label>

                            <input type="checkbox" name="orderby_rating" class="checkbox order_by_check" value = "desc" id="f2" {{ request()->orderby_rating !== null ? 'checked' : '' }}>
                            <label class="checkbox" for="f2">{{__('Rating')}}</label>

                            <input type="checkbox" name="orderby_min_order_value" class="checkbox order_by_check" value = "asc" id="f3" {{ request()->orderby_min_order_value !== null ? 'checked' : '' }}>
                            <label class="checkbox" for="f3">{{__('Min.order')}}</label>

                            <input type="checkbox" name="orderby_delivery_time" class="checkbox order_by_check" value = "asc" id="f4" {{ request()->orderby_delivery_time !== null ? 'checked' : '' }}>
                            <label class="checkbox" for="f4">{{__('Del.time')}}</label>

                        </div>

                        <div class="full_row">
                            <h4>{{('Cuisine Type')}}</h4>
                            @php $cuisines = explode(',', (request()->cuisine === null) ? '' : request()->cuisine ) @endphp
                            @foreach($cuisineList as $key => $value)
                            <input type="checkbox" value ="{{$value['cuisine_id']}}" data="{{$value['cuisine_name']}}" class="checkbox cuisines" id="c{{$value['cuisine_id']}}"  {{ in_array($value['cuisine_id'],$cuisines) ? 'checked' : '' }}>
                            <label class="checkbox" for="c{{$value['cuisine_id']}}">{{$value['cuisine_name']}}</label> 
                            {{-- {{ Form::checkbox('cuisine_name',$value['cuisine_name'],null,[ 'id' => 'cuisine', "class" => "checkbox" ]) }}
                            {{ Form::label("", $value['cuisine_name'], ['class' => 'checkbox' ]) }} --}}
                            @endforeach
                            {{-- <input type="checkbox" class="checkbox" id="c1">
                            <label class="checkbox" for="c1">Italian </label>
                            

                            <input type="checkbox" class="checkbox" id="c2">
                            <label class="checkbox" for="c2">Indian</label>

                            <input type="checkbox" class="checkbox" id="c3">
                            <label class="checkbox" for="c3">American</label>

                            <input type="checkbox" class="checkbox" id="c4">
                            <label class="checkbox" for="c4">Burger</label>

                            <input type="checkbox" class="checkbox" id="c5">
                            <label class="checkbox" for="c5">Japanese </label>

                            <input type="checkbox" class="checkbox" id="c6">
                            <label class="checkbox" for="c6">Oriental</label>

                            <input type="checkbox" class="checkbox" id="c7">
                            <label class="checkbox" for="c7">Chinese</label>

                            <input type="checkbox" class="checkbox" id="c8">
                            <label class="checkbox" for="c8">Thai</label> --}}

                        </div>

                        <div class="full_row last text-right">
                           {{-- <button type="button" class="shape-btn loader shape1"><span class="shape">Done</span></button> --}}
                           {!! Html::decode( Form::button('<span class="shape">Done</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ) !!}
                        </div>

                    </div>
                    
                </div>

            </div>
            {{form::close()}}
            <!-- search and filter -->

            <!-- tags -->

            <div class="row mb-4">
                <div class="col-md-12">
                    <span id="select-cuisines">
                        @foreach($checkedCuisines as $key => $value)                        
                        <button class="tags wow zoomIn remove-cuisine" style="visibility: visible; animation-name: zoomIn;" cuisine_id={{$value->cuisine_id}}>{{$value->cuisine_name}}<span>×</span> </button>
                        @endforeach
                       {{-- <button class="tags wow zoomIn" style="visibility: visible; animation-name: zoomIn;"> Chinese <span>×</span> </button>
                        <button class="tags wow zoomIn" style="visibility: visible; animation-name: zoomIn;"> Arabian <span>×</span> </button> --}}  
                    </span>              
                    <a href="javascript:" class="cuisine-clear clearall wow zoomIn" style="visibility : visible;animation-name: zoomIn;{{ (request()->cuisine === null) ? 'display:none;' : '' }}">Clear All</a>                    
                </div>                                        
            </div>
            
            <!-- tags -->
            
            <div class="listing-restaurants" id="branch_lising_search">
                    
                @include('frontend.branch.search-branch')

            </div>
            @if(false)
            /* For future update */
            <div class="corporate_rest">
                <h2 class="heading-1">Restaurants</h2>
               

                <div class="row">
                <ul class="rest_ul">
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r1.png) #EA362F no-repeat center center"></a>
                        <span>Jasmi's</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/list-2.png) #FFFFFF no-repeat center center"></a>
                        <span>Dajajio</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r3.png) #522A14 no-repeat center center"></a>
                        <span>Marash</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r4.png) #8DC242 no-repeat center center"></a>
                        <span>Jasmi's Coffee</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r5.png) #295133 no-repeat center center"></a>
                        <span>Le Chocolat</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r6.png) #C3342F no-repeat center center"></a>
                        <span>Wood Pizza Pasta</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r7.png) #303838 no-repeat center center"></a>
                        <span>Tony Roma's</span>
                    </li>
                </ul>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- listing restaurant -->

    <!-- restaurant popup -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {     
    $('#branch-search-form input[name="branch_name"]').on('keyup',function(){
        var action = $('#branch-search-form').attr('action');
        var branch_name = $('#branch_name').val();
        if(branch_name.length <= 3 && branch_name.length > 0) {
            return ;
        }
        var data = $('#branch-search-form').serializeArray();
        $.ajax({
            type : 'get',
            url : action,            
            data: data,
            beforeSend:function() {
            },              
            success:function(result){
                $('#branch_lising_search').html(result.list);                
                changeUrl();
            }
        });
    }); 
    
    $('.order_by_check').on('change', function() {
        $('.order_by_check').not(this).prop('checked', false);  
    });

    $('#branch-search-form').on('submit',function(e){
        e.preventDefault();
        var action = $(this).attr('action');
        
        var cuisine = '';        
        $('#select-cuisines button').remove();
        $('#branch-search-form .cuisines').each(function() {
           if($(this).prop('checked') == true) {
                var cuisineName = $(this).attr('data');
                var cuisineId = $(this).val();
                cuisine += ","+$(this).val();       
                $('#select-cuisines').append(`<button class="tags wow zoomIn remove-cuisine" style="visibility: visible; animation-name: zoomIn;" cuisine_id=`+cuisineId+`>`+cuisineName+`<span>×</span> </button>`);
            }                        
        });        
        cuisine = cuisine.substring(1);
        $('#branch-search-form input[name="cuisine"]').val(cuisine);
        if($('#select-cuisines button').length > 0) {
            $('.cuisine-clear').show();
        }
        var data = $('#branch-search-form').serializeArray();       
        $.ajax({
            type : 'get',
            url : action,            
            data: data,
            success:function(result){
                $('#branch_lising_search').html(result.list); 
                $(".search-and-filter").toggleClass("open");
                $(".filter-overlay").toggleClass("open");
                changeUrl();
            }
        });        
    });
    $('.cuisine-clear').on('click',function() {
        $('#select-cuisines button').remove();
        $('.cuisines').prop('checked',false);
        $('.cuisine-clear').hide();            
    });
    
    $('body').on('click','.remove-cuisine',function() {
        $(this).remove();
        //var cuisineLength = $(this).val();
        var cuisineId = $(this).attr('cuisine_id');
        $('#c'+cuisineId).prop('checked',false);
    });
    $('.wishlist_heart').click(function()
    {
        var ths = $(this);
        var isWishlist = ths.data('wishlist');
        var type = (isWishlist == 1) ?  'PUT' : 'POST';        
        $.ajax({
            url: "{{ route('frontend.wishlist') }}",
            type: type,
            data : { branch_key: $(this).val()},
            success: function(result) {
                if(result.status == HTTP_SUCCESS) {
                    successNotify(result.message);                                        
                    if(isWishlist == 1){
                        ths.removeClass('added');
                        ths.data('wishlist',0);
                    } else {                        
                        ths.addClass('added');
                        ths.data('wishlist',1);
                    }
                }else{
                    var message = result.message;
                    errorNotify(result.message.replace(",","<br/>"));
                }
            }
        });
    });        
});
function changeUrl() {
    var action = $('#branch-search-form').attr('action');
    var urlPush = action+"?";
    var data = $('#branch-search-form').serializeArray();
    $(data).each(function(index, value ) {
        urlPush +=value['name']+"="+value['value']+"&";
    });
    window.history.pushState("object or string", "Title", urlPush);
}

</script>    
@endsection
  