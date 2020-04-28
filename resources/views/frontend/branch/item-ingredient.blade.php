<div class="modal-header text-center">
    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
    <h5 class="modal-title">{{__('Add Item Choices')}}</h5>
</div>
<div class="modal-body">

    <!-- item information -->

    <div class="item-information">
        <div class="img-menu bg_style" style="background-image:url({{$itemDetails->item_image}});"></div>
        <h4>{{$itemDetails->item_name}}</h4>
        <p>{{$itemDetails->item_description}}</p>
    
          <div class="price-menu add_modal">
            <span class="quantity">
        <button class="min quantity_min" hasIngredient="1" branchKey="{{$itemDetails->branch_key}}" itemKey="{{$itemDetails->item_key}}" action="minus"><i class="material-icons">remove</i></button>
            <input type="text" class="quantity_text" readonly value="1">
        <button class="max quantity_max" hasIngredient="1" branchKey="{{$itemDetails->branch_key}}" itemKey="{{$itemDetails->item_key}}" action="plus"><i class="material-icons">add</i></button>
    </span>
    @if($itemDetails->offer_enable === true)
            <p class="price"> {{--<strike> {{ $itemDetails->item_price }} </strike> --}}</p>
            @if($itemDetails->offer_value < $itemDetails->item_price)
                <p class="price flat_price"  flatPrice="{{$itemDetails->flat_offer_price}}" value=""> {{ $itemDetails->offer_price }} </p>
                <input type="hidden" value="{{$itemDetails->flat_offer_price}}" id='item-price'>
            @else
                <p class="price flat_price"  flatPrice="{{$itemDetails->flat_item_price}}" value=""> {{ $itemDetails->item_price }} </p>
                <input type="hidden" value="{{$itemDetails->flat_item_price}}" id='item-price'>
            @endif
        @else 
            <p class="price flat_price"  flatPrice="{{$itemDetails->flat_item_price}}" value=""> {{ $itemDetails->item_price }} </p>
            <input type="hidden" value="{{$itemDetails->flat_item_price}}" id='item-price'>
        @endif  
</div>
              
    </div>

    <!-- item information -->

    <div class="group-item">

        @foreach($itemDetails->ingrdient_groups as $gkey => $group) 
        <!-- fullrow -->
        <div class="full_row">
            <!-- box -->
            <div class="box">
                <h4>{{$group->ingredient_group_name}} <span>(Choose from min {{$group->minimum}} upto {{$group->maximum}} items) </span> </h4>
            </div>
            <!-- box -->
            <!-- ul -->
            <div class="row-1 ingredient_groups" id="{{$group->ingredient_group_key}}" minimum="{{ $group->minimum }}" maximum="{{ $group->maximum }}">
                <ul class="reset">
                    @foreach($group->ingredients as $ikey => $ingredient)
                        <li>
                            <input type="checkbox" groupKey="{{$group->ingredient_group_key}}" ingredientKey="{{$ingredient->ingredient_key}}" id="{{$group->ingredient_group_id.$ingredient->ingredient_key}}" ingredientFlatPrice="{{$ingredient->flat_ingredient_price}}" class="checkbox {{$group->ingredient_group_key}} ingredients">
                            <label for="{{$group->ingredient_group_id.$ingredient->ingredient_key}}" class="checkbox"> {{$ingredient->ingredient_name}} 
                                <span class="price" ingredientFlatPrice="{{$ingredient->flat_ingredient_price}}">{{$ingredient->price}}</span>
                            </label>
                        </li>
                    @endforeach

                    {{-- <li>
                        <input type="checkbox" id="ing2" class="checkbox">
                        <label for="ing2" class="checkbox"> Red Chilli Sauce <span class="price">BD
                                0.600</span></label>
                    </li>
                    <li>
                        <input type="checkbox" id="ing3" class="checkbox">
                        <label for="ing3" class="checkbox"> Barbeque Sauce <span class="price">BD 0.600</span></label>
                    </li>
                    <li>
                        <input type="checkbox" id="ing4" class="checkbox">
                        <label for="ing4" class="checkbox"> Sweet Onion Sauce <span class="price">BD
                                0.600</span></label>
                    </li>
                    <li>
                        <input type="checkbox" id="ing5" class="checkbox">
                        <label for="ing5" class="checkbox"> South West Sauce <span class="price">BD
                                0.600</span></label>
                    </li> --}}
                </ul>
            </div>
            <!-- ul -->
            <span style="color:chocolate;" id="error{{$group->ingredient_group_key}}"></span>
        </div>
        <!-- row -->
        @endforeach


        {{-- <!-- fullrow -->
        <div class="full_row">
            <!-- box -->
            <div class="box">
                <h4>Choice of Vegetables <span>(Choose upto 3 items) </span></h4>
            </div>
            <!-- box -->
            <!-- ul -->
            <div class="row-1">
                <ul class="reset">
                    <li>
                        <input type="checkbox" id="ing6" class="checkbox">
                        <label for="ing6" class="checkbox"> Harissa Sauce <span class="price">BD 0.600</span></label>
                    </li>
                    <li>
                        <input type="checkbox" id="ing7" class="checkbox">
                        <label for="ing7" class="checkbox"> Red Chilli Sauce <span class="price">BD
                                0.600</span></label>
                    </li>
                    <li>
                        <input type="checkbox" id="ing8" class="checkbox">
                        <label for="ing8" class="checkbox"> Barbeque Sauce <span class="price">BD 0.600</span></label>
                    </li>
                    <li>
                        <input type="checkbox" id="ing9" class="checkbox">
                        <label for="ing9" class="checkbox"> Sweet Onion Sauce <span class="price">BD
                                0.600</span></label>
                    </li>
                    <li>
                        <input type="checkbox" id="ing10" class="checkbox">
                        <label for="ing10" class="checkbox"> South West Sauce <span class="price">BD
                                0.600</span></label>
                    </li>
                </ul>
            </div>
            <!-- ul -->
        </div>
        <!-- row -->
        <div class="full_row mt-15">
            <div class="form-group">
                <textarea class="form-control" placeholder="Instructions"></textarea>
            </div>
        </div>
        <!-- row --> --}}
            
                <div class="full_row mt-15">
                    <div class="form-group">
                        {{--<textarea class="form-control" name="" placeholder="Instructions"></textarea>--}}
                        {{Form::textarea('item_instruction', '', ['class' => 'form-control','id' => $itemDetails->item_key.'-item_instruction','placeholder' => 'Instructions'])}}
                    </div>
                </div>

    </div>
</div>
<div class="modal-footer item_quantity">
  
    <button class="shape-btn loader shape1" hasIngredient="1"  branchKey="{{$itemDetails->branch_key}}" itemKey="{{$itemDetails->item_key}}" id="{{ auth()->guard(GUARD_USER)->check() ? 'add_to_cart' : '' }}"><span class="shape">{{__('Add to Cart')}}</span></button>
</div>
{{-- <script>
    $('document').ready(function() {
        $('body').on('click','.quantity_max, .quantity_min',function() {
            alert('hai');
            // var quantity = parseInt($(this).closest('.price-menu').find('.quantity_text').val());
            var quantity = parseInt($('.add_modal .quantity_text').val());
            var itemPrice =  $('.flat_price').attr('flatPrice');
            // var itemPrice =  $('#item_price').text();
            // var itemTotal = (quantity)*(itemPrice);
            // alert(itemTotal);
            $('.flat_price').html((quantity)*(itemPrice));
        })

    });
    </script> --}}