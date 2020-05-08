<!-- login modal -->
<div class="modal login_modal corporate_offer_ui_modal fade" id="corporate_offer_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                <h5 class="modal-title"><?php echo e(__('Corporate Package')); ?></h5>
                <div class="icons-add"><img src="<?php echo e(asset(FRONT_END_BASE_PATH.'img/icon1.png')); ?>"></div>
            </div>
            <div class="modal-body">

                <div class="form-box floating_label">
                    <?php echo e(Form::open(['route' => 'frontend.corporate-login', 'id' => 'corporate-offer-form', 'class' => 'form-horizontal signinDetails', 'method' => 'POST', 'files' => "true"])); ?>

                        <div class="form-group ">
                            <?php echo e(Form::label("corporate_name", __('Corporate Name'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text("corporate_name",'', ['class' => 'form-control'])); ?> 
                        </div>
                        <div class="form-group">                            
                            <?php echo e(Form::label("contact_name", __('Contact Name'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text("contact_name", '', ['class' => 'form-control'])); ?>

                        </div>
                        <div class="form-group">                            
                            <?php echo e(Form::label("office_email", __('Office Email'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text("office_email", '', ['class' => 'form-control'])); ?>

                        </div>
                        <div class="form-group">                            
                            <?php echo e(Form::label("mobile_number", __('Mobile Number'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text("mobile_number", '', ['class' => 'form-control'])); ?>

                        </div>
                        <div class="form-group">                            
                            <?php echo e(Form::label("contact_address", __('Contact Addresss'), ['class' => 'required'])); ?>

                            <?php echo e(Form::textarea("contact_address", '', ['class' => 'form-control'])); ?>

                        </div>
                        <div class="form-group">
                            <?php echo e(Form::label("valid_upto", __('Valid Upto'), ['class' => 'required'])); ?>

                            <?php echo e(Form::text("valid_upto", '', ['class' => 'form-control dpicker', 'data-position' => 'top left'])); ?>

                        </div>
                        <div class="form-group upload_image">
                            <div class="custom-file">
                                <?php echo e(Form::label("company_logo", __('Company Logo...'), ['class' => 'custom-file-label','for' => 'validatedCustomFile'])); ?>

                                <?php echo e(Form::file("company_logo", ['class' => 'custom-file-input', 'id' => 'validatedCustomFile' ])); ?>

                            </div>
                        </div>
                        <div class="text-right btn-footer-shape">
                            <button class="shape-btn loader shape1"><span class="shape"><?php echo e(__('Submit')); ?></span></button>
                        </div>
                    <?php echo e(form::close()); ?>

                    <?php echo JsValidator::formRequest('App\Http\Requests\Frontend\CorporateOfferRequest', '#corporate-offer-form'); ?>

                </div>                                
            </div>
        </div>
    </div>
</div>
<!-- login modal -->
<style>
.corporate_offer_ui_modal .custom-file-input{ height: 54px !important; }
</style>