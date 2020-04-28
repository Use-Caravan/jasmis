@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          @include('admin.layouts.partials._flashmsg')
            <h3 class="box-title">@lang('admincrud.Social Media Configuration')</h3>                        
          </div>            
            {{ Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ]) }}
            {{ Form::hidden('config_name',CONFIG_SOCIAL_MEDIA) }}
            <div class="box-body"> 
                <div class="col-md-12">
                    <div class="form-group {{ ($errors->has('social_twitter')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("social_twitter", __('admincrud.Twitter'), ['class' => 'required']) }}
                            {{ Form::text('social_twitter', config('webconfig.social_twitter'), ['class' => 'form-control','placeholder' => __('admincrud.Twitter') ]  ) }}
                            @if($errors->has("social_twitter"))
                                <span class="help-block error-help-block">{{ $errors->first("social_twitter") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('social_facebook')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("social_facebook", __('admincrud.Facebook'), ['class' => 'required']) }}
                            {{ Form::text('social_facebook', config('webconfig.social_facebook'), ['class' => 'form-control','placeholder' => __('admincrud.Facebook')]  ) }}
                            @if($errors->has("social_facebook"))
                                <span class="help-block error-help-block">{{ $errors->first("social_facebook") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('social_instagram')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("social_instagram", __('admincrud.Instagram'), ['class' => 'required']) }}
                            {{ Form::text('social_instagram', config('webconfig.social_instagram'), ['class' => 'form-control','placeholder' => __('admincrud.Instagram')]  ) }}
                            @if($errors->has("social_instagram"))
                                <span class="help-block error-help-block">{{ $errors->first("social_instagram") }}</span>
                            @endif
                        </div>
                    </div>
                    {{--
                    <div class="form-group {{ ($errors->has('social_google')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("social_google", __('admincrud.Google+'), ['class' => 'required']) }}
                            {{ Form::text('social_google', config('webconfig.social_google'), ['class' => 'form-control','placeholder' => __('admincrud.Google+')]  ) }}
                            @if($errors->has("social_google"))
                                <span class="help-block error-help-block">{{ $errors->first("social_google") }}</span>
                            @endif 
                        </div>
                    </div>
                    --}}
                </div>                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Html::link(route('admin-social-media-settings'), __('admincommon.Cancel'), ['class' => 'btn btn-default']) }}        
                {{ Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
            </div>
            <!-- /.box-footer -->
            {{ Form::close() }}
            {!! JsValidator::formRequest('App\Http\Requests\Admin\SocialMediaConfigRequest', '#app-settings-form')  !!}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection