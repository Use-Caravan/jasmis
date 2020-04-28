{{ Form::open(['url' => $url, 'id' => 'ingredient-group-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST' ]) }}
    <div class="box-body">
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{$errors->has("ingredient_group_name.$key")}}"> 
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content full_row">
            @foreach ($languages as $key => $language)
            <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                <div class="form-group {{ ($errors->has("ingredient_group_name.$key")) ? 'has-error' : '' }}"> 
                    <div class="col-sm-6">                   
                        {{ Form::label("ingredient_group_name[$key]", __('admincrud.Ingredient Group Name'), ['class' => 'required']) }}
                        {{ Form::text("ingredient_group_name[$key]", $modelLang['ingredient_group_name'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("ingredient_group_name.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("ingredient_group_name.$key") }}</span>
                        @endif                    
                    </div>
                </div>
            </div> <!--tab-pane-->
            @endforeach
        </div>
        @if(APP_GUARD == GUARD_ADMIN)
        <div class="col-md-6">  
            <div class="form-group {{ ($errors->has("vendor_id")) ? 'has-error' : '' }}">
                {{ Form::label("vendor_id", __('admincrud.Vendor Name')) }}
                {{ Form::select("vendor_id", $vendorList, $model->vendor_id, ['class' => 'selectpicker vendor_id' ,"id" => "vendor_id", 'placeholder' => 'Choose ingredient']) }} 
                @if($errors->has("vendor_id.$key"))
                    <span class="help-block error-help-block">{{ $errors->first("vendor_id.$key") }}</span>
                @endif                    
            </div>
        </div>
        @else 
            {{ Form::hidden("vendor_id", auth()->guard(GUARD_VENDOR)->user()->vendor_id) }}
        @endif
        <div class="col-md-6 ">
            <div class="form-group {{ ($errors->has("ingredient_type")) ? 'has-error' : '' }}">                                
                {{ Form::label("ingredient_type", __('admincrud.Ingredient Type'), ['class' => 'required']) }}
                {{ Form::select('ingredient_type', $model->getIngredientTypes(), $model->ingredient_type ,['class' => 'selectpicker','placeholder' => __('admincrud.Choose ingredient type') ] )}}
                @if($errors->has("ingredient_type"))
                    <span class="help-block error-help-block">{{ $errors->first("ingredient_type") }}</span>
                @endif                    
            </div>
        </div>               
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("minimum")) ? 'has-error' : '' }}">                                
                {{ Form::label("minimum", __('admincrud.Minimum Quantity'), ['class' => 'required']) }}
                {{ Form::text('minimum', ($model->minimum === null) ? 0 : $model->minimum, [
                    'class' => 'form-control',
                    'onkeypress' => "return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0",
                    'maxlength' => "6",
                    ] )}}
                @if($errors->has("minimum"))
                    <span class="help-block error-help-block">{{ $errors->first("minimum") }}</span>
                @endif                    
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("maximum")) ? 'has-error' : '' }}">                                 
                {{ Form::label("maximum", __('admincrud.Maximum Quantity'), ['class' => 'required']) }}
                {{ Form::text('maximum', ($model->maximum === null) ? 0 : $model->maximum, [
                    'class' => 'form-control',
                    'onkeypress' => "return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0",
                    'maxlength' => "6",
                    ] )}}
                @if($errors->has("maximum"))
                    <span class="help-block error-help-block">{{ $errors->first("maximum") }}</span>
                @endif                    
            </div>
        </div>  
        <div class="col-md-6">          
            <div class="form-group {{ ($errors->has("sort_no")) ? 'has-error' : '' }}">                                
                {{ Form::label("sort_no", __('admincrud.Sort No'), ['class' => 'required']) }}
                {{ Form::text("sort_no", $model->sort_no, ['class' => 'form-control']) }} 
                @if($errors->has("sort_no.$key"))
                    <span class="help-block error-help-block">{{ $errors->first("sort_no.$key") }}</span>
                @endif                    
            </div>
        </div>               
        <div class="col-md-6">
            <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">                                
                {{ Form::label("Group_status", __('admincrud.Ingredient Group Status')) }}
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
        
        <div class="col-md-12">
            <div class="drag_main">
                <div class="drag_section">
                    <div class="search_list full_row">
                        <input type="text" class="input_control" placeholder="Search..." id="search-left">
                        <i class="fa fa-search"></i>
                    </div>
                    <ul class="drag_list" id="drag-ingredient">
                        @foreach($ingredients as $key => $value)
                            <li class="ui-draggable ui-draggable-handle" id="ingredient_{{$value['ingredient_id']}}" ingredientID="{{ $value['ingredient_id'] }}"">{{ $value['ingredient_name'] }}</li>
                        @endforeach
                    </ul> <!--drag_list-->
                </div> <!--drag_section-->                
                <div class="drag_section">
                    <div class="search_list full_row">
                        <input type="text" class="input_control" placeholder="Search..." id="search-right">
                        <i class="fa fa-search"></i>
                    </div> <!--search_list-->                    
                    <ul class="drag_list" id="drop-ingredient">                        
                        @if(count((array) $existsIngredients) > 0)
                        @foreach($existsIngredients as $key => $value)
                            <li class="ui-draggable ui-draggable-handle" id="ingredient_{{$value['ingredient_id']}}" ingredientID="{{ $value['ingredient_id'] }}"">{{ $value['ingredient_name'] }}
                                <input type="text" id="ingredientID_{{$value['ingredient_id']}}" class="form-control ingredientCost" name="price[{{$value['ingredient_id']}}]" value="{{ isset($value['price']) ? $value['price'] : old('price')[$value['ingredient_id']]  }}" maxlength="6" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0 " style="display: inline-block;">
                            </li>
                        @endforeach                      
                        @endif  
                    </ul> <!--drag_list-->
                </div> <!--drag_section-->
                
            </div>
        </div>
        <div class="col-md-6">
            
        </div>
    </div>
  <!-- /.box-body -->
    <div class="box-footer">
        {{ Html::link(route('ingredient-group.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\IngredientGroupRequest', '#ingredient-group-form')  !!}

<script>
$(document).ready(function()
{
    $('#ingredient-group-form').on('submit',function(e) {
        var ingredientLength = $('#drop-ingredient .ingredientCost').length;
        var maximum = $('input[name=maximum]').val();
            if(ingredientLength < maximum) {
                errorNotify(" {{ __('please choose maximum quantity of ingredients') }} ");
                e.preventDefault();
            } 
    });
    $("#drag-ingredient, #drop-ingredient").sortable({
        connectWith: "#drag-ingredient, #drop-ingredient",        
    }).disableSelection();
    $("#drop-ingredient").on("sortreceive", function (event, ui)
    {
        var Newpos = ui.item.index() + 1;
        var element = $("#drop-ingredient li:nth-child(" + Newpos + ")")
        var ingredientID = element.attr("ingredientid");
        var innerHtml = element.html();
        var inputHtml = innerHtml+`<input type="text" id="ingredientID_`+ingredientID+`" class="form-control ingredientCost" name="price[`+ingredientID+`]" value="0" maxlength="6" onkeypress="return (event.charCode >= 48 &amp;&amp; event.charCode <= 57) || event.charCode == 46 || event.charCode == 0 " style="display: inline-block;">`;
        element.html(inputHtml);        
                    
    });
    $("#drag-ingredient").on("sortreceive", function (event, ui)
    {
        var Newpos = ui.item.index() + 1;
        var element = $("#drag-ingredient li:nth-child(" + Newpos + ")");
        var ingredientID = element.attr("ingredientid");        
        $('#ingredientID_'+ingredientID).remove();                                    
    });

    $('#vendor_id').change(function(e) {
        $.ajax({
            url: "{{ route('get-vendor-ingredient') }}",
            type: 'post',
            data: { vendor_id: $(this).val() },
            success: function(result){                 
                 if(result.status == AJAX_SUCCESS){
                    var ingredient = ``;
                    $("#drag-ingredient").html('');
                    $.each(result.data, function (key, val) {
                        ingredient += `<li class="ui-draggable ui-draggable-handle" id="ingredient_`+val.ingredient_id+`" ingredientID="`+val.ingredient_id+`">`+val.ingredient_name+`</li>`;
                    });                       
                    $("#drag-ingredient").html(ingredient);
                }else{
                    errorNotify(" {{ __('Something went wrong') }}" );
                }
            }
        });
    });

});
</script>
