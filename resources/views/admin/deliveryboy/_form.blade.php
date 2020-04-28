{{ Form::open(['url' => $url, 'id' => 'deliveryboy-form', 'class' => 'form-horizontal', 'method' => $method ]) }}
    <div class="box-body">        
        <div class="col-sm-6">
            <div class="form-group {{ ($errors->has("name")) ? 'has-error' : '' }}"> 
                {{ Form::label("name", __('admincommon.User Name'), ['class' => 'required']) }}
                {{ Form::text("name", $model->name, ['class' => 'form-control']) }}
                @if($errors->has("name"))
                    <span class="help-block error-help-block">{{ $errors->first("name") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group {{ ($errors->has("email")) ? 'has-error' : '' }}"> 
                                
                {{ Form::label("email", __('admincommon.Email'), ['class' => 'required']) }}
                {{ Form::text("email", $model->email, ['class' => 'form-control']) }}
                @if($errors->has("email"))
                    <span class="help-block error-help-block">{{ $errors->first("email") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group {{ ($errors->has("phone_number")) ? 'has-error' : '' }}"> 
                                
                {{ Form::label("phone_number", __('admincommon.Mobile Number'), ['class' => 'required']) }}
                {{ Form::text("phone_number", $model->phone_number, ['class' => 'form-control']) }}
                @if($errors->has("phone_number"))
                    <span class="help-block error-help-block">{{ $errors->first("phone_number") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group {{ ($errors->has("country")) ? 'has-error' : '' }}">                                 
                {{ Form::label("country", __('admincrud.Country Name'), ['class' => 'required']) }}
                {{ Form::text("country", $model->country, ['class' => 'form-control']) }}
                @if($errors->has("country"))
                    <span class="help-block error-help-block">{{ $errors->first("country") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group {{ ($errors->has("city")) ? 'has-error' : '' }}">                                 
                {{ Form::label("city", __('admincrud.City Name'), ['class' => 'required']) }}
                {{ Form::text("city", $model->city, ['class' => 'form-control']) }}
                @if($errors->has("city"))
                    <span class="help-block error-help-block">{{ $errors->first("city") }}</span>
                @endif                    
            </div>
        </div> 
        <div class="col-sm-6">
            <div class="form-group {{ ($errors->has("address")) ? 'has-error' : '' }}">                                 
                {{ Form::label("address", __('admincommon.Address'), ['class' => 'required']) }}
                {{ Form::textarea("address", $model->address, ['class' => 'form-control']) }}
                @if($errors->has("address"))
                    <span class="help-block error-help-block">{{ $errors->first("address") }}</span>
                @endif                    
            </div>
        </div>
        @if($model->_id == null)
        <div class="col-sm-6">
            <div class="form-group {{ ($errors->has("password")) ? 'has-error' : '' }}">                                 
                {{ Form::label("password", __('admincommon.Password'), ['class' => 'required']) }}
                {{ Form::password("password", ['class' => 'form-control']) }}
                @if($errors->has("password"))
                    <span class="help-block error-help-block">{{ $errors->first("password") }}</span>
                @endif                    
            </div>
        </div>
        @endif
        @if($model->_id == null)
        <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("confirm_password")) ? 'has-error' : '' }}">                    
                {{ Form::label("confirm_password", __('admincommon.Confirm Password'), ['class' => 'required']) }}
                {{ Form::password("confirm_password", ['class' => 'form-control']) }} 
                @if($errors->has("confirm_password"))
                    <span class="help-block error-help-block">{{ $errors->first("confirm_password") }}</span>
                @endif                    
            </div>
        </div>
        @endif
       {{--  <div class="clearfix"></div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("approved_status")) ? 'has-error' : '' }}">
            {{ Form::label("approved_status", __('admincrud.Approved Status'),['class' => 'required']) }}
            @php $model->approved_status = ($model->exists) ? $model->approved_status : DELIVERY_BOY_APPROVED_STATUS_PENDING  @endphp
                @foreach($model->approvedStatus() as $key => $value)
                    {{ Form::radio('approved_status', $key , ($model->approved_status == $key ), ['class' => 'hide', 'id' => "approved_status_pending$key"]) }}
                    {{ Form::label("approved_status_pending$key", $value, ['class' => 'radio']) }} 
                @endforeach            
                @if($errors->has("approved_status"))
                    <span class="help-block error-help-block">{{ $errors->first("approved_status") }}</span>
                @endif 
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">                    
            <div class="col-md-6"> 
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
        </div>   --}}
    </div>
  <!-- /.box-body -->
    <div class="box-footer">
        {{ Html::link(route('deliveryboy.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($method == 'PUT' ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\DeliveryboyRequest', '#deliveryboy-form')  !!}