@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          @include('admin.layouts.partials._flashmsg')
            <h3 class="box-title">@lang('admincrud.SMS Configuration')</h3>
            {{ Html::link(route('admin-test-sms'), __('admincrud.Send Test SMS'),['class' => 'btn btn-warning']) }}                        
          </div>            
            {{ Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ]) }}
            {{ Form::hidden('config_name',CONFIG_SMS) }}
            <div class="box-body"> 
                <div class="col-md-12">
                    <div class="form-group {{ ($errors->has('sms_gateway_username')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("sms_gateway_username", __('admincrud.SMS Gateway Username'), ['class' => 'required']) }}
                            {{ Form::text('sms_gateway_username', config('webconfig.sms_gateway_username'), ['class' => 'form-control','placeholder' => __('admincrud.SMS Gateway Username') ]  ) }}
                            @if($errors->has("sms_gateway_username"))
                                <span class="help-block error-help-block">{{ $errors->first("sms_gateway_username") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('sms_gateway_password')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("sms_gateway_password", __('admincrud.SMS Gateway Password'), ['class' => 'required']) }}
                            {{ Form::text('sms_gateway_password', config('webconfig.sms_gateway_password'), ['class' => 'form-control','placeholder' => __('admincrud.SMS Gateway Password')]  ) }}
                            @if($errors->has("sms_gateway_password"))
                                <span class="help-block error-help-block">{{ $errors->first("sms_gateway_password") }}</span>
                            @endif 
                        </div>
                    </div>                    
                    <div class="form-group {{ ($errors->has('sms_sender_id')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("sms_sender_id", __('admincrud.SMS Sender ID'), ['class' => 'required']) }}
                            {{ Form::text('sms_sender_id', config('webconfig.sms_sender_id'), ['class' => 'form-control','placeholder' => __('admincrud.SMS Sender ID')]  ) }}
                            @if($errors->has("sms_sender_id"))
                                <span class="help-block error-help-block">{{ $errors->first("sms_sender_id") }}</span>
                            @endif 
                        </div>
                    </div>
                </div>                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Html::link(route('admin-sms-settings'), __('admincommon.Cancel'), ['class' => 'btn btn-default']) }}        
                {{ Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
            </div>
            <!-- /.box-footer -->
            {{ Form::close() }}
            {!! JsValidator::formRequest('App\Http\Requests\Admin\SMSConfigRequest', '#app-settings-form')  !!}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection