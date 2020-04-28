{{ Form::open(['url' => $url, 'id' => 'ingredient-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{$errors->has("ingredient_name.$key")}}"> 
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content full_row">
            @foreach ($languages as $key => $language)
            <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                <div class="form-group {{ ($errors->has("ingredient_name.$key")) ? 'has-error' : '' }}"> 
                    <div class="col-sm-12">                   
                        {{ Form::label("ingredient_name[$key]", __('admincrud.Ingredient Name'), ['class' => 'required']) }}
                        {{ Form::text("ingredient_name[$key]", $modelLang['ingredient_name'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("ingredient_name.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("ingredient_name.$key") }}</span>
                        @endif                    
                    </div>
                </div>
            </div> <!--tab-pane-->
            @endforeach
        </div>

        @if(APP_GUARD == GUARD_ADMIN)
        <div class="form-group {{ ($errors->has("vendor_id")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("vendor_id", __('admincrud.Vendor Name')) }}
                {{ Form::select("vendor_id", $vendorList, $model->vendor_id, ['class' => 'selectpicker','placeholder' => 'Choose ingredient']) }} 
                @if($errors->has("vendor_id.$key"))
                    <span class="help-block error-help-block">{{ $errors->first("vendor_id.$key") }}</span>
                @endif                    
            </div>
        </div>
        @else 
            {{ Form::hidden("vendor_id", auth()->guard(GUARD_VENDOR)->user()->vendor_id) }}
        @endif

        <div class="form-group {{ ($errors->has("sort_no")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("sort_no", __('admincrud.Sort No')) }}
                {{ Form::text("sort_no", $model->sort_no, ['class' => 'form-control']) }} 
                @if($errors->has("sort_no.$key"))
                    <span class="help-block error-help-block">{{ $errors->first("sort_no.$key") }}</span>
                @endif                    
            </div>
        </div>
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
        {{ Html::link(route('ingredient.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\IngredientRequest', '#ingredient-form')  !!}