{{ Form::open(['url' => $url, 'id' => 'delivery-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">
        
        <div class="form-group {{ ($errors->has("from_km")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("from_km", __('admincrud.From Kilometer'), ['class' => 'required']) }}
                {{ Form::text("from_km", $model->from_km, ['class' => 'form-control']) }} 
                @if($errors->has("from_km"))
                    <span class="help-block error-help-block">{{ $errors->first("from_km") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("to_km")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("to_km", __('admincrud.To Kilometer'), ['class' => 'required']) }}
                {{ Form::text("to_km", $model->to_km, ['class' => 'form-control']) }} 
                @if($errors->has("to_km"))
                    <span class="help-block error-help-block">{{ $errors->first("to_km") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group {{ ($errors->has("price")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("price", __('admincrud.Price'), ['class' => 'required']) }}
                {{ Form::text("price", $model->price, ['class' => 'form-control']) }} 
                @if($errors->has("price"))
                    <span class="help-block error-help-block">{{ $errors->first("price") }}</span>
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
        {{ Html::link(route('deliverycharge.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
   
  <!-- /.box-footer -->
{{ Form::close() }}
{!! JsValidator::formRequest('App\Http\Requests\Admin\DeliveryChargeRequest', '#delivery-form')  !!}

