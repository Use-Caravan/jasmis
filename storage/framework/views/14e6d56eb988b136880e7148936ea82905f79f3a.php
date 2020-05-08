
    <!-- Signup modal -->
    <div class="modal edit-profile_ul right_slide fade" id="edit-profile">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close-modal" data-dismiss="modal"><i class="material-icons">arrow_back</i></button>
                    <h5 class="modal-title"><?php echo e(__('Edit Profile')); ?></h5>
                </div>
                <div class="modal-body">                    
                    <?php echo e(Form::open(['route' => 'frontend.profile-update', 'id' => 'profile-update', 'class' => 'form-horizontal', 'method' => 'POST','enctype' => "multipart/form-data",'autocomplete' => 'off'])); ?>

                    <div class="form-box floating_label">
                        <div class="form-group focus">
                            <?php echo e(Form::label("first_name", __('First Name'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("first_name", Auth::guard(GUARD_USER)->user()->first_name, ['class' => 'form-control','id' => "profilefirst-name"])); ?>    
                        </div>
                        <div class="form-group focus">
                            <?php echo e(Form::label("last_name", __('Last Name'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("last_name",Auth::guard(GUARD_USER)->user()->last_name, ['class' => 'form-control','id' => "profilelast-name"])); ?>

                        </div>
                        <div class="form-group focus rd_only">
                            <?php echo e(Form::label("email", __('Email'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("email",Auth::guard(GUARD_USER)->user()->email, ['class' => 'form-control','id' => "profilesign-up-email",'readonly'])); ?> 
                        </div>

                        <div class="form-group focus rd_only">
                            <?php echo e(Form::label("phone_number", __('Phone Number'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::text("phone_number",Auth::guard(GUARD_USER)->user()->phone_number, ['class' => 'form-control','id' => "profilemobile-number"])); ?> 
                        </div>

                        <div class="form-group focus">
                            <?php echo e(Form::label('gender', __('Gender'), ['class' => 'required'])); ?> 
                            <?php echo e(Form::select('gender', Common::gender() , Auth::guard(GUARD_USER)->user()->gender, ['class' => 'form-control','placeholder' => __('Please choose gender'),'id' => 'gender'] )); ?>

                        </div>
                    
                        <div class="form-group fx_top focus">
                            <?php echo e(Form::label("dob", __('Date Of Birth'))); ?>

                            <span class="iconsfly"><i class="material-icons">date_range</i></span>
                            <?php echo e(Form::text("dob",Auth::guard(GUARD_USER)->user()->dob, ['class' => 'form-control dpicker','id' => 'date_picker','data-position' =>'top left'])); ?> 
                        </div>
                        <div class="form-group focus">
                            <?php echo e(Form::label("profile_image", __('Profile Image'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::file('profile_image',['class' => 'form-control','id' => "profile-image"])); ?>

                        </div> 
                       <div class="form-group focus">
                            <?php echo e(Form::label("current_password", __('Current Password'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::password("current_password", ['class' => 'form-control','id' => "profilepassword"])); ?> 
                           <span class="change-password"><?php echo e(__('Change')); ?></span>
                        </div>

                        <div class="change-password-div">
                            <div class="form-group">
                            <?php echo e(Form::label("new_password", __('New Password'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::password("new_password", ['class' => 'form-control','id' => "pr-new_password"])); ?> 
                            </div>
                            <div class="form-group">
                            <?php echo e(Form::label("confirm_password", __('Confirm Password'), ['class' => 'required' ])); ?>

                            <?php echo e(Form::password("confirm_password", ['class' => 'form-control','id' => "pr-confirm_password"])); ?> 
                            </div>
                        </div>

                        <div class="text-right">
                            <?php echo Html::decode( Form::button('<span class="shape">'.__('Submit').'</span>', ['type'=>'submit', 'class' => 'shape-btn shape1']) ); ?>

                        </div>
                        <?php echo e(form::close()); ?>

                        <?php echo JsValidator::formRequest('App\Http\Requests\Frontend\ProfileUpdateRequest', '#profile-update'); ?> 
                    </div>

                </div>
            </div>
        </div>
    </div>
<script>
   $(document).ready(function(){
        $('#date_picker').datepicker();
        $('#date_picker').val("<?php echo e(date('m/d/Y', strtotime(Auth::guard(GUARD_USER)->user()->dob) )); ?>");
    });
</script>
    <!-- Signup modal -->