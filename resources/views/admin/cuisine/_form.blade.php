{{ Form::open(['url' => $url, 'id' => 'cuisine-form', 'class' => 'form-horizontal', 'enctype' => "multipart/form-data", 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li class="@if($key == App::getLocale()) active @endif" haserror="{{$errors->has("cuisine_name.$key")}}">
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content full_row">
            @foreach ($languages as $key => $language)
            <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                <div class="form-group {{ ($errors->has("cuisine_name.$key")) ? 'has-error' : '' }}">
                    <div class="col-sm-12">
                        {{ Form::label("cuisine_name[$key]", __('admincrud.Cuisine Name'), ['class' => 'required']) }}
                        {{ Form::text("cuisine_name[$key]", $modelLang['cuisine_name'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("cuisine_name.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("cuisine_name.$key") }}</span>
                        @endif
                    </div>
                </div>
            </div> <!--tab-pane-->
            @endforeach
        </div>
        <div class="form-group {{ ($errors->has("sort_no")) ? 'has-error' : '' }}">
            <div class="col-sm-12">
                {{ Form::label('sort_no', __('admincrud.Sort No')) }}            
                {{ Form::text('sort_no', $model->sort_no, ['class' => 'form-control']) }}
                @if($errors->has("sort_no"))
                    <span class="help-block error-help-block">{{ $errors->first("sort_no") }}</span>
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
        {{ Html::link(route('cuisine.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\CuisineRequest', '#cuisine-form'); !!}