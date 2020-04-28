@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          @include('admin.layouts.partials._flashmsg')
            <h3 class="box-title">@lang('admincrud.Currency Configuration')</h3>                        
          </div>            
            {{ Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ]) }}
            {{ Form::hidden('config_name',CONFIG_CURRENCY) }}            
            <div class="box-body"> 
                <div class="col-md-12">
                    <div class="form-group {{ ($errors->has('currency_code')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("currency_code", __('admincrud.Currency Code'), ['class' => 'required']) }}
                            {{ Form::text('currency_code', config('webconfig.currency_code'), ['class' => 'form-control','placeholder' => __('admincrud.Currency Code') ]  ) }}
                            @if($errors->has("currency_code"))
                                <span class="help-block error-help-block">{{ $errors->first("currency_code") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group {{ ($errors->has('currency_symbol')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("currency_symbol", __('admincrud.Currency Symbol'), ['class' => 'required']) }}
                            {{ Form::text('currency_symbol', config('webconfig.currency_symbol'), ['class' => 'form-control','placeholder' => __('admincrud.Currency Symbol')]  ) }}
                            @if($errors->has("currency_symbol"))
                                <span class="help-block error-help-block">{{ $errors->first("currency_symbol") }}</span>
                            @endif 
                        </div>
                    </div>                    
                    <div class="form-group {{ ($errors->has('currency_position')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("currency_position", __('admincrud.Currency Position'), ['class' => 'required']) }}
                            {{ Form::select('currency_position', $model->currencyPositions(),config('webconfig.currency_position'), ['class' => 'form-control']  ) }}
                            @if($errors->has("currency_position"))
                                <span class="help-block error-help-block">{{ $errors->first("currency_position") }}</span>
                            @endif 
                        </div>
                    </div>                    
                </div>                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Html::link(route('admin-currency-settings'), __('admincommon.Cancel'), ['class' => 'btn btn-default']) }}        
                {{ Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
            </div>
            <!-- /.box-footer -->
            {{ Form::close() }}
            {!! JsValidator::formRequest('App\Http\Requests\Admin\CurrencyConfigRequest', '#app-settings-form')  !!}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection