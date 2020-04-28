{{ Form::open(['url' => $url, 'id' => 'voucher-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST','enctype' => "multipart/form-data" ]) }}
    <div class="box-body">
       
    <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("discount_type")) ? 'has-error' : '' }}">
            {{ Form::label("discount_type", __('admincrud.Discount Type'),['class' => 'required']) }}
            @php $model->discount_type = ($model->exists) ? $model->discount_type : VOUCHER_DISCOUNT_TYPE_PERCENTAGE @endphp                    
            {{ Form::radio('discount_type', VOUCHER_DISCOUNT_TYPE_PERCENTAGE, ($model->discount_type == VOUCHER_DISCOUNT_TYPE_PERCENTAGE), ['class' => 'hide', 'id' => 'discount_percentage']) }}
            {{ Form::label("discount_percentage", __('admincrud.Percentage Based'), ['class' => 'radio']) }} 
            {{ Form::radio('discount_type', VOUCHER_DISCOUNT_TYPE_AMOUNT, ($model->discount_type == VOUCHER_DISCOUNT_TYPE_AMOUNT), ['class' => 'hide', 'id' => 'discount_amount']) }}
            {{ Form::label("discount_amount", __('admincrud.Amount Based'), ['class' => 'radio']) }} 
             @if($errors->has("discount_type"))
                    <span class="help-block error-help-block">{{ $errors->first("discount_type") }}</span>
                @endif                    
            </div>
        </div>
        
         <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("promo_code")) ? 'has-error' : '' }}">                    
                {{ Form::label("promo_code", __('admincrud.Manual Voucher Code')) }}
                {{ Form::text("promo_code", $model['promo_code'], ['class' => 'form-control']) }} 
                @if($errors->has("promo_code"))
                    <span class="help-block error-help-block">{{ $errors->first("promo_code") }}</span>
                @endif 
            </div>                   
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("max_redeem_amount")) ? 'has-error' : '' }}">                    
                {{ Form::label("max_redeem_amount", __('admincrud.Maximum Redeem Amount'), ['class' => 'required']) }}
                {{ Form::text("max_redeem_amount", $model['max_redeem_amount'], ['class' => 'form-control']) }} 
                @if($errors->has("max_redeem_amount"))
                    <span class="help-block error-help-block">{{ $errors->first("max_redeem_amount") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("value")) ? 'has-error' : '' }}">                    
                {{ Form::label("value", __('admincrud.Value'), ['class' => 'required']) }}
                {{ Form::text("value", $model['value'], ['class' => 'form-control']) }} 
                @if($errors->has("value"))
                    <span class="help-block error-help-block">{{ $errors->first("value") }}</span>
                @endif                    
            </div>
        </div> 
        <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("min_order_value")) ? 'has-error' : '' }}">                    
                {{ Form::label("min_order_value", __('admincrud.Min Order Value'), ['class' => 'required']) }}
                {{ Form::text("min_order_value", $model['min_order_value'], ['class' => 'form-control']) }} 
                @if($errors->has("min_order_value"))
                    <span class="help-block error-help-block">{{ $errors->first("min_order_value") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("app_type")) ? 'has-error' : '' }}">
            {{ Form::label("app_type", __('admincrud.App Type'), ['class' => 'required']) }} 
            {{ Form::select('app_type[]', $model->selectAppTypes(),$explodeAppType ,['multiple'=>'multiple','class' => 'selectpicker','title' => __('admincrud.Please Choose App Type')] )}}
                @if($errors->has("app_type"))
                    <span class="help-block error-help-block">{{ $errors->first("app_type") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("expiry_date")) ? 'has-error' : '' }}">                    
                {{ Form::label("expiry_date", __('admincrud.Expiry Date'), ['class' => 'required']) }}
                {{ Form::text("expiry_date",$model->expiry_date, [ 'data-val' => $model->expiry_date, 'class' => 'form-control','id'=>'expiry_date_picker', "autocomplete" => "off"]) }} 
                @if($errors->has("expiry_date"))
                    <span class="help-block error-help-block">{{ $errors->first("min_order_value") }}</span>
                @endif                    
            </div>
        </div>
        <div class = "clearfix"></div>
        <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("limit_of_use")) ? 'has-error' : '' }}">                    
                {{ Form::label("limit_of_use", __('admincrud.Limit Of Use')) }}
                {{ Form::text("limit_of_use", $model['limit_of_use'], ['class' => 'form-control']) }} 
                @if($errors->has("limit_of_use"))
                    <span class="help-block error-help-block">{{ $errors->first("limit_of_use") }}</span>
                @endif 
            </div>                   
        </div>
        <div class="clearfix">
       <div class="col-md-6">
            <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">
                {{ Form::label("voucher_status", __('admincrud.Voucher Status'),['class' => 'required']) }}
                @php $model->status = ($model->exists) ? $model->status : ITEM_ACTIVE @endphp
                {{ Form::radio('status', ITEM_ACTIVE, ($model->status == ITEM_ACTIVE), ['class' => 'hide','id'=> 'statuson' ]) }}
                {{ Form::label("statuson", __('admincommon.Active'), ['class' => ' radio']) }}
                {{ Form::radio('status', ITEM_INACTIVE, ($model->status == ITEM_INACTIVE), ['class' => 'hide','id'=>'statusoff']) }}
                {{ Form::label("statusoff", __('admincommon.Inactive'), ['class' => 'radio']) }}
                @if($errors->has("status"))
                    <span class="help-block error-help-block">{{ $errors->first("status") }}</span>
                @endif                    
            </div>
        </div>
        <div class = "clearfix"></div>
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("apply_promo_for")) ? 'has-error' : '' }}">
            {{ Form::label("apply_promo_for", __('admincrud.Apply Promo'), ['class' => 'required']) }} 
            {{ Form::select('apply_promo_for', $model->selectApplyPromo(), $model->apply_promo_for ,['class' => 'selectpicker','placeholder' => __('admincrud.Please choose promo for'),'id' => 'apply_promo'] )}}
                <span id="apply_promo-error" class="help-block error-help-block"></span>
                @if($errors->has("apply_promo_for"))
                    <span class="help-block error-help-block">{{ $errors->first("apply_promo_for") }}</span>
                @endif 
            </div>
        </div>
       <div class = "clearfix"></div>
        <div class="col-md-6 {{ ($model->apply_promo_for == VOUCHER_APPLY_PROMO_BOTH || $model->apply_promo_for == VOUCHER_APPLY_PROMO_SHOPS) ? '' : 'hide' }}" id = "promo_for_all_shops">
            <div class="form-group {{ ($errors->has("promo_for_shops")) ? 'has-error' : '' }}">
            {{ Form::label("promo_for_shops", __('admincrud.Apply Promo For Shops'), ['class' => 'required']) }} 
            {{ Form::select('promo_for_shops', $model->selectPromoForShops(), $model->promo_for_shops ,['class' => 'selectpicker','id' => 'promo_for_all_shop','placeholder' => __('admincrud.Please choose promo for')] )}}
                @if($errors->has("promo_for_shops"))
                    <span class="help-block error-help-block">{{ $errors->first("promo_for_shops") }}</span>
                @endif 
            </div>
        </div>            
        <div class="pull-right  col-md-6 {{ ($model->apply_promo_for == VOUCHER_APPLY_PROMO_BOTH || $model->apply_promo_for == VOUCHER_APPLY_PROMO_USERS) ? '' : 'hide' }}" id = "promo_for_all_user">
            <div class="form-group {{ ($errors->has("promo_for_user")) ? 'has-error' : '' }}">
            {{ Form::label("promo_for_user", __('admincrud.Apply Promo For Users'), ['class' => 'required']) }} 
            {{ Form::select('promo_for_user', $model->selectPromoForUser(),$model->promo_for_user ,['class' => 'selectpicker','id' => 'promo_for_all_users','placeholder' => __('admincrud.Please choose promo for')] )}}
                @if($errors->has("promo_for_user"))
                    <span class="help-block error-help-block">{{ $errors->first("promo_for_user") }}</span>
                @endif                             
            </div>
        </div>        
        <div class="clearfix"></div>
        <div class="col-md-6 {{ (($model->apply_promo_for == VOUCHER_APPLY_PROMO_BOTH || $model->apply_promo_for == VOUCHER_APPLY_PROMO_SHOPS) && $model->promo_for_shops == PROMO_SHOPS_PARTICULAR) ? '' : 'hide' }}" id = "promo_for_particular_shop"> 
            <div class="form-group {{ ($errors->has("shopbeneficiary_id")) ? 'has-error' : '' }}"> 
            {{Form::label('shopbeneficiary_id', __('admincrud.Beneficiar Shop Name'),['class' => 'required'])}}
            {{Form::select('shopbeneficiary_id[]',$branchList,$existsShopBenificiary,['multiple'=>'multiple','class' => 'selectpicker','title' => __('admincommon.Nothing selected')])}}
            @if($errors->has("shopbeneficiary_id"))
                <span class="help-block error-help-block">{{ $errors->first("shopbeneficiary_id") }}</span>                
            @endif 
            </div> 
        </div>        
        <div class="pull-right col-md-6 {{ (($model->apply_promo_for == VOUCHER_APPLY_PROMO_BOTH || $model->apply_promo_for == VOUCHER_APPLY_PROMO_USERS) && $model->promo_for_user == PROMO_USER_PARTICULAR) ? '' : 'hide' }}" id = "promo_for_particular_user"> 
            <div class="form-group {{ ($errors->has("userbeneficiary_id")) ? 'has-error' : '' }}"> 
            {{Form::label('userbeneficiary_id', __('admincrud.Beneficiar User Name'),['class' => 'required'])}}
            {{Form::select('userbeneficiary_id[]',$userList,$existsUserBenificiary,['multiple'=>'multiple','class' => 'selectpicker','title' => __('admincommon.Nothing selected')])}}
            @if($errors->has("userbeneficiary_id"))
                <span class="help-block error-help-block">{{ $errors->first("userbeneficiary_id") }}</span>
            @endif 
            </div> 
        </div>
                                            
    </div>
  
    <div class="box-footer">  
        {{ Html::link(route('voucher.index'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\VoucherRequest', '#voucher-form')  !!}


<script> 
$(document).ready(function(){
    
    
    $('#expiry_date_picker').datetimepicker({    
         "minDate" : new Date(), 
        /* "defaultDate" : new Date() */
    });
    if($('#expiry_date_picker').data('val') != undefined)  {
        $('#expiry_date_picker').val($('#expiry_date_picker').data('val'));
    }           
    
    $('#apply_promo').on('change', function () {
    var valueSelected =  $(this).val();
    if (valueSelected == {{ PROMO_FOR_ALL_SHOPS }}) {        
        $('#promo_for_all_shops').removeClass('hide');
        $('#promo_for_all_user').addClass('hide');
        $('#promo_for_particular_user').addClass('hide');
    }
    else if (valueSelected == {{ PROMO_FOR_ALL_USERS }}) {        
        $('#promo_for_all_shops').addClass('hide');
        $('#promo_for_all_user').removeClass('hide');
        $('#promo_for_particular_shop').addClass('hide');
        
    }
    else if (valueSelected == {{ PROMO_FOR_BOTH }}) {
        $('#promo_for_all_shops').removeClass('hide');
        $('#promo_for_all_user').removeClass('hide');
       
    }
    else {
        $('#promo_for_all_shops').addClass('hide');
        $('#promo_for_all_user').addClass('hide');
    }
    });
    $('#promo_for_all_shop').on('change', function () {
        var valueSelected = $(this).val();
        if (valueSelected == {{ PROMO_SHOPS_PARTICULAR }}) {
            $('#promo_for_particular_shop').removeClass('hide');            
        }else{
            $('#promo_for_particular_shop').addClass('hide');            
        }            
    });

    $('#promo_for_all_users').on('change',function() {
        
        var valueSelected = $(this).val();
        if (valueSelected == {{ PROMO_USER_PARTICULAR }}) {
            $('#promo_for_particular_user').removeClass('hide');            
        }
        else{
            $('#promo_for_particular_user').addClass('hide');            
        }        
    });
}); 
</script>

