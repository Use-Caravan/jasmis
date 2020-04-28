{{ Form::open(['url' => route('newsletter-sendmail'), 'id' => 'newsletter-subscriber-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">
        
    <div class="col-md-12">
            <div class="form-group {{ ($errors->has("newsletter_subscribers")) ? 'has-error' : '' }}">
            {{ Form::label("newsletter_subscribers", __('admincrud.Subscriber Mail'), ['class' => 'required']) }} 
            {{ Form::select('newsletter_subscribers[]', $subscriberEmails, [] ,['multiple'=>'multiple','class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Subscriber Email'),'title' => __('admincommon.Nothing selected')] )}}
                <span id="apply_promo-error" class="help-block error-help-block"></span>
                @if($errors->has("newsletter_subscribers"))
                    <span class="help-block error-help-block">{{ $errors->first("newsletter_subscribers") }}</span>
                @endif 
                
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group {{ ($errors->has("newsletter_id")) ? 'has-error' : '' }}">
            {{ Form::label("newsletter_id", __('admincrud.Newsletter Title'), ['class' => 'required']) }} 
            {{ Form::select('newsletter_id', $newsletters ,[] ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Newsletter')] )}}
                <span id="apply_promo-error" class="help-block error-help-block"></span>
                @if($errors->has("newsletter_id"))
                    <span class="help-block error-help-block">{{ $errors->first("newsletter_id") }}</span>
                @endif 
                
            </div>
        </div>
    </div>
    
  <!-- /.box-body -->
    <div class="box-footer">
        {{ Html::link(route('newsletter-subscriber.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit(__('admincommon.Send'), ['class' => 'btn btn-info pull-right']) }}
    </div>
   
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\NewsletterSubscriberRequest', '#newsletter-subscriber-form')  !!}
