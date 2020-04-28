<!-- Full row Start -->
<div class="full_row">
    <!-- side categories end -->
    <div class="side-categories wow fadeInUp">
        <h4>{{__('Categories')}}</h4>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#all_items{{ $branchDetails->branch_key}}" role="tab">{{__('All')}}</a>
            </li>
            @foreach($itemDetails as $key => $value)
                @if($value->items != null) 
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#a_{{ $value->category_key }}" data-categorykey="{{ $value->category_key }}" data_category_id="{{ $value->category_id }}" id="category_id" role="tab">{{$value->category_name}}</a>
                    </li>    
                @endif
            @endforeach
            {{-- 
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#chicken" role="tab">Chicken
                    value meals</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#desserts" role="tab">Desserts</a>
            </li>
                --}}
        </ul>
        <!-- Nav tabs -->
    </div>
    <!-- side categories end -->
    <!-- main menu start -->
    <div class="main-menu wow fadeInUp">

        <div class="item-search full_row">
            <label class="icons"><i class="fa fa-search"></i></label>
            <input type="text" placeholder=@lang("Search Menu Items") id = "item-search">
        </div>
        <!-- item container -->
        <div class="item-container">
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="all_items{{ $branchDetails->branch_key}}" role="tabpanel">
                    <div class="row" id="item_search">
                        @foreach($itemDetails as $key => $value)
                            @if($value->items !== null)
                                @foreach($value->items as $iKey => $iValue)
                                    {!! view('frontend.branch.item')->with('iValue',$iValue)->with('branchDetails',$branchDetails)->render() !!}
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div> 
                @if($itemDetails != null)
                @foreach($itemDetails as $key => $value)
                @if($value->items !== null)
                    <div class="tab-pane" id="a_{{ $value->category_key }}" role="tabpanel">
                        <h3 class="m-title">{{$value->category_name}}</h3>
                        <div class="row" id="html_{{ $value->category_key }}">
                            @foreach($value->items as $iKey => $iValue)
                                {!! view('frontend.branch.item')->with('iValue',$iValue)->with('branchDetails',$branchDetails)->render() !!}
                            @endforeach
                        </div>
                    </div>
                @endif
                @endforeach
                @else
                <span> <h2 class="heading-1">{{__('No Items Found.')}}<h2></span>
                @endif
            </div>
            <!-- Tab panes -->
        </div>
        <!-- item container -->
    </div>
    <!-- main menu start -->
</div>
<!-- Full row end -->



<!-- ingridents modal -->
<div class="modal ing_modal_ui fade" id="ing_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="IngredientContent">

            {{-- Ingredient code will come from Ajax --}}

        </div>
    </div>
</div>
<!-- ingridents modal -->

<!-- Cart clear info model -->
<div class="modal cart_clear_confirm_modal fade" id="cart_clear_confirm_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                <h5 class="modal-title">{{__('Notification Alert!')}}</h5>
                <div class="icons-add"><img src="{{ asset(FRONT_END_BASE_PATH.'img/icon3.png') }}"></div>
            </div>
            <div class="modal-body">                    
                <div class="form-box floating_label">
                    <p id="cart_clear_confirm_message">You have some item in another branch. Are you sure you want to delete those items?</p>
                    <div class="text-right mb-5">                                                
                        <a href="javascript:" onclick="$('#cart_clear_confirm_modal').modal('hide');" class="shape-btn shape1"><span class="shape">Cancel</span></a>
                        {!! Html::decode( Form::button('<span class="shape">Ok</span>', ['type'=>'button', "id" => "cart_clear_confirm","updated-branchkey" => '', 'class' => 'shape-btn shape1']) ) !!}
                    </div>                        
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart clear info model -->

<script>
$('document').ready(function(){

    window.thiUI = '';
    $('body').on('click','.quantity_max, .quantity_min',function() {

        window.thiUI = $(this);
        var itemKey = $(this).attr('itemKey');
        var cartCount = $("#cartCountSpan").text();
        if($('#current_branch_key').attr('branch_key') != $('#cartIconLi').attr('branch-key') && cartCount != 0) {
            $('#cart_clear_confirm_modal').modal('toggle');
            $('#cart_clear_confirm').attr('updated-branchkey',$('#current_branch_key').attr('branch_key'));
            return ;
        }

        var currenct = parseInt($(this).closest('.price-menu').find('.quantity_text').val());
        var newVal = currenct; 
        if($(this).attr('action') == 'minus') {
            if(currenct > 0) {
                newVal = currenct - 1;
            }            
        }
        if($(this).attr('action') == 'plus') {
            newVal = currenct + 1; 
        }
        $(this).closest('.price-menu').find('.quantity_text').val(newVal);        

        var itemPrice =  $('.flat_price').attr('flatPrice');
        var ingredientPrice = 0;
        
        $('.full_row .ingredients:checked').each(function() {
            ingredientPrice = parseFloat(ingredientPrice) + parseFloat($(this).attr('ingredientFlatPrice'));
        });
        
        var total = parseFloat(ingredientPrice) + parseFloat(itemPrice);
        $('.flat_price').html( ((newVal)*(total)).toFixed(3) );
        $('#item-price').val( ((newVal)*(total)).toFixed(3) );
        
        if( $(this).attr('hasIngredient') == 0 || $(this).attr('hasIngredient') == undefined) { 
            userCart($(this),[],0);        
        }
    })
    $('body').on('click','.quantity_maximum, .quantity_minimum',function() {        

        var currenct = parseInt($(this).closest('.quantity').find('.quantity_text').val());        
        var newVal = currenct;
        if($(this).attr('action') == 'minus') {
            if(currenct > 0) {
                newVal = currenct - 1;
            }            
        }
        if($(this).attr('action') == 'plus') {
            newVal = currenct + 1; 
        }        
        $(this).closest('.quantity').find('.quantity_text').val(newVal);
        var cart_item_key = $(this).attr('cartitemkey');        
        var item_key = $(this).attr('itemKey');     
        updatecartQuantity(cart_item_key,newVal, item_key);
    });
    

    $('body').on('click','.addItemIngredient',function() {
        window.thiUI = $(this);
        var itemKey = $(this).attr('itemKey');
        var cartCount = $("#cartCountSpan").text();
        if($('#current_branch_key').attr('branch_key') != $('#cartIconLi').attr('branch-key') && cartCount != 0) {            
            $('#cart_clear_confirm_modal').modal('toggle');
            $('#cart_clear_confirm').attr('updated-branchkey',$('#current_branch_key').attr('branch_key'));
            return ;
        }
        
        $.ajax({ 
            url:  "{{ route('frontend.item.show',[''])}}/"+itemKey,
            type: 'GET',
            success: function(result) {
                $('#IngredientContent').html(result);
                $('#ing_modal').modal('toggle');
            }
        });
    }); 
    
    $('body').on('click','#cart_clear_confirm',function()
    {        
        $('#cart_clear_confirm_modal').modal('toggle');
        $('#cartIconLi').attr('branch-key',$(this).attr('updated-branchkey'));
        window.thiUI.click();
    });

    $('body').on('click','.ingredients',function() {
        var current = $(this);
        var groupKey = $(this).attr('groupKey');
        var minimum = $('#'+groupKey).attr('minimum');
        var maximum = $('#'+groupKey).attr('maximum');
        var ingredientCount = 0;            
        var quantity = parseInt($('.add_modal .quantity_text').val());
        var itemPrice =  $('.flat_price').attr('flatPrice');
        var ingredientPrice = $(this).attr('ingredientFlatPrice');
        var itemTotal = $('#item-price').val();
        $('#'+groupKey+' .ingredients').each(function() {                
            if($(this). prop("checked") == true) {
                ingredientCount++;                    
            }
        });
        var errorCount = 0;
        if(ingredientCount > maximum) {
            current.prop("checked",false);
            errorCount++;
            errorNotify("{{__('You have selected maximum ingredients')}}");
            return false;
        }   
        
        if(ingredientCount < minimum) {                
            errorCount++;
            errorNotify("{{ __('You have to choose atleast minimum ingredient')}}");
            return false;
        } 
        ingredientPrice = parseFloat(ingredientPrice) * parseInt(quantity);

        if(current.prop("checked") == true){
            var total = (parseFloat(ingredientPrice) + parseFloat(itemTotal)).toFixed(3);
        } else {
            var total = (parseFloat(itemTotal) - parseFloat(ingredientPrice)).toFixed(3);
        }        

        $('.flat_price').html(total);
        $('#item-price').val(total);
        if(errorCount == 0) {
            $('#error'+groupKey).html("");
        }
    });
    $('body').on('click','#add_to_cart',function() {

        var branchKey = $(this).attr('branchKey');
        
        var totalErrorCount = 0;              
        var ingredient_groups = [];
        $('.ingredient_groups').each(function() {

            var currentEle = $(this);
            
            var groupKey = currentEle.attr('id');
            var minimum =  currentEle.attr('minimum');
            var maximum =  currentEle.attr('maximum');
            var ingredientCount = 0;

            var ingredient = [];

            $('#'+groupKey+' .ingredients').each(function() {
                if($(this).prop("checked") == true) {
                    ingredient.push({
                        "ingredient_key" : $(this).attr('ingredientKey'),
                        "quantity" : 1,
                    })
                    ingredientCount++;
                }
            });

            if(ingredient.length > 0) {
                ingredient_groups.push({
                    "ingredient_group_key" : groupKey,
                    "ingredients" : ingredient
                });
            }

            var errorCount = 0;
            if(ingredientCount > maximum) {
                $('#error'+groupKey).html("{{__('You have selected maximum ingredients')}}");
                errorCount++;
                totalErrorCount++;
            }
            if(ingredientCount < minimum) {
                errorCount++;
                totalErrorCount++;
                $('#error'+groupKey).html("{{__('You have to choose atleast minimum ingredient')}}");
            } 
            if(errorCount == 0) {
                $('#error'+groupKey).html("");
            }
        });
        if(totalErrorCount == 0){
            userCart($(this),ingredient_groups,1);
        }
    });
 
    $('#item-search').on('keyup',function(){
        var itemName = $('#item-search').val();
        var categoryId = $('.nav-item a.active').attr('data_category_id');
        var categoryKey = $('.nav-item a.active').data('categorykey');
        var branch_key = $('#current_branch_key').attr("branch_key");
        $.ajax({
            type : 'get',
            url : "{{route('frontend.item.index')}}",            
            data: {item_name : itemName,category_id : categoryId,webfilter : true,branch_key:branch_key},
            beforeSend:function() {
            },              
            success:function(result){
                if(categoryId == undefined || categoryId == null || categoryId == '') {
                    $('#item_search').html(result.itemHtml);
                } else {
                    $('#html_'+categoryKey).html(result.itemHtml);
                }
            }
        });
    });

});

function updatecartQuantity(cart_item_key, quantity,item_key)
{            
    $.ajax({ 
        url: "{{ route('frontend.cart-quantity-update')}}",
        type: 'post',        
        data : {cart_item_key:cart_item_key,quantity:quantity,branch_key:$('#current_branch_key').attr('branch_key')},
        success: function(result) {
            if(result.status == HTTP_SUCCESS ){
                successNotify(result.message);
                $('#cart_div').html(result.design);                                
                $("body input[name='"+item_key+"']").val(quantity);
                if(result.cart_quantity > 0) {
                    $('#cartIconLi').show();
                    $('#cartIconLi a').attr('href',result.checkout_url);
                    $('#cartIconLi').attr('branch-key',result.branch_key);
                    $('#cartCountSpan').html(result.cart_quantity);
                } else {
                    $('#cartCountSpan').html(0);
                }
            }else{
                var message = result.message;
                errorNotify(message.replace(",","<br/>"));
            }
        }
    });
}


function userCart(ths,ingredients = [],haveIngredient = 0) {

    var item_key = ths.attr('itemKey');
    var branch_key = ths.attr('branchKey');        
    if(haveIngredient === 1) {
        var quantity = ths.closest('#IngredientContent').find('.quantity_text').val();
    } else {
        var quantity = ths.closest('.item_quantity').find('.quantity_text').val();
    }
    

    if(quantity <= 0){
        errorNotify("{{__('Please choose atleast one quantity to add in to cart')}}");
        return ;
    }
    cartUpdate(branch_key, item_key, quantity, ingredients, haveIngredient)    
}
function cartUpdate(branch_key, item_key, quantity, ingrdient_groups = [], haveIngredient) {    


    var data = JSON.stringify({branch_key:branch_key,item_key:item_key,quantity:quantity,ingrdient_groups:ingrdient_groups,item_instruction:$('#'+item_key+'-item_instruction').val()});
    $.ajax({ 
        url: "{{ route('frontend.cart-update')}}",
        type: 'post',
        dataType: 'json',
        processData: false,
        contentType: 'application/json',
        data : data,
        success: function(result) {
            if(result.status == HTTP_SUCCESS ) {
                $('#cart_div').html(result.design);
                $("#"+item_key+" .quantity_text").val(quantity);
                if(haveIngredient == 1) {
                    $('#ing_modal').modal('toggle');
                }
                if(result.cart_quantity > 0) {
                    $('#cartIconLi').show();
                    $('#cartIconLi a').attr('href',result.checkout_url);
                    $('#cartIconLi').attr('branch-key',result.branch_key);
                    $('#cartCountSpan').html(result.cart_quantity);
                } else {
                    $('#cartCountSpan').html(0);
                }
                successNotify(result.message);
            } else {
                var message = result.message;
                errorNotify(message.replace(",","<br/>"));
            }
        }
    });
}
</script>