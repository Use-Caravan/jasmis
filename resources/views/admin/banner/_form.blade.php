{{ Form::open(['url' => $url, 'id' => 'addresstype-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST', 'enctype' => "multipart/form-data"]) }}
    <div class="box-body">
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{ $errors->has("banner_name.$key").$errors->has("banner_file.$key") }}"> 
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content full_row">
            @foreach ($languages as $key => $language)
            <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                <div class="form-group {{ ($errors->has("banner_name.$key")) ? 'has-error' : '' }}"> 
                    <div class="col-sm-12">                   
                        {{ Form::label("banner_name[$key]", __('admincrud.Banner Name'), ['class' => 'required']) }}
                        {{ Form::text("banner_name[$key]", $modelLang['banner_name'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("banner_name.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("banner_name.$key") }}</span>
                        @endif                    
                    </div>
                </div>
                <div class="form-group {{ ($errors->has("banner_file.$key")) ? 'has-error' : '' }}">                    
                    <div class="col-md-12">                          
                    {{ Form::label("banner_file[$key]", __('admincrud.Banner Image')." ( 1170W x 170H - 1180W x 180H)", ['class' => (!$model->exists) ? 'required' : '']) }}
                    {{ Form::file("banner_file[$key]", ['class' => 'form-control',"accept" => "image/*"]) }}
                    @if($errors->has("banner_file.$key"))
                        <span class="help-block error-help-block">{{ $errors->first("banner_file.$key") }}</span>
                    @endif  
                    @if(isset($modelLang['banner_file'][$key]) && $modelLang['banner_file'][$key] != null)
                        <ul class="uploads reset">
                            <li class="uploaded">                                
                                <label for="upload1" style="background:url({{ FileHelper::loadImage($modelLang['banner_file'][$key]) }})"></label>
                            </li>
                        </ul>                   
                    @endif
                    </div>
                </div> 
            </div> <!--tab-pane-->
            @endforeach
        </div> 

         <div class="form-group {{ ($errors->has("redirect_url")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("redirect_url", __('admincrud.Redirect URL'), ['class' => 'required']) }}
                {{ Form::text("redirect_url", $model['redirect_url'], ['class' => 'form-control area_latitude']) }} 
                @if($errors->has("redirect_url"))
                    <span class="help-block error-help-block">{{ $errors->first("redirect_url") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group radio_group{{ ($errors->has("is_home_banner")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                 
                {{ Form::label("is_home_banner", __('admincommon.Display in Home'),['class' => 'required']) }}         
                @php $model->is_home_banner = ($model->exists) ? $model->is_home_banner : 0 @endphp
                {{ Form::radio('is_home_banner', 1, ($model->is_home_banner == 1), ['class' => 'hide','id'=> 'ishomeon' ]) }}
                {{ Form::label("ishomeon", __('admincommon.Yes'), ['class' => ' radio']) }}
                {{ Form::radio('is_home_banner', 0, ($model->is_home_banner == 0), ['class' => 'hide','id'=>'ishomeoff']) }}
                {{ Form::label("ishomeoff", __('admincommon.No'), ['class' => 'radio']) }}
                @if($errors->has("is_home_banner"))
                    <span class="help-block error-help-block">{{ $errors->first("is_home_banner") }}</span>
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
        {{ Html::link(route('banner.index'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\BannerRequest', '#addresstype-form')  !!}