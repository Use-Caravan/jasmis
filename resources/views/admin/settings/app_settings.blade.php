@extends('admin.layouts.layout')
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
          @include('admin.layouts.partials._flashmsg')
            <h3 class="box-title">@lang('admincrud.App Configuration')</h3>
          </div>            
          {{ Form::open(['url' => route('admin-app-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ]) }}          
          {{ Form::hidden('config_name',CONFIG_APP) }}
            <div class="box-body"> 
                <div class="col-md-6">
                    <div class="form-group {{ ($errors->has('app_name')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_name", __('admincrud.App Name'), ['class' => 'required']) }}
                            {{ Form::text('app_name', config('webconfig.app_name'), ['class' => 'form-control','placeholder' => __('admincrud.App Name')]  ) }}
                            @if($errors->has("app_name"))
                                <span class="help-block error-help-block">{{ $errors->first("app_name") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_description')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_description", __('admincrud.App Description'), ['class' => 'required']) }}
                            {{ Form::textarea('app_description', config('webconfig.app_description'), ['class' => 'form-control','placeholder' => __('admincrud.App Description') ]  ) }}
                            @if($errors->has("app_description"))
                                <span class="help-block error-help-block">{{ $errors->first("app_description") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_meta_keywords')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_meta_keywords", __('admincrud.App Meta Keywords'), ['class' => 'required']) }}
                            {{ Form::textarea('app_meta_keywords', config('webconfig.app_meta_keywords'), ['class' => 'form-control','placeholder' => __('admincrud.App Meta Keywords') ]  ) }}
                            @if($errors->has("app_meta_keywords"))
                                <span class="help-block error-help-block">{{ $errors->first("app_meta_keywords") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_meta_description')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_meta_description", __('admincrud.App Meta Description'), ['class' => 'required']) }}
                            {{ Form::textarea('app_meta_description', config('webconfig.app_meta_description'), ['class' => 'form-control','placeholder' => __('admincrud.App Meta Description') ]  ) }}
                            @if($errors->has("app_meta_description"))
                                <span class="help-block error-help-block">{{ $errors->first("app_meta_description") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('play_store_link')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("play_store_link", __('admincrud.App Playstore Link'), ['class' => 'required']) }}
                            {{ Form::text('play_store_link', config('webconfig.play_store_link'), ['class' => 'form-control','placeholder' => __('admincrud.App Playstore Link')]  ) }}
                            @if($errors->has("play_store_link"))
                                <span class="help-block error-help-block">{{ $errors->first("play_store_link") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_store_link')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_store_link", __('admincrud.App store Link'), ['class' => 'required']) }}
                            {{ Form::text('app_store_link', config('webconfig.app_store_link'), ['class' => 'form-control','placeholder' => __('admincrud.App store Link')]  ) }}
                            @if($errors->has("app_store_link"))
                                <span class="help-block error-help-block">{{ $errors->first("app_store_link") }}</span>
                            @endif 
                        </div>
                    </div>
                    
                     <div class="form-group {{ ($errors->has('app_address')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_address", __('admincrud.App Address'), ['class' => 'required']) }}
                            {{ Form::textarea('app_address', config('webconfig.app_address'), ['class' => 'form-control','placeholder' => __('admincrud.App Address') ]  ) }}
                            @if($errors->has("app_address"))
                                <span class="help-block error-help-block">{{ $errors->first("app_address") }}</span>
                            @endif 
                        </div>
                    </div>                                       
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ ($errors->has('app_logo')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_logo", __('admincrud.App Logo')."  (147 X 37)", ['class' => 'required']) }}
                            {{ Form::file('app_logo', ['class' => 'form-control','placeholder' => __('admincrud.App Logo')]  ) }}
                            @if($errors->has("app_logo"))
                                <span class="help-block error-help-block">{{ $errors->first("app_logo") }}</span>
                            @endif 
                            <ul class="uploads reset">
                                <li class="uploaded">                                
                                    <label for="upload1" style="background:url({{ FileHelper::loadImage(config('webconfig.app_logo')) }})"></label>                                
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_favicon')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_favicon", __('admincrud.App Favicon'), ['class' => 'required']) }}
                            {{ Form::file('app_favicon', ['class' => 'form-control','placeholder' => __('admincrud.App Favicon')]  ) }}
                            @if($errors->has("app_favicon"))
                                <span class="help-block error-help-block">{{ $errors->first("app_favicon") }}</span>
                            @endif 
                            <ul class="uploads reset">
                                <li class="uploaded">                                
                                    <label for="upload1" style="background:url({{ FileHelper::loadImage(config('webconfig.app_favicon')) }})"></label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_email')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_email", __('admincrud.App Email'), ['class' => 'required']) }}
                            {{ Form::text('app_email', config('webconfig.app_email'), ['class' => 'form-control','placeholder' => __('admincrud.App Email')]  ) }}
                            @if($errors->has("app_email"))
                                <span class="help-block error-help-block">{{ $errors->first("app_email") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_contact_number')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_contact_number", __('admincrud.App Contact Number'), ['class' => 'required']) }}
                            {{ Form::text('app_contact_number', config('webconfig.app_contact_number'), ['class' => 'form-control','placeholder' => __('admincrud.App Contact Number')]  ) }}
                            @if($errors->has("app_contact_number"))
                                <span class="help-block error-help-block">{{ $errors->first("app_contact_number") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_primary_color')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_primary_color", __('admincrud.App Primary Color'), ['class' => 'required ']) }}
                            {{ Form::text('app_primary_color', config('webconfig.app_primary_color'), ['class' => 'jscolor form-control','placeholder' => __('admincrud.App Primary Color')]  ) }}
                            @if($errors->has("app_primary_color"))
                                <span class="help-block error-help-block">{{ $errors->first("app_primary_color") }}</span>
                            @endif 
                        </div>
                    </div>    
                    <div class="form-group {{ ($errors->has('map_key')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("map_key", __('admincrud.App Map Key'), ['class' => 'required']) }}
                            {{ Form::text('map_key', config('webconfig.map_key'), ['class' => 'form-control','placeholder' => __('admincrud.App Map Key')]  ) }}
                            @if($errors->has("map_key"))
                                <span class="help-block error-help-block">{{ $errors->first("map_key") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_latitude')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_latitude", __('admincrud.App Default Latitude'), ['class' => 'required']) }}
                            {{ Form::text('app_latitude', config('webconfig.app_latitude'), ['class' => 'form-control','placeholder' => __('admincrud.App Default Latitude')]  ) }}
                            @if($errors->has("app_latitude"))
                                <span class="help-block error-help-block">{{ $errors->first("app_latitude") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('app_longitude')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("app_longitude", __('admincrud.App Default Longitude'), ['class' => 'required']) }}
                            {{ Form::text('app_longitude', config('webconfig.app_longitude'), ['class' => 'form-control','placeholder' => __('admincrud.App Default Longitude')]  ) }}
                            @if($errors->has("app_longitude"))
                                <span class="help-block error-help-block">{{ $errors->first("app_longitude") }}</span>
                            @endif 
                        </div>
                    </div>                                    
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Html::link(route('admin-app-settings'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
                {{ Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
            </div>
            <!-- /.box-footer -->
            {{ Form::close() }}
            {!! JsValidator::formRequest('App\Http\Requests\Admin\ConfigurationRequest', '#app-settings-form')  !!}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection