@extends('admin.layouts.layout')
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          @include('admin.layouts.partials._flashmsg')
            <h3 class="box-title">@lang('admincrud.Delivery boy Configuration')</h3>
          </div>                             
          @php  
            $currentUrlWithEnv = \Request::fullUrl();
            $currentUrl = \URL::current();
          @endphp
          {{ Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ]) }}          
          {{ Form::hidden('config_name',DELIVERY_BOY) }}
            <div class="box-body">                 
                <div class="form-group {{ ($errors->has('order_accept_time_limit')) ? 'has-error' : '' }}">
                    <div class="col-md-12">
                        {{ Form::label("order_accept_time_limit", __('admincrud.Order Accept Time Limit'), ['class' => 'required']) }}
                        {{ Form::text('order_accept_time_limit', config('webconfig.order_accept_time_limit'), ['class' => 'form-control','placeholder' => __('admincrud.Order Accept Time Limit') ]  ) }}
                        @if($errors->has("order_accept_time_limit"))
                            <span class="help-block error-help-block">{{ $errors->first("order_accept_time_limit") }}</span>
                        @endif 
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('request_radius')) ? 'has-error' : '' }}">
                    <div class="col-md-12">
                        {{ Form::label("request_radius", __('admincrud.Order Request Radius'), ['class' => 'required']) }}
                        {{ Form::text('request_radius', config('webconfig.request_radius'), ['class' => 'form-control','placeholder' => __('admincrud.Order Request Radius') ]  ) }}
                        @if($errors->has("request_radius"))
                            <span class="help-block error-help-block">{{ $errors->first("request_radius") }}</span>
                        @endif 
                    </div>
                </div> 
                <div class="form-group {{ ($errors->has('order_assign_type')) ? 'has-error' : '' }}">
                    <div class="col-md-12">
                        {{ Form::label("order_assign_type", __('admincrud.Order Assign Type'), ['class' => 'required']) }}
                        {{ Form::select('order_assign_type', [ORDER_ASSIGN_TYPE_AUTOMATIC => 'Automatic', ORDER_ASSIGN_TYPE_MANUAL => 'Manual'], config('webconfig.order_assign_type'), ['class' => 'form-control','placeholder' => __('admincrud.Order Assign Type')]  ) }}
                        @if($errors->has("order_assign_type"))
                            <span class="help-block error-help-block">{{ $errors->first("order_assign_type") }}</span>
                        @endif 
                    </div>
                </div>
                @if($currentUrlWithEnv === $currentUrl.'?env=dev')  
                <div class="form-group {{ ($errors->has('deliveryboy_url')) ? 'has-error' : '' }}">
                    <div class="col-md-12">
                        {{ Form::label("deliveryboy_url", __('admincrud.Delivery boy URL'), ['class' => 'required']) }}
                        {{ Form::text('deliveryboy_url',  config('webconfig.deliveryboy_url') , ['class' => 'form-control','placeholder' => __('admincrud.Delivery boy URL')]  ) }}
                        @if($errors->has("deliveryboy_url"))
                            <span class="help-block error-help-block">{{ $errors->first("deliveryboy_url") }}</span>
                        @endif 
                    </div>
                </div>
                <div class="form-group {{ ($errors->has('company_id')) ? 'has-error' : '' }}">
                    <div class="col-md-12">
                        {{ Form::label("company_id", __('admincrud.Company ID'), ['class' => 'required']) }}
                        {{ Form::text('company_id',  config('webconfig.company_id') , ['class' => 'form-control','placeholder' => __('admincrud.Company ID') ]  ) }}
                        @if($errors->has("company_id"))
                            <span class="help-block error-help-block">{{ $errors->first("company_id") }}</span>
                        @endif 
                    </div>
                </div>                                                        
                <div class="form-group {{ ($errors->has('auth_token')) ? 'has-error' : '' }}">
                    <div class="col-md-12">
                        {{ Form::label("auth_token", __('admincrud.Delivery boy Auth Token'), ['class' => 'required']) }}
                        {{ Form::textarea('auth_token',  config('webconfig.auth_token') , ['class' => 'form-control','placeholder' => __('admincrud.Delivery boy Auth Token') ]  ) }}
                        @if($errors->has("auth_token"))
                            <span class="help-block error-help-block">{{ $errors->first("auth_token") }}</span>
                        @endif 
                    </div>
                </div>   
                @endif                          
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Html::link(route('admin-app-settings'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
                {{ Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
            </div>
            <!-- /.box-footer -->
            {{ Form::close() }}
            {!! JsValidator::formRequest('App\Http\Requests\Admin\DeliveryboySettingRequest', '#app-settings-form')  !!}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection