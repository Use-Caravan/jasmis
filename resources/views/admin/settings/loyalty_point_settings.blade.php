@extends('admin.layouts.layout')
@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
          @include('admin.layouts.partials._flashmsg')
            <h3 class="box-title">@lang('admincrud.Loyalty Redeem Configuration')</h3>                        
          </div>            
            {{ Form::open(['url' => route('admin-settings-save'), 'id' => 'app-settings-form', 'class' => 'form-horizontal', 'method' => 'POST', 'enctype' => 'multipart/form-data' ]) }}
            {{ Form::hidden('config_name',CONFIG_LOYALTY_POINT) }}
            <div class="box-body"> 
                <div class="col-md-12">
                    {{--<h3>@lang('admincrud.Order Point Settings')</h3>
                    <div class="col-md-6">
                        <div class="form-group {{ ($errors->has('loyalty_amount')) ? 'has-error' : '' }}">                        
                            {{ Form::label("loyalty_amount", __('admincrud.Order Amount'), ['class' => 'required']) }}
                            {{ Form::text('loyalty_amount', config('webconfig.loyalty_amount'), ['class' => 'form-control','placeholder' => __('admincrud.Loyalty Amount') ]  ) }}
                            @if($errors->has("loyalty_amount"))
                                <span class="help-block error-help-block">{{ $errors->first("loyalty_amount") }}</span>
                            @endif 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ ($errors->has('loyalty_point_for_amount')) ? 'has-error' : '' }}">                        
                            {{ Form::label("loyalty_point_for_amount", __('admincrud.Loyalty Point for Amount'), ['class' => 'required']) }}
                            {{ Form::text('loyalty_point_for_amount', config('webconfig.loyalty_point_for_amount'), ['class' => 'form-control','placeholder' => __('admincrud.Loyalty Point for Amount')]  ) }}
                            @if($errors->has("loyalty_point_for_amount"))
                                <span class="help-block error-help-block">{{ $errors->first("loyalty_point_for_amount") }}</span>
                            @endif 
                        </div>
                    </div> 
                    <h3>@lang('admincrud.Redeem Point Settings')</h3>--}}
                    <div class="col-md-6">
                        <div class="form-group {{ ($errors->has('loyalty_points')) ? 'has-error' : '' }}">                        
                            {{ Form::label("loyalty_points", __('admincrud.Loyalty Points'), ['class' => 'required']) }}
                            {{ Form::text('loyalty_points', config('webconfig.loyalty_points'), ['class' => 'form-control','placeholder' => __('admincrud.Loyalty Points')]  ) }}
                            @if($errors->has("loyalty_points"))
                                <span class="help-block error-help-block">{{ $errors->first("loyalty_points") }}</span>
                            @endif 
                        </div>
                    </div>                
                    <div class="col-md-6">
                        <div class="form-group {{ ($errors->has('loyalty_amount_for_points')) ? 'has-error' : '' }}">                        
                            {{ Form::label("loyalty_amount_for_points", __('admincrud.Redeem Amount For Points'), ['class' => 'required']) }}
                            {{ Form::text('loyalty_amount_for_points', config('webconfig.loyalty_amount_for_points'), ['class' => 'form-control','placeholder' => __('admincrud.Loyalty Amount For Points')]  ) }}
                            @if($errors->has("loyalty_amount_for_points"))
                                <span class="help-block error-help-block">{{ $errors->first("loyalty_amount_for_points") }}</span>
                            @endif 
                        </div>
                    </div>                    
                </div>                
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ Html::link(route('admin-loyalty-point-settings'), __('admincommon.Cancel'), ['class' => 'btn btn-default']) }}        
                {{ Form::submit(__('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
            </div>
            <!-- /.box-footer -->
            {{ Form::close() }}
            {!! JsValidator::formRequest('App\Http\Requests\Admin\LoyaltyPointConfigRequest', '#app-settings-form')  !!}
        </div>
      </div>
    </div>
  </section>
</div>
@endsection