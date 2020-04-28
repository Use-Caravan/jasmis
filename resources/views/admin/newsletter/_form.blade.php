{{ Form::open(['url' => $url, 'id' => 'newsletter-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">
        
        <div class="form-group {{ ($errors->has("newsletter_title")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("newsletter_title", __('admincrud.Newsletter Title'), ['class' => 'required']) }}
                {{ Form::text("newsletter_title", $model->newsletter_title, ['class' => 'form-control']) }} 
                @if($errors->has("newsletter_title"))
                    <span class="help-block error-help-block">{{ $errors->first("newsletter_title") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("newsletter_content")) ? 'has-error' : '' }}"> 
            <div class="col-md-12">                          
                {{ Form::label("newsletter_content", __('admincrud.Newsletter Content'), ['class' => 'required']) }}
                {{ Form::textarea("newsletter_content", $model->newsletter_content, ['class' => 'form-control']) }} 
                @if($errors->has("newsletter_content"))
                    <span class="help-block error-help-block">{{ $errors->first("newsletter_content") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                                          
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
        {{ Html::link(route('newsletter.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
   
  <!-- /.box-footer -->
{{ Form::close() }}
{!! JsValidator::formRequest('App\Http\Requests\Admin\NewsletterRequest', '#newsletter-form')  !!}
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'newsletter_content' );
</script>
