{{ Form::open(['url' => $url, 'id' => 'loyaltylevel-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST', 'files' => 1 ]) }}
    <div class="box-body">
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{$errors->has("country_name.$key")}}"> 
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content full_row">
            @foreach ($languages as $key => $language)
            <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                <div class="form-group {{ ($errors->has("loyalty_level_name.$key")) ? 'has-error' : '' }}"> 
                    <div class="col-sm-12">                   
                        {{ Form::label("loyalty_level_name[$key]", __('admincrud.Loyalty Level Name'), ['class' => 'required']) }}
                        {{ Form::text("loyalty_level_name[$key]", $modelLang['loyalty_level_name'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("loyalty_level_name.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("loyalty_level_name.$key") }}</span>
                        @endif                    
                    </div>
                </div>
            </div> <!--tab-pane-->
            @endforeach
        </div>
        <div class="form-group {{ ($errors->has("from_point")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("from_point", __('admincrud.From Point'),['class' => 'required']) }}
                {{ Form::text("from_point", $model->from_point, ['class' => 'form-control']) }}
                @if($errors->has("from_point"))
                    <span class="help-block error-help-block">{{ $errors->first("from_point") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("to_point")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("to_point", __('admincrud.To Point'),['class' => 'required']) }}
                {{ Form::text("to_point", $model->to_point, ['class' => 'form-control']) }}
                @if($errors->has("to_point"))
                    <span class="help-block error-help-block">{{ $errors->first("to_point") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group loyalty_point_per_bd_div {{ ($errors->has("loyalty_point_per_bd")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("loyalty_point_per_bd", __('admincrud.Loyalty Point Per BD'),['class' => 'required']) }}
                {{ Form::text("loyalty_point_per_bd", $model->loyalty_point_per_bd, ['class' => 'form-control only-numeric', 'maxlength' => '5']) }}
                @if($errors->has("loyalty_point_per_bd"))
                    <span class="help-block error-help-block">{{ $errors->first("loyalty_point_per_bd") }}</span>
                @endif
            </div>
        </div>
        <div class="form-group form-group-help redeem_amount_per_point_div {{ ($errors->has("redeem_amount_per_point")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("redeem_amount_per_point", __('admincrud.Redeem Amount Per Point ( In Fils )'),['class' => 'required']) }}
                {{ Form::text("redeem_amount_per_point", $model->redeem_amount_per_point, ['class' => 'form-control only-numeric', 'maxlength' => '5']) }}
                @if($errors->has("redeem_amount_per_point"))
                    <span class="help-block error-help-block">{{ $errors->first("redeem_amount_per_point") }}</span>
                @endif         
            </div>
            <span class="help-block-text error-help-block redeem_amount_in_bd"></span>
        </div> 
        <br>
        <div class="form-group {{ ($errors->has("minimum_amount_to_redeem")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("minimum_amount_to_redeem", __('admincrud.Minimum Amount to Redeem ( In BD )'),['class' => 'required']) }}
                {{ Form::text("minimum_amount_to_redeem", $model->minimum_amount_to_redeem, ['class' => 'form-control only-numeric', 'maxlength' => '5']) }}
                @if($errors->has("minimum_amount_to_redeem"))
                    <span class="help-block error-help-block">{{ $errors->first("minimum_amount_to_redeem") }}</span>
                @endif
            </div>
        </div> 
        <div class="form-group {{ ($errors->has("card_image")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
            {{ Form::label("card_image", __('admincrud.Card Image')." ( 550W x 356H )", ['class' => (!$model->exists) ? 'required' : '']) }}
            {{ Form::file("card_image", ['class' => 'form-control',"accept" => "image/*"]) }}
            @if($errors->has("card_image"))
                <span class="help-block error-help-block">{{ $errors->first("card_image") }}</span>
            @endif  
            </div>
        </div>
        
        @if($model->exists)
        <div class = "clearfix"></div>
        <div class="form-group {{ ($errors->has("card_image")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
            {{ Form::label("card_image", __('admincrud.Exist Image')."") }}
            <img src="{{ FileHelper::loadImage($model->card_image) }}" style="width: 150px;">
            </div>
        </div>
        @endif
        <div class="form-group {{ ($errors->has("popup_image")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
            {{ Form::label("popup_image", __('admincrud.Popup Image')." ( 200W x 400H )", ['class' => (!$model->exists) ? 'required' : '']) }}
            {{ Form::file("popup_image", ['class' => 'form-control',"accept" => "image/*"]) }}
            @if($errors->has("popup_image"))
                <span class="help-block error-help-block">{{ $errors->first("popup_image") }}</span>
            @endif  
            </div>
        </div>
        @if($model->exists)
        <div class = "clearfix"></div>
        <div class="form-group {{ ($errors->has("popup_image")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
            {{ Form::label("popup_image", __('admincrud.Popup Image')."") }}
            <img src="{{ FileHelper::loadImage($model->popup_image) }}" style="width: 150px;">
            </div>
        </div>
        @endif
        <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("status", __('admincommon.Status'),['class' => 'required']) }}
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
  <!-- /.box-body -->
    <div class="box-footer">
        {{ Html::link(route('loyaltylevel.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right loyaltylevel_save']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\LoyaltyLevelRequest', '#loyaltylevel-form')  !!}

<script> 
    $(document).ready(function(){
        var redeem_amount_per_point = $('#redeem_amount_per_point').val();
        var redeem_amount_in_bd = redeem_amount_per_point / 1000;
        $(".redeem_amount_in_bd").text(redeem_amount_in_bd+" BD");

        $('#redeem_amount_per_point').on('keyup', function () {
            var redeem_amount_per_point = $(this).val();
            var redeem_amount_in_bd = redeem_amount_per_point / 1000;
            $(".redeem_amount_in_bd").text(redeem_amount_in_bd+" BD");
        });

        $(".only-numeric").bind("keypress", function (e) {
            var keyCode = e.which ? e.which : e.keyCode
               
            if (!(keyCode >= 48 && keyCode <= 57))
                return false;
        });

        /** Check loyalty point per BD is greater than higher loyalty level's or not **/
        $('#loyalty_point_per_bd').change(function()
        {   
            checkLoyaltyPointByLevel("loyalty_point_per_bd");
        });

        /** Check loyalty point per BD is greater than higher loyalty level's or not **/
        $('#redeem_amount_per_point').change(function()
        {   
            checkLoyaltyPointByLevel("redeem_amount_per_point");
        });

        $("form").submit(function(e) {
            e.preventDefault();

            var self = this,
                loyalty_point_per_bd = $('#loyalty_point_per_bd').val(),
                redeem_amount_per_point = $('#redeem_amount_per_point').val(),
                to_point = $('#to_point').val(),
                type = "both";

            $.ajax({
                type: "POST",
                url: "{{ route('loyaltypoint-by-level') }}",
                data: {loyalty_point_per_bd: loyalty_point_per_bd, redeem_amount_per_point: redeem_amount_per_point, to_point: to_point, type: type},
                cache: false
            }).done(function(result) {
                if(result.status == AJAX_SUCCESS){
                    if( result.loyalty_level_high_count == 0 && result.loyalty_level_low_count == 0 && result.redeem_amount_per_point_high_count == 0 && result.redeem_amount_per_point_low_count == 0 ) {
                        $('.loyalty_point_per_bd_div').addClass('has-success');
                        $("#loyalty_point_per_bd-error").text("");

                        $('.redeem_amount_per_point_div').addClass('has-success');
                        $("#redeem_amount_per_point-error").text("");

                        //$("#loyaltylevel-form").submit();
                        self.submit();
                    }
                    
                    if( result.loyalty_level_high_count > 0 ) {
                        $('.loyalty_point_per_bd_div').addClass('has-error');
                        $("#loyalty_point_per_bd-error").text("Loyalty point per BD should not greater than higher loyalty level's");
                    }
                    
                    if( result.loyalty_level_low_count > 0 ) {
                        $('.loyalty_point_per_bd_div').addClass('has-error');
                        $("#loyalty_point_per_bd-error").text("Loyalty point per BD should not lesser than lower loyalty level's");
                    }
                    
                    if( result.redeem_amount_per_point_high_count > 0 ) {
                        $('.redeem_amount_per_point_div').addClass('has-error');
                        $("#redeem_amount_per_point-error").text("Redeem amount per point should not greater than higher loyalty level's");
                    }
                    
                    if( result.redeem_amount_per_point_low_count > 0 ) {
                        $('.redeem_amount_per_point_div').addClass('has-error');
                        $("#redeem_amount_per_point-error").text("Redeem amount per point should not lesser than lower loyalty level's");
                    }
                }
                else{
                    Error('Something went wrong','Error');
                }
            }).fail(function() {
                //alert('error');
                Error('Something went wrong','Error');
            });
        });

        /** Check loyalty point per BD is greater than higher loyalty level's or not **/
        function checkLoyaltyPointByLevel( type )
        {
            //alert(type);
            var to_point = $('#to_point').val();
            if( type == 'loyalty_point_per_bd' ) {
                var loyalty_point_per_bd = $('#loyalty_point_per_bd').val();  
                var redeem_amount_per_point = "";              
            }
            else if( type == 'redeem_amount_per_point' ) {
                var redeem_amount_per_point = $('#redeem_amount_per_point').val();  
                var loyalty_point_per_bd = "";              
            }

            if( ( type == 'loyalty_point_per_bd' && loyalty_point_per_bd > 0 && to_point > 0 ) || ( type == 'redeem_amount_per_point' && redeem_amount_per_point > 0 && to_point > 0 ))
            {
                $.ajax({
                    url: "{{ route('loyaltypoint-by-level') }}",
                    type: 'post',
                    data: {loyalty_point_per_bd: loyalty_point_per_bd, redeem_amount_per_point: redeem_amount_per_point, to_point: to_point, type: type},
                    success: function(result){ 
                        if(result.status == AJAX_SUCCESS){
                            if( result.high_count == 0 && result.low_count == 0 ) {
                                if( type == 'loyalty_point_per_bd' ) {
                                    $('.loyalty_point_per_bd_div').addClass('has-success');
                                    $("#loyalty_point_per_bd-error").text("");
                                }
                                else if( type == 'redeem_amount_per_point' ) {
                                    $('.redeem_amount_per_point_div').addClass('has-success');
                                    $("#redeem_amount_per_point-error").text("");
                                }
                            }
                            if( result.high_count > 0 ) {
                                if( type == 'loyalty_point_per_bd' ) {
                                    $('.loyalty_point_per_bd_div').addClass('has-error');
                                    $("#loyalty_point_per_bd-error").text("Loyalty point per BD should not greater than higher loyalty level's");
                                }
                                else if( type == 'redeem_amount_per_point' ) {
                                    $('.redeem_amount_per_point_div').addClass('has-error');
                                    $("#redeem_amount_per_point-error").text("Redeem amount per point should not greater than higher loyalty level's");
                                }
                            }
                            
                            if( result.low_count > 0 ) {
                                if( type == 'loyalty_point_per_bd' ) {
                                    $('.loyalty_point_per_bd_div').addClass('has-error');
                                    $("#loyalty_point_per_bd-error").text("Loyalty point per BD should not lesser than lower loyalty level's");
                                }
                                else if( type == 'redeem_amount_per_point' ) {
                                    $('.redeem_amount_per_point_div').addClass('has-error');
                                    $("#redeem_amount_per_point-error").text("Redeem amount per point should not lesser than lower loyalty level's");
                                }
                            }
                        }else{
                            Error('Something went wrong','Error');
                        }
                    }
                });
            }
        }
    });
</script>