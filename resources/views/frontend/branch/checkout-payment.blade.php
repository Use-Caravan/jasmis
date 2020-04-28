
@foreach($cartItem->payment_details as $key => $value)
<tr>
    <td>{{$value->name}}</td>
    <td class="text-right">{{$value->price}} </td>
</tr>
@endforeach
{{-- <tr>
    <td>Delivery Fee</td>
    <td class="text-right">BD 0.800</td>
</tr>
<tr>
    <td>VAT(5%)</td>
    <td class="text-right">BD 0.500</td>
</tr> --}}
@if(session::has('corporate_voucher'))

<td>{{$cartItem->total->name}}</td>
    <td class="text-right">{{ Common::currency($cartItem->total->cprice -$cartItem->sub_total->cprice) }}</td>
</tr>

@else
<td>{{$cartItem->total->name}}</td>
    <td class="text-right">{{ $cartItem->total->price}}</td>
</tr>
@endif