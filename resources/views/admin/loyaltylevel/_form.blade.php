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
        <div class="form-group {{ ($errors->has("redeem_amount_per_point")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("redeem_amount_per_point", __('admincrud.Redeem Amount Per Point'),['class' => 'required']) }}
                {{ Form::text("redeem_amount_per_point", $model->redeem_amount_per_point, ['class' => 'form-control']) }}
                @if($errors->has("redeem_amount_per_point"))
                    <span class="help-block error-help-block">{{ $errors->first("redeem_amount_per_point") }}</span>
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
        <div class = "clearfix">
        <div class="form-group {{ ($errors->has("card_image")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
            {{ Form::label("card_image", __('admincrud.Exist Image')."") }}
            <img src="{{ FileHelper::loadImage($model->card_image) }}" style="width: 150px;">
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
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\LoyaltyLevelRequest', '#loyaltylevel-form')  !!}