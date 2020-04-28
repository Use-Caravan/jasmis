
<div class="box-head">
    <h3>{{__('Cart')}}</h3>
    @if($cartDetails !== null && !empty($cartDetails))
        <p>{{$cartDetails->total->cart_total}} {{__('Items')}}</p>
    @endif
    <span class="close-cart-toggle"><i class="material-icons">arrow_back</i></span>
</div>  
@if($cartDetails !== null && !empty($cartDetails))
    @foreach($cartDetails->items as $key => $value)
    <div class="box-body">
        <table>
            <tbody>
                <div class="price-menu">
                <tr>
                    <td class="title">{{$value->item_name}}</td>
                    <td><span class="quantity">
                        <button class="min {{ auth()->guard(GUARD_USER)->check() ? 'quantity_minimum' : 'loginModel' }}" branchKey="{{$branchDetails->branch_key}}" cartItemKey="{{$value->cart_item_key}}" itemKey="{{$value->item_key}}"  action="minus"><i class="material-icons">remove</i></button>
                            <input type="text" class="quantity_text" readonly value="{{$value->quanity}}">
                        <button class="max {{ auth()->guard(GUARD_USER)->check() ? 'quantity_maximum' : 'loginModel' }}" branchKey="{{$branchDetails->branch_key}}" cartItemKey="{{$value->cart_item_key}}" itemKey="{{$value->item_key}}" action="plus"><i class="material-icons">add</i></button>
                        </span>
                    </td>
                    <td class="text-right">{{$value->subtotal}}</td>           
                </tr>
                </div>
                <tr>
                    <td colspan="3" class="description">{{$value->ingredient_name}} </td>
                </tr>
                    {{--    <td class="title">Big "J" </td>
                        <td><span class="quantity"> <button class="min"><i class="material-icons">remove</i></button>
                            <input type="text" value="1"><button class="max"><i class="material-icons">add</i></button></span></td>
                        <td class="text-right">3.300</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="description">Red Chilli Sauce, Cucumber </td>
                    </tr> --}}
            </tbody>
        </table>
    </div>
    @endforeach
    <div class="box-footer">
        <table>
            <tbody>                    
                @foreach($cartDetails->payment_details as $key => $value)
                <tr class="{{ ($value->is_bold == 1) ? 'sub' : '' }}">
                    <td>{{$value->name}}</td>
                    <td class="text-right">{{$value->price}}</td>
                </tr>
                @endforeach
                {{-- <tr>
                    <td>Delivery fee</td>
                    <td class="text-right">0.800</td>
                </tr>

                <tr>
                    <td>VAT(5%)</td>
                    <td class="text-right">0.500</td>
                </tr> --}}                
                <tr class="sub total">
                    <td>{{$cartDetails->total->name}}</td>
                    <td class="text-right">{{$cartDetails->total->price}}</td>
                </tr>
            </tbody>
        </table>            
        <div class="full_row text-right mt-3 mb-2">
            <a class="shape-btn loader shape1"  href="{{ route('frontend.checkout',['branch_slug' =>$branchDetails->branch_slug ]) }}"><span class="shape">{{__('Checkout')}}</span></a>
        </div>            
    </div>

@else
    <p>{{__('Your cart is empty')}}</p>
@endif

