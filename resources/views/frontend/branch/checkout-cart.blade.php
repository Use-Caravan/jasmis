@foreach($cartItem->items as $key => $value) 
<tr>
    <td class="name">
        <div class="min-height">
            <span class="img bg_style" style="background-image:url({{$value->item_image}});"></span>
            <h4>{{$value->item_name}}</h4>
            <p>{{$value->ingredient_name}}</p>
        </div>
    </td>
    <td class="qt">
        <span class="quantity">
            <button class="min {{ auth()->guard(GUARD_USER)->check() ? 'quantity_minimum' : 'loginModel' }}" branchKey="{{$branchDetails->branch_key}}" cartItemKey="{{$value->cart_item_key}}" itemKey="{{$value->item_key}}"  action="minus"><i class="material-icons">remove</i></button>
                <input type="text" class="quantity_text" readonly value="{{$value->quanity}}">
            <button class="max {{ auth()->guard(GUARD_USER)->check() ? 'quantity_maximum' : 'loginModel' }}" branchKey="{{$branchDetails->branch_key}}" cartItemKey="{{$value->cart_item_key}}" itemKey="{{$value->item_key}}" action="plus"><i class="material-icons">add</i></button>
        </span>

    </td>
    <td class="price text-right">
        
    </td>
    <td class="rm"><a href="javascript:" class="remove-btn" onclick="updatecartQuantity('{{$value->cart_item_key}}', 0)"><i class="fa fa-times-circle" aria-hidden="true"></i></a></td> 
</tr>
@endforeach

{{-- <tr>
    <td class="name">
        <div class="min-height">
            <span class="img bg_style" style="background-image:url({{ asset(FRONT_END_BASE_PATH.'img/menu1.png')}});"></span>
            <h4>Taco Raco</h4>
            <p>Harissa Sauce, Onions</p>
        </div>
    </td>
    <td class="qt">
        <span class="quantity"=>
    <button class="min"><i class="material-icons">remove</i></button>
        <input type="text" value="2">
        <button class="max"><i class="material-icons">add</i></button>
        </span>
    </td>
    <td class="price text-right">
        BD 3.300
    </td>
    <td class="rm"><a href="" class="remove-btn"><i class="fa fa-times-circle" aria-hidden="true"></i></a></td>
</tr> --}}
