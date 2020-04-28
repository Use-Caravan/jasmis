@extends('frontend.layouts.layout')
@section('content')

<section>
<div class="container">

		<div class="offers">

			<ul class="nav nav-tabs" id="offerTab" role="tablist">
			  <li class="nav-item">
			    <a class="nav-link active" data-toggle="tab" href="#offer" role="tab" aria-selected="true">{{__('Offers')}}</a>
			  </li>
			  <li class="nav-item">
			    <a class="nav-link" data-toggle="tab" href="#voucher" role="tab" aria-selected="false">{{__('Voucher')}}</a>
			  </li>
			</ul>

			<div class="tab-content" id="offerTabContent">

				<div class="tab-pane fade show active" id="offer" role="tabpanel">
					<ul class="offfer_ul">
                        @if($offerItems == null)
                            <p>{{__('No offers found')}}</p>
                        @endif
                        @foreach($offerItems as $key => $item)
                        <li>
                            <a href="{{ route('frontend.branch.show',[$item->branch_slug]) }}"><div class="img" style="background: url({{ FileHelper::loadImage($item->branch_logo) }}) no-repeat center center"></div></a>
                            <a href="{{ route('frontend.branch.show',[$item->branch_slug]) }}">{{ $item->offer_name }}</a>
                            <a href="{{ route('frontend.branch.show',[$item->branch_slug]) }}"><p>Get {{ $item->offer_value }} off on this {{$item->item_name}}</p></a>
                            <a href="{{ route('frontend.branch.show',[$item->branch_slug]) }}"><span>{{ $item->branch_name }}</span></a>
                        </li>
                        @endforeach
                        
					</ul>
				</div>

				<div class="tab-pane fade" id="voucher" role="tabpanel">
				  	<div class="corporate_rest">
				        <div class="row">
					        <ul class="rest_ul">
                                @if($voucherBranches == null)
                                    <p>{{__('No vouchers found')}}</p>
                                @endif
                                @foreach($voucherBranches as $key => $value)
                                <li>
                                    <a href="{{ route('frontend.branch.show',[$value->branch_slug]) }}" class="current_page voucher_branch" branchKey={{ $value->branch_key }} href="javascript:" style="background: url({{$value->branch_logo}}) #EA362F no-repeat center center"></a>
                                    <span>{{$value->branch_name}}</span>
                                </li>
                                @endforeach
                            </ul>
				        </div>
				    </div>
				</div>

			</div>

	     </div>
      </div>
</section>
@endsection
   
    