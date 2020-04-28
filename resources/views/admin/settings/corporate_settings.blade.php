@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-3 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          @include('admin.layouts.partials._flashmsg')
            <h3 class="box-title">@lang('admincrud.Corporate Voucher Terms')</h3>                        
          </div>            
            {{ Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ]) }}
            {{ Form::hidden('config_name',CONFIG_CORPORATE) }}            
            <div class="box-body"> 
                <div class="col-md-12">
                    <div class="form-group {{ ($errors->has('corporate_description')) ? 'has-error' : '' }}">
                        <div class="col-md-12">
                            {{ Form::label("corporate_description", __('admincrud.Corporate Voucher Terms'), ['class' => 'required']) }}
                            {{ Form::textarea('corporate_description', config('webconfig.corporate_description'), ['class' => 'form-control','placeholder' => __('admincrud.Corporate Voucher Terms') ]  ) }}
                            @if($errors->has("corporate_description"))
                                <span class="help-block error-help-block">{{ $errors->first("corporate_description") }}</span>
                            @endif 
                        </div>
                    </div>
                </div>                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Html::link(route('admin-corporate-settings'), __('admincommon.Cancel'), ['class' => 'btn btn-default']) }}        
                {{ Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
            </div>
            <!-- /.box-footer -->
            {{ Form::close() }}
            {!! JsValidator::formRequest('App\Http\Requests\Admin\CorporateConfigRequest', '#app-settings-form')  !!}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection