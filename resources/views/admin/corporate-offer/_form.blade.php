{{ Form::open(['url' => $url, 'id' => 'corporate-offer-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST','enctype' => "multipart/form-data" ]) }}
    <div class="box-body">
            <ul class="nav nav-tabs full_row">
                    @foreach ($languages as $key => $language)                             
                        <li @if($key == App::getLocale()) class="active" @endif> <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a></li>
                    @endforeach
                </ul>
                <div class="tab-content full_row">            
                    @foreach ($languages as $key => $language)
                        <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">                
        
                            <div class="col-md-6">                
                                <div class="form-group {{ ($errors->has("offer_name.$key")) ? 'has-error' : '' }}" >
                                    {{ Form::label("offer_name[$key]", __('admincrud.Offer Name'), ['class' => 'required']) }}
                                    {{ Form::text("offer_name[$key]", $modelLang['offer_name'][$key], ['class' => 'form-control']) }} 
                                    @if($errors->has("offer_name.$key"))
                                        <span class="help-block error-help-block">{{ $errors->first("offer_name.$key") }}</span>
                                    @endif                    
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group {{ ($errors->has("offer_banner.$key")) ? 'has-error' : '' }}">
                                    <ul class="uploads reset">                                
                                <li>
                                    {{ 
                                        Form::label(
                                        "offer_banner[$key]",
                                        __('admincrud.Offer Banner')." (1170 X 170)", 
                                        [
                                            'class' => 'required fa fa-plus-circle',
                                            "id" => "offer_banner$key",
                                            'style' => ($model->exists) ? 'background:url('.FileHelper::loadImage(  isset($modelLang["offer_banner"][$key]) ? $modelLang["offer_banner"][$key] : ''  ).')' : ''
                                                                                                                
                                        ])
                                    }}

                                    {{ Form::file("offer_banner[$key]", ['class' => 'form-control upload_image','lang' => $key]) }}
                                    {{--  
                                        <input id="upload1" type="file">
                                        <label for="upload1"><i class="fa fa-plus-circle"></i></label> 
                                    --}}                                
                                </li>
                                    @if($errors->has("offer_banner.$key"))
                                        <span class="help-block error-help-block">{{ $errors->first("offer_banner.$key") }}</span>
                                    @endif 
                                </ul>
                        </div>
                    </div>
                            <div class="col-md-6">
                            <div class="form-group {{ ($errors->has("offer_description.$key")) ? 'has-error' : '' }}" >
                                {{ Form::label("offer_description[$key]", __('admincrud.Offer Description'), ['class' => 'required']) }}
                                {{ Form::textarea("offer_description[$key]", $modelLang['offer_description'][$key], ['class' => 'form-control']) }} 
                                @if($errors->has("offer_description.$key"))
                                    <span class="help-block error-help-block">{{ $errors->first("offer_description.$key") }}</span>
                                @endif                    
                            </div>
                        </div>                            
                    </div>                
                    @endforeach
                </div> <!--tab-pane-->
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("offer_type")) ? 'has-error' : '' }}">                
            {{ Form::label("offer_type", __('admincrud.Offer Type'),['class' => 'required']) }}
            @php $model->offer_type = ($model->exists) ? $model->offer_type : CORPORATE_OFFER_TYPE_QUANTITY @endphp
            {{ Form::radio('offer_type', CORPORATE_OFFER_TYPE_QUANTITY, ($model->offer_type == CORPORATE_OFFER_TYPE_QUANTITY), ['class' => 'hide', 'id' => 'offer_qty']) }}
            {{ Form::label("offer_qty", __('admincrud.Quantity Based'), ['class' => 'radio']) }}
            {{ Form::radio('offer_type', CORPORATE_OFFER_TYPE_AMOUNT, ($model->offer_type == CORPORATE_OFFER_TYPE_AMOUNT), ['class' => 'hide', 'id' => 'offer_amount']) }}
            {{ Form::label("offer_amount", __('admincrud.Amount Based'), ['class' => 'radio']) }}
            @if($errors->has("discount_type"))
                <span class="help-block error-help-block">{{ $errors->first("discount_type") }}</span>
            @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("offer_level")) ? 'has-error' : '' }}">                    
                {{ Form::label("offer_level", __('admincrud.Offer Level'), ['class' => 'required']) }}
                {{ Form::text("offer_level", $model->offer_level, ['class' => 'form-control','id' => 'offer_level']) }} 
                @if($errors->has("offer_level"))
                    <span class="help-block error-help-block">{{ $errors->first("offer_level") }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("offer_value")) ? 'has-error' : '' }}">                    
                {{ Form::label("offer_value", __('admincrud.Offer Value In Percentage'), ['class' => 'required']) }}
                {{ Form::text("offer_value", $model->offer_value, ['class' => 'form-control','id' => 'offer_value']) }} 
                @if($errors->has("offer_value"))
                    <span class="help-block error-help-block">{{ $errors->first("offer_value") }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("start_datetime")) ? 'has-error' : '' }}">                    
                {{ Form::label("start_datetime", __('admincrud.Start Datetime'), ['class' => 'required']) }}
                {{ Form::text("start_datetime",$model->start_datetime, [ 'data-val' => $model->start_datetime, 'class' => 'form-control datetimepicker','id'=>'start_date_picker', "autocomplete" => "off"]) }} 
                @if($errors->has("start_datetime"))
                    <span class="help-block error-help-block">{{ $errors->first("start_datetime") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("end_datetime")) ? 'has-error' : '' }}">                    
                {{ Form::label("end_datetime", __('admincrud.Expiry Date'), ['class' => 'required']) }}
                {{ Form::text("end_datetime",$model->end_datetime, [ 'data-val' => $model->end_datetime, 'class' => 'form-control datetimepicker','id'=>'end_date_picker', "autocomplete" => "off"]) }}
                @if($errors->has("end_datetime"))
                    <span class="help-block error-help-block">{{ $errors->first("end_datetime") }}</span>
                @endif                    
            </div>
        </div>

        {{-- @if(APP_GUARD === GUARD_ADMIN)
            <div class="col-md-6">  
                <div class="form-group {{ ($errors->has("vendor_id")) ? 'has-error' : '' }}">
                    {{ Form::label("vendor_id", __('admincrud.Vendor Name'), ['class' => 'required']) }}
                    {{ Form::select('vendor_id', $vendorList, $model->vendor_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Vendor'),'id' => 'Offer-vendor_id'] )}}
                    @if($errors->has("vendor_id"))
                        <span class="help-block error-help-block">{{ $errors->first("vendor_id") }}</span>
                    @endif
                </div>
            </div>
        @endif
        @if(APP_GUARD === GUARD_ADMIN || APP_GUARD === GUARD_VENDOR)
            <div class="col-md-6">  
                <div class="form-group {{ ($errors->has("branch_id")) ? 'has-error' : '' }}">
                    {{ Form::label("branch_id", __('admincrud.Branch Name'), ['class' => 'required']) }}
                    {{ Form::select('branch_id', $branchList, $model->branch_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Branch'),'id' => 'Offer-branch_id'] )}}
                    @if($errors->has("branch_id"))
                        <span class="help-block error-help-block">{{ $errors->first("branch_id") }}</span>
                    @endif                    
                </div>
            </div>
        @endif        
        @if(APP_GUARD === GUARD_VENDOR || APP_GUARD === GUARD_OUTLET)
            {{ Form::hidden("vendor_id",auth()->guard(GUARD_VENDOR)->user()->vendor_id, ['class' => 'form-control']) }}
        @endif
        @if(APP_GUARD === GUARD_OUTLET)        
            {{ Form::hidden("branch_id",$model->branch_id, ['class' => 'form-control']) }}
        @endif

        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("item_id")) ? 'has-error' : '' }}">
                {{ Form::label("item_id", __('admincrud.Item Name'), ['class' => 'required']) }}
                {{ Form::select('item_id[]', $itemList, $existsItem ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Item'), 'multiple'=>'multiple', 'id' => 'Offer-item_id'] )}}
                @if($errors->has("item_id"))
                    <span class="help-block error-help-block">{{ $errors->first("item_id") }}</span>
                @endif                    
            </div>
        </div>                 
            
        <div class="col-md-6">
                <div class="form-group {{ ($errors->has("display_in_home")) ? 'has-error' : '' }}">
            {{ Form::label("display_in_home", __('admincrud.Do you want to display in home page?'),['class' => 'required']) }}
            @php $model->offer_type = ($model->exists) ? $model->display_in_home : 2 @endphp
            {{ Form::radio('display_in_home', 1, ($model->offer_type == 1), ['class' => 'hide', 'id' => 'yes']) }}
            {{ Form::label("yes", __('admincommon.Yes'), ['class' => 'radio']) }}
            {{ Form::radio('display_in_home', 2, ($model->offer_type == 2), ['class' => 'hide', 'id' => 'no']) }}
            {{ Form::label("no", __('admincommon.No'), ['class' => 'radio']) }}
            @if($errors->has("display_in_home"))
                <span class="help-block error-help-block">{{ $errors->first("display_in_home") }}</span>
            @endif
            </div>
        </div> --}}
        <div class="clearfix">
       <div class="col-md-6">
            <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">
                {{ Form::label("voucher_status", __('admincrud.Offer Status'),['class' => 'required']) }}
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
    </div>
  
    <div class="box-footer">  
        {{ Html::link(route('corporate-offer.index'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\CorporateOfferRequest', '#corporate-offer-form')  !!}


<script>
    $(document).ready(function() {
        $('.datetimepicker').datetimepicker({    
            "minDate" : new Date(),
        });    

        $('#start_date_picker').val('{{ ($model->start_datetime !== null) ? date("m/d/Y h:i A",strtotime($model->start_datetime)) : '' }}');
        $('#end_date_picker').val('{{ ($model->end_datetime !== null) ?  date("m/d/Y h:i A",strtotime($model->end_datetime)) : '' }}');
    });
</script>