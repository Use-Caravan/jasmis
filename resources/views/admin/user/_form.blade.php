{{ Form::open(['url' => $url, 'id' => 'user-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">
        <div class="form-group {{ ($errors->has("first_name")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("first_name", __('admincommon.First Name'),['class' => 'required']) }}
                {{ Form::text("first_name", $model->first_name, ['class' => 'form-control']) }}
                @if($errors->has("first_name"))
                    <span class="help-block error-help-block">{{ $errors->first("first_name") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("last_name")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("last_name", __('admincommon.Last Name'),['class' => 'required']) }}
                {{ Form::text("last_name", $model->last_name, ['class' => 'form-control']) }}
                @if($errors->has("last_name"))
                    <span class="help-block error-help-block">{{ $errors->first("last_name") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("username")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("username", __('admincommon.User Name'),['class' => 'required']) }}
                {{ Form::text("username", $model->username, ['class' => 'form-control']) }}
                @if($errors->has("username"))
                    <span class="help-block error-help-block">{{ $errors->first("username") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("email")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("email", __('admincommon.Email'),['class' => 'required']) }}
                {{ Form::text("email", $model->email, ['class' => 'form-control']) }}
                @if($errors->has("email"))
                    <span class="help-block error-help-block">{{ $errors->first("email") }}</span>
                @endif                    
            </div>
        </div>
        <div class="col-md-12">  
            <div class="form-group {{ ($errors->has("password")) ? 'has-error' : '' }}">                    
                {{ Form::label("password", __('admincommon.Password'), ($model->exists)?'':['class' => 'required']) }}
                {{ Form::password("password", ['class' => 'form-control']) }} 
                @if($errors->has("password"))
                    <span class="help-block error-help-block">{{ $errors->first("password") }}</span>
                @endif                    
            </div>
        </div> 
         <div class="col-md-12">  
            <div class="form-group {{ ($errors->has("confirm_password")) ? 'has-error' : '' }}">                    
                {{ Form::label("confirm_password", __('admincommon.Confirm Password'), ($model->exists)?'':['class' => 'required']) }}
                {{ Form::password("confirm_password", ['class' => 'form-control']) }} 
                @if($errors->has("confirm_password"))
                    <span class="help-block error-help-block">{{ $errors->first("confirm_password") }}</span>
                @endif                    
            </div>
        </div> 
        <div class="form-group {{ ($errors->has("phone_number")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
                {{ Form::label("phone_number", __('admincrud.Phone Number'),['class' => 'required']) }}
                {{ Form::text("phone_number", $model->phone_number, ['class' => 'form-control']) }}
                @if($errors->has("phone_number"))
                    <span class="help-block error-help-block">{{ $errors->first("phone_number") }}</span>
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
        {{ Html::link(route('user.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\UserRequest', '#user-form')  !!}