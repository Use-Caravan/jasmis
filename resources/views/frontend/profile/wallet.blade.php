@extends('frontend.layouts.layout')
@section('content')
<section class="padd-20">
    <div class="container">        
        <div class="breadcums mb-0 wow fadeInUp">
            <ul class="reset">
                <li><a href="{{route('frontend.index')}}">{{('Home')}}</a></li>
                <li><span>{{__('C wallet')}}</span></li>
            </ul>
        </div>        
    </div>
</section>

<section class="myaccount-page">
    <div class="container">                
        @if($transaction !== null)
            @if($transaction->status === TRANSACTION_STATUS_SUCCESS)
                <div class="flash-message">
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>	    
                        {{ __("apimsg.Payment has been success") }}
                    </div>
                </div>
            @else
            <div class="flash-message">
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>	    
                    {{ __("apimsg.Payment cannot capture") }}
                </div>
            </div>  
            @endif              
        @endif
        @include('frontend.layouts.partials._profile-section')
            <div class="border-boxed">                
                <div class="full_row">
                    @include('frontend.layouts.partials._profile_sidemenu')
                    <div class="account-content">
                        <h2 class="account-title wow fadeInUp">{{__('C wallet')}}</h2>                            
                        <div class="greybox-wallet wow fadeInUp">
                            <div class="icons"><img src="{{ asset(FRONT_END_BASE_PATH.'img/c-wallet-logo.png')}}"></div>
                                <div class="full_row">
                                    <div class="amount wow fadeInUp" id="wallet_amount">
                                    {{ Common::currency(Auth::guard(GUARD_USER)->user()->wallet_amount) }}   
                                    </div>
                                </div>
                        </div>

                        <!-- box -->
                        <h2 class="account-title wow fadeInUp">{{__('Add Money')}}</h2>
                        {{ Form::open(['route' => 'frontend.wallet-add', 'id' => 'wallet-form', 'class' => 'form-horizontal', 'method' => 'POST']) }}
                        <div class="full_row add_money wow fadeInUp">
                            <div class="form-group">
                            {{ Form::text("amount",'',['class' => 'form-control','id' => "c-amount",'maxlength'=>'10',"placeholder" => '0.00']) }}
                            <span class="fly_price">BD</span>
                            </div>
                            {!! Html::decode( Form::button('<span class="shape">'.__('Proceed').'</span>', ['type'=>'submit', 'class' => 'shape-btn alert-trigger shape1']) ) !!}
                        </div>
                        {{ form::close() }}
                    </div>
                </div>
            </div>
    </div>
</section>
    <!-- row end -->
<script>
$(document).ready(function()
{
    setTimeout(function(){
        $('.flash-message').hide('slow');
        var url = window.location.href;
        var a = url.indexOf("?");
        var b =  url.substring(a);
        var c = url.replace(b,"");
        url = c;
        window.history.pushState(null, null, url);
    },3000);
})
</script>
@endsection
          

    

