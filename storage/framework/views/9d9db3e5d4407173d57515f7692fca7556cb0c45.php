<!-- login modal -->

    <div class="modal login_modal fade" id="login_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h5 class="modal-title"><?php echo e(__('Login')); ?></h5>
                    <div class="icons-add"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/icon1.png')); ?>"></div>
                </div>
                <div class="modal-body">

                    <div class="form-box floating_label">
                        <?php echo e(Form::open(['route' => 'frontend.signin', 'id' => 'login-form', 'class' => 'form-horizontal signinDetails', 'method' => 'POST'])); ?>     
                         <div class="form-group ">
                                <?php echo e(Form::label("username", __('Email'), ['class' => 'required'])); ?>

                                <?php echo e(Form::text("username",'', ['class' => 'form-control','id' => "username",'maxlength'=>'100'])); ?> 
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <?php echo e(Form::label("password", __('Password'), ['class' => 'required'])); ?>

                                <?php echo e(Form::password("password", ['class' => 'form-control','id' => "password",'maxlength'=>'15'])); ?> 
                            </div>
                            
                             <?php echo Html::decode( Html::link('#forgot-modal', __('Forgot?'),['class' => 'forgot','data-toggle' => 'modal','data-dismiss' => 'modal', 'data-target' => '#forgot-modal'])); ?> 
                        </div>
                        <div class="text-right">
                            <button class="shape-btn loader shape1"><span class="shape"><?php echo e(__('Submit')); ?></span></button>
                        </div>
                        <?php echo e(form::close()); ?>

                      
                    </div>

                    <div class="or text-center"><?php echo e(__('(OR)')); ?></div>

                    <div class="text-center connect-social">
                        <a href="<?php echo e(route('frontend.facebook-login')); ?>" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="<?php echo e(route('frontend.google-login')); ?>" class="gmail">&nbsp;</a>
                    </div>

                    <p class="switch-modal f18 text-center"><?php echo e(__('Don’t have an account?')); ?> <a href="#sign-up" data-toggle="modal" data-target="#sign-up" data-dismiss="modal"> <?php echo e(__('Sign Up')); ?></a></p>

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
                    <h5 class="modal-title"><?php echo e(__('Forgot Password')); ?></h5>
                    <div class="icons-add"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/icon3.png')); ?>"></div>

                </div>
                <div class="modal-body">
                    <?php echo e(Form::open(['route' => 'frontend.send-reset-link', 'id' => 'forgot-form', 'class' => 'form-horizontal forgot-password', 'method' => 'POST'])); ?> 
                    <div class="form-box floating_label">
                        <div class="form-group">
                            <?php echo e(Form::label("fpemail", __('Email'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("email",'', ['class' => 'form-control','id' => "fpemail"])); ?>

                        </div>
                        <div class="text-right mb-5">
                            <?php echo Html::decode( Form::button('<span class="shape">Submit</span>', ['type'=>'button', 'class' => 'shape-btn loader shape1 forgot-submit']) ); ?>

                        </div>
                        <?php echo e(form::close()); ?>

                        <?php echo JsValidator::formRequest('App\Http\Requests\Frontend\ForgotPasswordRequest', '#forgot-form'); ?>

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
                    <h5 class="modal-title"><?php echo e(__('Sign Up')); ?></h5>
                    <div class="icons-add"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/icon2.png')); ?>"></div>

                </div>
                <div class="modal-body">
                    <?php echo e(Form::open(['route' => 'frontend.signup', 'id' => 'register-form', 'class' => 'form-horizontal signupDetails', 'method' => 'POST'])); ?>     
                        <div class="form-box floating_label">
                            
                            <div class="form-group ">
                                <?php echo e(Form::label("sfirst-name", __('First Name'), ['class' => 'required' ])); ?>

                                <?php echo e(Form::text("first_name",'', ['class' => 'form-control','id' => "sfirst-name",'maxlength'=>'100'])); ?> 
                               
                            </div>
                            <div class="form-group ">
                                <?php echo e(Form::label("slast-name", __('Last Name'), ['class' => 'required' ])); ?>

                                <?php echo e(Form::text("last_name",'', ['class' => 'form-control','id' => "slast-name",'maxlength'=>'50'])); ?>

                                  
                            </div>
                            <div class="form-group ">
                                <?php echo e(Form::label("ssign-up-email", __('Email'), ['class' => 'required' ])); ?>

                                <?php echo e(Form::text("email",'', ['class' => 'form-control','id' => "ssign-up-email",'maxlength'=>'100'])); ?> 
                                
                            </div>
                            <div class="form-group">
                                <?php echo e(Form::label("sphone_number", __('Phone Number'), ['class' => 'required' ])); ?>

                                <?php echo e(Form::text("phone_number",'', ['class' => 'form-control','id' => "sphone_number",'maxlength'=>'15'])); ?> 
                               
                            </div>

                             <div class="form-group">
                                <?php echo e(Form::label("spassword", __('Password'), ['class' => 'required' ])); ?>

                                <?php echo e(Form::password("password", ['class' => 'form-control','id' => "spassword",'maxlength'=>'20'])); ?> 
                               
                            </div>

                            <div class="form-group">
                                <?php echo e(Form::label("sconfirm-password", __('Confirm Password'), ['class' => 'required' ])); ?>

                                <?php echo e(Form::password("confirm_password", ['class' => 'form-control','id' => "sconfirm-password",'maxlength'=>'20'])); ?> 
                               
                            </div>   
                            <div class="check-group mb-4">                                
                                <?php echo e(Form::checkbox('terms',1,null,[ 'id' => 'signup', "class" => "checkbox" ])); ?>

                                <?php $__currentLoopData = $cms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($value->position == 4): ?>
                                        <?php echo Html::decode( Form::label('signup', __('I Accept the').' '.Html::link('cms/'.$value->slug, __('Terms and Conditions'), ['']), ['class' => 'checkbox f18']) ); ?>                                
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="text-right">
                                <button class="shape-btn loader shape1"><span class="shape"><?php echo e(__('Submit')); ?></span></button>
                            </div>
                        </div>
                    <?php echo e(Form::close()); ?>

                    <?php echo JsValidator::formRequest('App\Http\Requests\Frontend\RegisterRequest', '#register-form'); ?> 
                    <div class="or text-center"><?php echo e(__('(OR)')); ?></div>

                    <div class="text-center connect-social">
                        <a href="<?php echo e(route('frontend.facebook-login')); ?>" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="<?php echo e(route('frontend.google-login')); ?>" class="gmail">&nbsp;</a>
                    </div>

                    <p class="switch-modal f18 text-center"><?php echo e(__('Already a Member?')); ?> <a href="#login_modal" data-toggle="modal" data-target="#login_modal" data-dismiss="modal"> <?php echo e(__('Login')); ?></a></p>
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
                    <h5 class="modal-title"><?php echo e(__('OTP Verification')); ?></h5>
                    <div class="icons-add"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/icon3.png')); ?>"></div>

                </div>
                <div class="modal-body">
                    <?php echo e(Form::open(['route' => 'frontend.verify-otp', 'id' => 'otp-form', 'class' => 'form-horizontal', 'method' => 'POST'])); ?> 
                    <div class="form-box floating_label">
                        <div class="form-group">
                            <?php echo e(Form::label("otp", __('OTP'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("otp",'', ['class' => 'form-control','id' => "otp"])); ?>

                            <?php echo e(Form::hidden('otp_temp_key','',['id' => 'otp_temp_key'])); ?> 
                            <?php echo e(Form::hidden('user_key','',['id' => 'enter_otp_user_key'])); ?> 
                            
                        </div>
                        <div class="text-right mb-5">
                            <?php echo Html::decode( Form::button('<span class="shape">Submit</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ); ?>

                        </div>
                        <?php echo e(form::close()); ?>

                        <?php echo JsValidator::formRequest('App\Http\Requests\Frontend\OTPRequest', '#otp-form'); ?>

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
                    <h5 class="modal-title"><?php echo e(__('Notification Alert!')); ?></h5>
                    <div class="icons-add"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/icon3.png')); ?>"></div>

                </div>
                <div class="modal-body">
                    <?php echo e(Form::open(['route' => 'frontend.send-otp', 'id' => 'otp-resend-form', 'class' => 'form-horizontal', 'method' => 'POST'])); ?> 
                    <div class="form-box floating_label">
                        <?php echo e(Form::hidden('user_key','',['id' => 'confirmation_verify_otp_user_key'])); ?> 
                        
                        
                        <p id = 'send-otp'></p>
                        <div class="text-right mb-5">
                            <?php echo Html::decode( Form::button('<span class="shape">Send</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ); ?>

                        </div>
                        <?php echo e(form::close()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- OTP resend model -->
