<div class="modal-body">

<!-- order box top -->
    <div class="view-order-box full_row">
        <h4 id ="branch_name">{{$response->data->branch_name}}</h4>
        <span>{{__('Order ID')}} : <p id = "order_id">{{$response->data->order_number}}</p></span>
        <p class="date" id = "order_date">{{$response->data->order_datetime}}</p>
        <div class="status">{{__('Status')}} : <span class="completed" id = "order_status"></span>{{$response->data->status}}</p></div>
    </div>
    <!-- order box top -->
        

    <div class="full_row cart-table">
        <div class="table-responsive">
            <table class="table" >
                <tbody id="item_details">
                    @foreach($response->data->items as $key => $value)
                    <tr>
                        <td class="name">
                            <div class="min-height">
                                <span class="img bg_style" style="background-image:url({{$value->item_image_path}});"></span>
                                <h4 id = "item_name">{{$value->item_name}}</h4>
                                <p>{{$value->ingredients}}</p>
                            </div>
                        </td>
                        <td class="qt">
                            {{$value->item_quantity}}
                        </td>
                        <td class="price text-right">
                            {{$value->item_subtotal}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- cart row end -->

    <div class="full_row total_price">

        <table class="shopping_cart">
            <tbody id="payment_details">                
                @foreach($response->data->payment_details as $value)                
                <tr>
                    <td>{{$value->name}}</td>
                    <td class="text-right">{{$value->price}}</td>
                </tr>
                @endforeach
                @if($response->data->claim_corporate_offer_booking === 1) 
                <tr>
                    <td>Corporate Voucher Offer</td>
                    <td class="text-right">{{ Common::currency($response->data->csub_total) }}</td>
                </tr>
                <tr class="total">
                    <td> {{$response->data->total_amount->name}}</td>
                    <td class="text-right">{{ Common::currency($response->data->corder_total - $response->data->csub_total)  }} </td>
                </tr>
                @else 
                <tr class="total">
                    <td> {{$response->data->total_amount->name}}</td>
                    <td class="text-right">{{ $response->data->total_amount->price  }} </td>
                </tr>
                @endif
                
                {{--<tr>
                    <td>Delivery Fee</td>
                    <td class="text-right">BD 0.800</td>
                </tr>
                <tr>
                    <td>VAT(5%)</td>
                    <td class="text-right">BD 0.500</td>
                </tr>
                <tr class="total">
                    <td> Total</td>
                    <td class="text-right">BD 7.700 </td>
                </tr> --}}
            </tbody>
        </table>

    </div>

</div>
