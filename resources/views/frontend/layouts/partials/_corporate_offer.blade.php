<!-- login modal -->
<div class="modal login_modal corporate_offer_ui_modal fade" id="corporate_offer_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                <h5 class="modal-title">{{__('Corporate Package')}}</h5>
                <div class="icons-add"><img src="{{ asset(FRONT_END_BASE_PATH.'img/icon1.png') }}"></div>
            </div>
            <div class="modal-body">

                <div class="form-box floating_label">
                    {{ Form::open(['route' => 'frontend.corporate-login', 'id' => 'corporate-offer-form', 'class' => 'form-horizontal signinDetails', 'method' => 'POST', 'files' => "true"]) }}
                        <div class="form-group ">
                            {{ Form::label("corporate_name", __('Corporate Name'), ['class' => 'required']) }}
                            {{ Form::text("corporate_name",'', ['class' => 'form-control']) }} 
                        </div>
                        <div class="form-group">                            
                            {{ Form::label("contact_name", __('Contact Name'), ['class' => 'required']) }}
                            {{ Form::text("contact_name", '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">                            
                            {{ Form::label("office_email", __('Office Email'), ['class' => 'required']) }}
                            {{ Form::text("office_email", '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">                            
                            {{ Form::label("mobile_number", __('Mobile Number'), ['class' => 'required']) }}
                            {{ Form::text("mobile_number", '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">                            
                            {{ Form::label("contact_address", __('Contact Addresss'), ['class' => 'required']) }}
                            {{ Form::textarea("contact_address", '', ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label("valid_upto", __('Valid Upto'), ['class' => 'required']) }}
                            {{ Form::text("valid_upto", '', ['class' => 'form-control dpicker', 'data-position' => 'top left']) }}
                        </div>
                        <div class="form-group upload_image">
                            <div class="custom-file">
                                {{ Form::label("company_logo", __('Company Logo...'), ['class' => 'custom-file-label','for' => 'validatedCustomFile']) }}
                                {{ Form::file("company_logo", ['class' => 'custom-file-input', 'id' => 'validatedCustomFile' ]) }}
                            </div>
                        </div>
                        <div class="text-right btn-footer-shape">
                            <button class="shape-btn loader shape1"><span class="shape">{{__('Submit')}}</span></button>
                        </div>
                    {{ form::close() }}
                    {!! JsValidator::formRequest('App\Http\Requests\Frontend\CorporateOfferRequest', '#corporate-offer-form')  !!}
                </div>                                
            </div>
        </div>
    </div>
</div>
<!-- login modal -->
<style>
.corporate_offer_ui_modal .custom-file-input{ height: 54px !important; }
</style>