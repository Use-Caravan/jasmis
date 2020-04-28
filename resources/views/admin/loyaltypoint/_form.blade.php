{{ Form::open(['url' => $url, 'id' => 'loyaltypoint-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">
        
        <div class="form-group {{ ($errors->has("from_amount")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("from_amount", __('admincrud.From Amount'), ['class' => 'required']) }}
                {{ Form::text("from_amount", $model->from_amount, ['class' => 'form-control']) }} 
                @if($errors->has("from_amount"))
                    <span class="help-block error-help-block">{{ $errors->first("from_amount") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("to_amount")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("to_amount", __('admincrud.To Amount'), ['class' => 'required']) }}
                {{ Form::text("to_amount", $model->to_amount, ['class' => 'form-control']) }} 
                @if($errors->has("to_amount"))
                    <span class="help-block error-help-block">{{ $errors->first("to_amount") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("point")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("point", __('admincrud.Point'), ['class' => 'required']) }}
                {{ Form::text("point", $model->point, ['class' => 'form-control']) }} 
                @if($errors->has("point"))
                    <span class="help-block error-help-block">{{ $errors->first("point") }}</span>
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
        {{ Html::link(route('loyaltypoint.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
   
  <!-- /.box-footer -->
{{ Form::close() }}
{!! JsValidator::formRequest('App\Http\Requests\Admin\LoyaltyPointRequest', '#loyaltypoint-form')  !!}

