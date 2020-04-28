<!-- login modal -->

    <div class="modal login_modal fade" id="login_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h5 class="modal-title">{{__('Login')}}</h5>
                    <div class="icons-add"><img src="{{ asset(FRONT_END_BASE_PATH.'img/icon1.png') }}"></div>
                </div>
                <div class="modal-body">

                    <div class="form-box floating_label">
                        {{ Form::open(['route' => 'frontend.signin', 'id' => 'login-form', 'class' => 'form-horizontal signinDetails', 'method' => 'POST']) }}     
                         <div class="form-group ">
                                {{ Form::label("username", __('Email'), ['class' => 'required']) }}
                                {{ Form::text("username",'', ['class' => 'form-control','id' => "username",'maxlength'=>'100']) }} 
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                {{ Form::label("password", __('Password'), ['class' => 'required']) }}
                                {{ Form::password("password", ['class' => 'form-control','id' => "password",'maxlength'=>'15']) }} 
                            </div>
                            
                             {!! Html::decode( Html::link('#forgot-modal', __('Forgot?'),['class' => 'forgot','data-toggle' => 'modal','data-dismiss' => 'modal', 'data-target' => '#forgot-modal']))   !!} 
                        </div>
                        <div class="text-right">
                            <button class="shape-btn loader shape1"><span class="shape">{{__('Submit')}}</span></button>
                        </div>
                        {{ form::close() }}
                      {{--  {!! JsValidator::formRequest('App\Http\Requests\Frontend\LoginRequest', '#login-form')  !!} --}}
                    </div>

                    <div class="or text-center">{{__('(OR)')}}</div>

                    <div class="text-center connect-social">
                        <a href="{{route('frontend.facebook-login')}}" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="{{route('frontend.google-login')}}" class="gmail">&nbsp;</a>
                    </div>

                    <p class="switch-modal f18 text-center">{{__('Don’t have an account?')}} <a href="#sign-up" data-toggle="modal" data-target="#sign-up" data-dismiss="modal"> {{__('Sign Up')}}</a></p>

                </div>
            </div>
        </div>
    </div>

    <!-- login modal -->

    <!-- Forgot Password modal -->

    <div class="modal login_modal fade" id="forgot-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h5 class="modal-title">{{__('Forgot Password')}}</h5>
                    <div class="icons-add"><img src="{{ asset(FRONT_END_BASE_PATH.'img/icon3.png') }}"></div>

                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => 'frontend.send-reset-link', 'id' => 'forgot-form', 'class' => 'form-horizontal forgot-password', 'method' => 'POST']) }} 
                    <div class="form-box floating_label">
                        <div class="form-group">
                            {{ Form::label("fpemail", __('Email'), ['class' => 'required' ]) }}
                            {{ Form::text("email",'', ['class' => 'form-control','id' => "fpemail"]) }}
                        </div>
                        <div class="text-right mb-5">
                            {!! Html::decode( Form::button('<span class="shape">Submit</span>', ['type'=>'button', 'class' => 'shape-btn loader shape1 forgot-submit']) ) !!}
                        </div>
                        {{ form::close() }}
                        {!! JsValidator::formRequest('App\Http\Requests\Frontend\ForgotPasswordRequest', '#forgot-form')  !!}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password modal -->

        <!-- Signup modal -->

    <div class="modal login_modal fade" id="sign-up">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h5 class="modal-title">{{__('Sign Up')}}</h5>
                    <div class="icons-add"><img src="{{ asset(FRONT_END_BASE_PATH.'img/icon2.png') }}"></div>

                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => 'frontend.signup', 'id' => 'register-form', 'class' => 'form-horizontal signupDetails', 'method' => 'POST']) }}     
                        <div class="form-box floating_label">
                            
                            <div class="form-group ">
                                {{ Form::label("sfirst-name", __('First Name'), ['class' => 'required' ]) }}
                                {{ Form::text("first_name",'', ['class' => 'form-control','id' => "sfirst-name",'maxlength'=>'100']) }} 
                               
                            </div>
                            <div class="form-group ">
                                {{ Form::label("slast-name", __('Last Name'), ['class' => 'required' ]) }}
                                {{ Form::text("last_name",'', ['class' => 'form-control','id' => "slast-name",'maxlength'=>'50']) }}
                                  
                            </div>
                            <div class="form-group ">
                                {{ Form::label("ssign-up-email", __('Email'), ['class' => 'required' ]) }}
                                {{ Form::text("email",'', ['class' => 'form-control','id' => "ssign-up-email",'maxlength'=>'100']) }} 
                                
                            </div>
                            <div class="form-group">
                                {{ Form::label("sphone_number", __('Phone Number'), ['class' => 'required' ]) }}
                                {{ Form::text("phone_number",'', ['class' => 'form-control','id' => "sphone_number",'maxlength'=>'15']) }} 
                               
                            </div>

                             <div class="form-group">
                                {{ Form::label("spassword", __('Password'), ['class' => 'required' ]) }}
                                {{ Form::password("password", ['class' => 'form-control','id' => "spassword",'maxlength'=>'20']) }} 
                               
                            </div>

                            <div class="form-group">
                                {{ Form::label("sconfirm-password", __('Confirm Password'), ['class' => 'required' ]) }}
                                {{ Form::password("confirm_password", ['class' => 'form-control','id' => "sconfirm-password",'maxlength'=>'20']) }} 
                               
                            </div>   
                            <div class="check-group mb-4">                                
                                {{ Form::checkbox('terms',1,null,[ 'id' => 'signup', "class" => "checkbox" ]) }}
                                @foreach($cms as $key => $value)
                                    @if($value->position == 4)
                                        {!! Html::decode( Form::label('signup', __('I Accept the').' '.Html::link('cms/'.$value->slug, __('Terms and Conditions'), ['']), ['class' => 'checkbox f18']) )  !!}                                
                                    @endif
                                @endforeach
                            </div>
                            <div class="text-right">
                                <button class="shape-btn loader shape1"><span class="shape">{{__('Submit')}}</span></button>
                            </div>
                        </div>
                    {{ Form::close() }}
                    {!! JsValidator::formRequest('App\Http\Requests\Frontend\RegisterRequest', '#register-form')  !!} 
                    <div class="or text-center">{{__('(OR)')}}</div>

                    <div class="text-center connect-social">
                        <a href="{{route('frontend.facebook-login')}}" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="{{route('frontend.google-login')}}" class="gmail">&nbsp;</a>
                    </div>

                    <p class="switch-modal f18 text-center">{{__('Already a Member?')}} <a href="#login_modal" data-toggle="modal" data-target="#login_modal" data-dismiss="modal"> {{__('Login')}}</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Signup modal -->

    <!-- OTP model -->
    <div class="modal otp_modal fade" id="otp-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h5 class="modal-title">{{__('OTP Verification')}}</h5>
                    <div class="icons-add"><img src="{{ asset(FRONT_END_BASE_PATH.'img/icon3.png') }}"></div>

                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => 'frontend.verify-otp', 'id' => 'otp-form', 'class' => 'form-horizontal', 'method' => 'POST']) }} 
                    <div class="form-box floating_label">
                        <div class="form-group">
                            {{ Form::label("otp", __('OTP'), ['class' => 'required' ]) }}
                            {{ Form::text("otp",'', ['class' => 'form-control','id' => "otp"]) }}
                            {{ Form::hidden('otp_temp_key','',['id' => 'otp_temp_key']) }} 
                            {{ Form::hidden('user_key','',['id' => 'enter_otp_user_key']) }} 
                            {{--<input type="hidden" id="otp_temp_key" name="otp_temp_key" value="">
                            <input type="hidden" id="user_key" name="user_key" value=""> --}}
                        </div>
                        <div class="text-right mb-5">
                            {!! Html::decode( Form::button('<span class="shape">Submit</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ) !!}
                        </div>
                        {{ form::close() }}
                        {!! JsValidator::formRequest('App\Http\Requests\Frontend\OTPRequest', '#otp-form')  !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- OTP model -->

    <!-- OTP resend model -->
    <div class="modal otp_resend_modal fade" id="otp_resend_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h5 class="modal-title">{{__('Notification Alert!')}}</h5>
                    <div class="icons-add"><img src="{{ asset(FRONT_END_BASE_PATH.'img/icon3.png') }}"></div>

                </div>
                <div class="modal-body">
                    {{ Form::open(['route' => 'frontend.send-otp', 'id' => 'otp-resend-form', 'class' => 'form-horizontal', 'method' => 'POST']) }} 
                    <div class="form-box floating_label">
                        {{ Form::hidden('user_key','',['id' => 'confirmation_verify_otp_user_key']) }} 
                        {{--<input type="hidden" id="user_keys" name="user_key" value="">--}}
                        {{--<p>{{__('The phone number already exists You have to verify OTP !')}}</p> --}}
                        <p id = 'send-otp'></p>
                        <div class="text-right mb-5">
                            {!! Html::decode( Form::button('<span class="shape">Send</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ) !!}
                        </div>
                        {{ form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- OTP resend model -->
