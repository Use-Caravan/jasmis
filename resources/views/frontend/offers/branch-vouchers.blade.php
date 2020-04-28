@if($vocher_for === 1)
<div class="modal-header text-center">
    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
    <h5 class="modal-title">{{__('Branch Vouchers')}}</h5>
</div>
<div class="modal-body voucher_pop">
    <ul class="offfer_ul">        
        @foreach($vouchers as $key => $value)
        <li>
            <div class="img" style="background: url({{$value->vendor_logo}}) no-repeat center center"></div>
            <p>{{$value->offer_title}}</p>
            <span>{{$value->offer_expiry_msg}}</span>
            <button type="submit" class="shape-btn pull-right use_voucher" voucher_code="{{$value->promo_code}}"><span class="shape">Use Code</span></button>
        </li>
        @endforeach
    </ul>
</div>
@elseif($vocher_for === 2)
<div class="modal-header text-center">
    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
    <h5 class="modal-title">{{__('Corporate Vouchers')}}</h5>
</div>
<div class="modal-body voucher_pop">
    <ul class="offfer_ul">        
        @foreach($vouchers as $key => $value)
        <li>
            <div class="img" style="background: url({{ FileHelper::loadImage($value->offer_banner)}}) no-repeat center center"></div>
            <p>{{$value->offer_name}}</p>
            @php $offerTypeText = ($value->offer_type === 1) ? $value->offer_level." Quantity" : Common::currency($value->offer_level)." amount" @endphp
            <span>{{ $value->offer_value."% offer, If you purchase more than ".$offerTypeText }}</span>
            <button type="submit" class="shape-btn pull-right use_voucher" voucher_code="{{$value->corporate_offer_key}}"><span class="shape">Use Code</span></button>
        </li>
        @endforeach
    </ul>
</div>
@endif