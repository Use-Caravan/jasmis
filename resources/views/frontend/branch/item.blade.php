<!-- col md 6 -->
{{-- @php echo"<pre>";print_r($iValue);exit; @endphp --}}
<div class="col-md-6 col-sm-12">
    <div class="box" id="{{$iValue->item_key}}">
        <div class="img-menu bg_style" style="background-image:url({{ $iValue->item_image }});"></div>
        <h4>{{ $iValue->item_name }}</h4>
        <div class="price-menu">
            @if($iValue->offer_enable === true)
                @if($iValue->offer_value < $iValue->item_price)
                    <p> <strike> {{ $iValue->item_price }} </strike></p>
                    <p>{{ $iValue->offer_price }}</p>
                @else 
                    <p>{{ $iValue->item_price }}</p>
                @endif
            @else 
            <p>{{ $iValue->item_price }}</p>
            @endif
            @if($branchDetails->availability_status === AVAILABILITY_STATUS_OPEN)
            <div class="menu-button-box {{ ($iValue->ingrdient_groups == null || empty($iValue->ingrdient_groups) ) ? 'in' : '' }}">
                <a href="javascript:void(0);" itemKey="{{$iValue->item_key}}" branchKey="{{$iValue->branch_key}}" class="btn white-shadow {{ auth()->guard(GUARD_USER)->check() ? 'addItemIngredient' : 'loginModel' }}">{{__('Add')}}</a>
                <span class="quantity item_quantity">
                    <button class="min {{ auth()->guard(GUARD_USER)->check() ? 'quantity_min' : 'loginModel' }}" hasIngredient="0" branchKey="{{$branchDetails->branch_key}}" itemKey="{{$iValue->item_key}}" action="minus"><i class="material-icons">remove</i></button>
                        <input type="text" name="{{$iValue->item_key}}" class="quantity_text"  readonly value="{{ $iValue->in_cart }}">                        
                    <button class="max {{ auth()->guard(GUARD_USER)->check() ? 'quantity_max' : 'loginModel' }}" hasIngredient="0" branchKey="{{$branchDetails->branch_key}}" itemKey="{{$iValue->item_key}}" action="plus"><i class="material-icons">add</i></button>
                </span>
            </div>
            @endif
        </div>
    </div>
</div>
<!-- col md 6 -->