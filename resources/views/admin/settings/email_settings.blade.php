@extends('admin.layouts.layout')
@section('content')


<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          @include('admin.layouts.partials._flashmsg')
            <h3 class="box-title">@lang('admincrud.Mail Configuration')</h3>            
            {{ Html::link(route('admin-test-mail'), __('admincrud.Send Test Mail'),['class' => 'btn btn-warning']) }}        
          </div>            
            {{ Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ]) }}
            {{ Form::hidden('config_name',CONFIG_MAIL) }}
            <div class="box-body"> 
                <div class="col-md-12">
                    <div class="form-group {{ ($errors->has('smtp_host')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("smtp_host", __('admincrud.SMTP Host'), ['class' => 'required']) }}
                            {{ Form::text('smtp_host', config('webconfig.smtp_host'), ['class' => 'form-control','placeholder' => __('admincrud.SMTP Host') ]  ) }}
                            @if($errors->has("smtp_host"))
                                <span class="help-block error-help-block">{{ $errors->first("smtp_host") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('smtp_username')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("smtp_username", __('admincrud.SMTP Username'), ['class' => 'required']) }}
                            {{ Form::text('smtp_username', config('webconfig.smtp_username'), ['class' => 'form-control','placeholder' => __('admincrud.SMTP Username')]  ) }}
                            @if($errors->has("smtp_username"))
                                <span class="help-block error-help-block">{{ $errors->first("smtp_username") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('smtp_password')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("smtp_password", __('admincrud.SMTP Password'), ['class' => 'required']) }}
                            {{ Form::text('smtp_password', config('webconfig.smtp_password'), ['class' => 'form-control','placeholder' => __('admincrud.SMTP Password')]  ) }}
                            @if($errors->has("smtp_password"))
                                <span class="help-block error-help-block">{{ $errors->first("smtp_password") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('encryption')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("encryption", __('admincrud.Encryption Method'), ['class' => 'required']) }}
                            {{ Form::select('encryption', $model->encryptionTypes(), config('webconfig.encryption'), ['class' => 'form-control','placeholder' => __('admincrud.Encryption Method')]  ) }}
                            @if($errors->has("encryption"))
                                <span class="help-block error-help-block">{{ $errors->first("encryption") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('port')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("port", __('admincrud.Mail Port'), ['class' => 'required']) }}
                            {{ Form::text('port', config('webconfig.port'), ['class' => 'form-control','placeholder' => __('admincrud.Mail Port')]  ) }}
                            @if($errors->has("port"))
                                <span class="help-block error-help-block">{{ $errors->first("port") }}</span>
                            @endif 
                        </div>
                    </div>    
                    <div class="form-group radio_group">
                        <div class="col-md-12">                            
                            {{ Form::label("port", __('admincrud.Is SMTP Enabled'), ['class' => 'required']) }}
                            {{Form::radio('is_smtp_enabled', 1, (config('webconfig.is_smtp_enabled') == 1), ['class' => 'hide', 'id' => 'port-Yes'])}}                            
                            {{ Form::label("port-Yes", __('admincommon.Yes'), ['class' => 'radio']) }}
                            {{Form::radio('is_smtp_enabled', 2, (config('webconfig.is_smtp_enabled') == 2), ['class' => 'hide', 'id' => 'port-No'])}}                            
                            {{ Form::label("port-No", __('admincommon.No'), ['class' => 'radio']) }}
                        </div>
                    </div> <!--form_group-->                
                </div>                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Html::link(route('admin-mail-settings'), __('admincommon.Cancel'), ['class' => 'btn btn-default']) }}        
                {{ Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
            </div>
            <!-- /.box-footer -->
            {{ Form::close() }}
            {!! JsValidator::formRequest('App\Http\Requests\Admin\MailConfigRequest', '#app-settings-form')  !!}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection