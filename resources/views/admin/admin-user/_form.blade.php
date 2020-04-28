    {{ Form::open(['url' => $url, 'id' => 'adminuser-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">

        {{-- <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif> <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a></li>
            @endforeach
        </ul>
        <div class="tab-content full_row">
            @foreach ($languages as $key => $language)
            <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                <div class="col-sm-12">
                    <div class="form-group {{ ($errors->has("first_name.$key")) ? 'has-error' : '' }}"> 
                                       
                        {{ Form::label("first_name[$key]", __('admincommon.First Name'), ['class' => 'required']) }}
                        {{ Form::text("first_name[$key]", $modelLang['first_name'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("first_name.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("first_name.$key") }}</span>
                        @endif                    
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group {{ ($errors->has("last_name.$key")) ? 'has-error' : '' }}"> 
                                       
                        {{ Form::label("last_name[$key]", __('admincommon.Last Name'), ['class' => 'required']) }}
                        {{ Form::text("last_name[$key]", $modelLang['last_name'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("last_name.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("last_name.$key") }}</span>
                        @endif                    
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group {{ ($errors->has("address.$key")) ? 'has-error' : '' }}" >
                        {{ Form::label("address[$key]", __('admincommon.Address'), ['class' => 'required']) }}
                        {{ Form::textarea("address[$key]", $modelLang['address'][$key], ['class' => 'form-control']) }} 
                        @if($errors->has("address.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("address.$key") }}</span>
                        @endif 
                    </div>                      
                </div>
            </div> <!--tab-pane-->
            @endforeach
            </div> --}}


                {{-- <div class="col-md-6"> 
                    <div class="form-group {{ ($errors->has("user_type")) ? 'has-error' : '' }}">
                    {{ Form::label("user_type", __('admincrud.User Type'), ['class' => 'required']) }} 
                    {{ Form::select('user_type', $model->userTypes(), $model->user_type ,['class' => 'selectpicker','id' => 'user_type','placeholder' => 'Please Choose User Type.'] )}}
                        @if($errors->has("user_type"))
                            <span class="help-block error-help-block">{{ $errors->first("user_type") }}</span>
                        @endif 
                    </div>
                </div> --}}
                
                <div class="col-sm-6">
                    <div class="form-group {{ ($errors->has("username")) ? 'has-error' : '' }}"> 
                        {{ Form::label("username", __('admincommon.User Name'), ['class' => 'required']) }}
                        {{ Form::text("username", $model['username'], ['class' => 'form-control']) }}
                        @if($errors->has("username"))
                            <span class="help-block error-help-block">{{ $errors->first("username") }}</span>
                        @endif                    
                    </div>
                </div>                
                <div class="col-sm-6">
                    <div class="form-group {{ ($errors->has("email")) ? 'has-error' : '' }}"> 
                                        
                        {{ Form::label("email", __('admincommon.Email'), ['class' => 'required']) }}
                        {{ Form::text("email", $model['email'], ['class' => 'form-control']) }}
                        @if($errors->has("email"))
                            <span class="help-block error-help-block">{{ $errors->first("email") }}</span>
                        @endif                    
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group {{ ($errors->has("phone_number")) ? 'has-error' : '' }}"> 
                                        
                        {{ Form::label("phone_number", __('admincommon.Phone Number'), ['class' => 'required']) }}
                        {{ Form::text("phone_number", $model['phone_number'], ['class' => 'form-control']) }}
                        @if($errors->has("phone_number"))
                            <span class="help-block error-help-block">{{ $errors->first("phone_number") }}</span>
                        @endif                    
                    </div>
                </div>              
                @if( (auth()->guard(APP_GUARD)->user()->user_type == ADMIN && $model->user_type == SUB_ADMIN)  || (!$model->exists) )
                <div class="col-md-6">
                    <div class="form-group {{ ($errors->has("role_id")) ? 'has-error' : '' }}">
                    {{ Form::label("role_id", __('admincrud.Role Name'), ['class' => 'required']) }} 
                    {{ Form::select('role_id', $roleName, $model->role_id ,['class' => 'selectpicker','placeholder' => 'Please Choose Role Name.'] )}}
                        @if($errors->has("role_id"))
                            <span class="help-block error-help-block">{{ $errors->first("role_id") }}</span>
                        @endif 
                    </div>
                </div>
                @endif  
                <div class="col-sm-6">
                    <div class="form-group {{ ($errors->has("password")) ? 'has-error' : '' }}"> 
                                        
                        {{ Form::label("password", __('admincommon.Password'), ['class' => (!$model->exists) ? 'required' : '']) }}
                        {{ Form::password("password", ['class' => 'form-control']) }}
                        @if($errors->has("password"))
                            <span class="help-block error-help-block">{{ $errors->first("password") }}</span>
                        @endif                    
                    </div>
                </div>
                
                <div class="col-md-6">  
                    <div class="form-group {{ ($errors->has("confirm_password")) ? 'has-error' : '' }}">                    
                        {{ Form::label("confirm_password", __('admincommon.Confirm Password'), ['class' => (!$model->exists) ? 'required' : '']) }}
                        {{ Form::password("confirm_password", ['class' => 'form-control']) }} 
                        @if($errors->has("confirm_password"))
                            <span class="help-block error-help-block">{{ $errors->first("confirm_password") }}</span>
                        @endif                    
                    </div>
                </div>
                @if($model->user_type == SUB_ADMIN)
                <div class="col-md-6">
                    <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">                                         
                        {{ Form::label("status", __('admincommon.Status'),['class' => 'required']) }}
                        @php $model->status = ($model->exists) ? $model->status : ITEM_ACTIVE @endphp
                        {{ Form::radio('status', ITEM_ACTIVE, ($model->status == ITEM_ACTIVE), ['class' => 'hide','id'=> 'statuson' ]) }}
                        {{ Form::label("statuson", __('admincommon.Active'), ['class' => 'radio']) }}
                        {{ Form::radio('status', ITEM_INACTIVE, ($model->status == ITEM_INACTIVE), ['class' => 'hide','id'=>'statusoff']) }}
                        {{ Form::label("statusoff", __('admincommon.Inactive'), ['class' => 'radio']) }}
                        @if($errors->has("status"))
                            <span class="help-block error-help-block">{{ $errors->first("status") }}</span>
                        @endif                    
                    </div>
                </div> 
                @endif
            </div>
        <!-- /.box-body -->
            <div class="box-footer">
                {{ Html::link(route('admin-user.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
                {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
            </div>
        <!-- /.box-footer -->
        {{ Form::close() }}

        {!! JsValidator::formRequest('App\Http\Requests\Admin\AdminUserRequest', '#adminuser-form')  !!}

        <script>
        $(document).ready( function() {
            $('#user_type').change( function() {
            $value = $(this).val();
                if ($value == {{ SUB_ADMIN }}) {
                    $('#role_id').removeClass('hide');
                }
                else {
                    $('#role_id').addClass('hide');
                } 
            });
        });
        </script>