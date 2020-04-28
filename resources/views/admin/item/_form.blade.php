{{ Form::open(['url' => $url, 'id' => 'item-form    ', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST','enctype' => "multipart/form-data" ]) }}
    <div class="box-body">
        
               
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{$errors->has("item_name.$key").$errors->has("item_image.$key").$errors->has("item_description.$key")}}"> 
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content full_row">            
            @foreach ($languages as $key => $language)
                <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">                

                    <div class="col-md-6">                
                        <div class="form-group {{ ($errors->has("item_name.$key")) ? 'has-error' : '' }}" >
                            {{ Form::label("item_name[$key]", __('admincrud.Item Name'), ['class' => 'required']) }}
                            {{ Form::text("item_name[$key]", $modelLang['item_name'][$key], ['class' => 'form-control']) }} 
                            @if($errors->has("item_name.$key"))
                                <span class="help-block error-help-block">{{ $errors->first("item_name.$key") }}</span>
                            @endif                    
                        </div>
                    </div>
                    <?php /*<div class="col-md-2" style="padding-left:20px; ">
                    {{ Form::text("title",  $modelLang['item_image'][$key], ['class' => 'form-control title']) }}
                    </div> */ ?>
                    <div class="col-md-6">
                        <div class="form-group {{ ($errors->has("item_image.$key")) ? 'has-error' : '' }}">
                        <ul class="uploads reset">                                
                            <li>
                                {{ 
                                    Form::label(
                                    "item_image[$key]",
                                    __('admincrud.Item Image')." 350 * 350", 
                                    [
                                        'class' => 'required fa fa-plus-circle',
                                        "id" => "item_image$key",
                                        'style' => ($model->exists) ? 'background:url('.FileHelper::loadImage(  isset($modelLang["item_image"][$key]) ? $modelLang["item_image"][$key] : ''  ).')' : ''
                                                                                                            
                                    ])
                                 }}

                                {{ Form::file("item_image[$key]", ['class' => 'form-control upload_image','lang' => $key]) }}
                                {{--  
                                    <input id="upload1" type="file">
                                    <label for="upload1"><i class="fa fa-plus-circle"></i></label> 
                                --}}                                
                            </li>
                                @if($errors->has("item_image.$key"))
                                    <span class="help-block error-help-block">{{ $errors->first("item_image.$key") }}</span>
                                @endif 
                        </ul>
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ ($errors->has("item_description.$key")) ? 'has-error' : '' }}" >
                            {{ Form::label("item_description[$key]", __('admincrud.Item Description'), ['class' => 'required']) }}
                            {{ Form::textarea("item_description[$key]", $modelLang['item_description'][$key], ['class' => 'form-control']) }} 
                            @if($errors->has("item_description.$key"))
                                <span class="help-block error-help-block">{{ $errors->first("item_description.$key") }}</span>
                            @endif                    
                        </div>
                    </div> 
                    <div class="col-md-6">                
                        <div class="form-group {{ ($errors->has("allergic_ingredient.$key")) ? 'has-error' : '' }}" >
                            {{ Form::label("allergic_ingredient[$key]", __('admincrud.Allergic Ingredient'), ['class' => 'required']) }}
                            {{ Form::text("allergic_ingredient[$key]", $modelLang['allergic_ingredient'][$key], ['class' => 'form-control']) }} 
                            @if($errors->has("allergic_ingredient.$key"))
                                <span class="help-block error-help-block">{{ $errors->first("allergic_ingredient.$key") }}</span>
                            @endif                    
                        </div>
                    </div>                                        
                    {{--
                    <div class="col-md-6"> 
                        <div class="form-group">
                            <ul class="uploads reset">
                                <li class="uploaded">                                
                                        <label for="upload1" style="background:url({{ FileHelper::loadImage($modelLang['vendor_logo'][$key]}})"></label>
                                </li>
                            </ul>                   
                        </div>
                    </div>  
                    --}}                    
                </div>                
            @endforeach
        </div> <!--tab-pane-->
        @if(APP_GUARD == GUARD_ADMIN)
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("vendor_id")) ? 'has-error' : '' }}">
            {{ Form::label("vendor_id", __('admincrud.Vendor Name'), ['class' => 'required']) }} 
            {{ Form::select('vendor_id', $vendorList, $model->vendor_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Vendor'),'id' =>'Item-vendor_id'] )}}
                @if($errors->has("vendor_id"))
                    <span class="help-block error-help-block">{{ $errors->first("vendor_id") }}</span>
                @endif 
            </div>
        </div>
        @else
            {{ Form::hidden("vendor_id", auth()->guard(GUARD_VENDOR)->user()->vendor_id) }}
        @endif
        @if($model->exists)
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("branch_id")) ? 'has-error' : '' }}">
            {{ Form::label("branch_id", __('admincrud.Branch Name'), ['class' => 'required']) }} 
            {{ Form::select('branch_id', $branchList, $model->branch_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Branch'),'id' =>'Item-branch_id'] )}}
                @if($errors->has("branch_id"))
                    <span class="help-block error-help-block">{{ $errors->first("branch_id") }}</span>
                @endif 
            </div>
        </div>
        @else
        <div class="col-md-6">
            <div class="form-group {{ ($errors->has("branch_id")) ? 'has-error' : '' }}">
            {{ Form::label("branch_id", __('admincrud.Branch Name'), ['class' => 'required']) }} 
            {{ Form::select('branch_id[]', $branchList, $model->branch_id ,['multiple'=>'multiple','class' => 'selectpicker','title' => __('admincrud.Please Choose Branch'),'id' =>'Item-branch_id'] )}}
                @if($errors->has("branch_id"))
                    <span class="help-block error-help-block">{{ $errors->first("branch_id") }}</span>
                @endif 
            </div>
        </div> 
        @endif
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("category_id")) ? 'has-error' : '' }}">
            {{ Form::label("category_id", __('admincrud.Category Name'), ['class' => 'required']) }} 
            {{ Form::select('category_id', $branchCategoryList, $model->category_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Category'),'id' => 'Item-category_id'] )}}
                @if($errors->has("category_id"))
                    <span class="help-block error-help-block">{{ $errors->first("category_id") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("cuisine_id")) ? 'has-error' : '' }}">
            {{ Form::label("cuisine_id", __('admincrud.Cuisine Name'), ['class' => 'required']) }} 
            {{ Form::select('cuisine_id[]', $branchCuisineList, $existsCuisines ,['multiple'=>'multiple','class' => 'selectpicker','title' => __('admincrud.Please Choose Cuisine'),'id' => 'Item-cuisine_id'] )}}
                @if($errors->has("cuisine_id"))
                    <span class="help-block error-help-block">{{ $errors->first("cuisine_id") }}</span>
                @endif 
            </div>
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("item_price")) ? 'has-error' : '' }}">                    
                {{ Form::label("item_price", __('admincrud.Item Price'), ['class' => 'required']) }}
                {{ Form::text("item_price", $model['item_price'], ['class' => 'form-control']) }} 
                @if($errors->has("item_price"))
                    <span class="help-block error-help-block">{{ $errors->first("item_price") }}</span>
                @endif                    
            </div>
        </div> 
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("sort_no")) ? 'has-error' : '' }}">                    
                {{ Form::label("sort_no", __('admincrud.Sort No')) }}
                {{ Form::text("sort_no", $model['sort_no'], ['class' => 'form-control']) }} 
                @if($errors->has("sort_no"))
                    <span class="help-block error-help-block">{{ $errors->first("sort_no") }}</span>
                @endif                    
            </div> 
        </div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("ingredient_group_id")) ? 'has-error' : '' }}"> 
            {{Form::label('ingredient_group_id', __('admincrud.Ingredient Group Name'))}}
            {{Form::select('ingredient_group_id[]',$ingredientGroupList,$existsIngredientGroup,['multiple'=>'multiple','class' => 'selectpicker','title'=> __('admincommon.Nothing selected')])}}
            @if($errors->has("ingredient_group_id"))
                <span class="help-block error-help-block">{{ $errors->first("ingredient_group_id") }}</span>
            @endif 
            </div> 
        </div>
        <div class="clearfix"></div>
        <div class="col-md-6"> 
            <div class="form-group {{ ($errors->has("approved_status")) ? 'has-error' : '' }}">
            {{ Form::label("approved_status", __('admincrud.Approved Status')) }}
            @php $model->approved_status = ($model->exists) ? $model->approved_status : ITEM_APPROVED @endphp
                {{ Form::radio('approved_status', ITEM_APPROVED, ($model->approved_status == ITEM_APPROVED), ['class' => 'hide', 'id' => 'approved_status_approved']) }}
                {{ Form::label("approved_status_approved", __('admincrud.Approved'), ['class' => 'radio']) }}
                {{ Form::radio('approved_status', ITEM_UNAPPROVED, ($model->approved_status == ITEM_UNAPPROVED), ['class' => 'hide', 'id' => 'approved_status_unapproved']) }}
                {{ Form::label("approved_status_unapproved", __('admincrud.Unapproved'), ['class' => 'radio']) }}    
                @if($errors->has("approved_status"))
                    <span class="help-block error-help-block">{{ $errors->first("approved_status") }}</span>
                @endif 
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">
                {{ Form::label("item_status", __('admincrud.Item Status')) }}
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
        {{ Html::link(route('item.index'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
        {{ Form::submit
        ($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\ItemRequest', '#item-form')  !!}

<script> 
$(document).ready(function(){
    $('#Item-branch_id').change(function()
    {           
        $.ajax({
            url: "{{ route('get-category-by-vendor') }}",
            type: 'post',
            data: { branch_id: $(this).val() },
            success: function(result){ 
                 if(result.status == AJAX_SUCCESS){
                    $('#Item-category_id').html('');
                    
                    $.each(result.data,function(key,title)
                    {  
                        $('#Item-category_id').append($('<option>', { value : key }).text(title));                       
                    });
                    $('.selectpicker').selectpicker('refresh');
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
   
        $.ajax({
            url: "{{ route('get-cuisine-by-vendor') }}",
            type: 'post',
            data: { branch_id: $(this).val() },
            success: function(result){ 
                 if(result.status == AJAX_SUCCESS){
                    $('#Item-cuisine_id').html('');
                    
                    $.each(result.data,function(key,title)
                    {  
                        $('#Item-cuisine_id').append($('<option>', { value : key }).text(title));                       
                    });
                    $('.selectpicker').selectpicker('refresh');
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    });

    $('#Item-vendor_id').change(function()
    {   
        $.ajax({
            url: "{{ route('get-branch-by-vendor') }}",
            type: 'post',
            data: { vendor_id: $(this).val() },
            success: function(result){ 
                 if(result.status == AJAX_SUCCESS){
                    $('#Item-branch_id').html('');
                    /* $('#Item-branch_id').append($('<option>', 'Select branch name','</option>')); */
                    $.each(result.data,function(key,title)
                    {  
                        $('#Item-branch_id').append($('<option>', { value : key }).text(title));                       
                    });
                    $('.selectpicker').selectpicker('refresh');
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    });

    $('.upload_image').on('change',function () {        
        var lang = $(this).attr('lang');
        var preview = $('#item_image'+lang);
        var file    = document.querySelector('input[name="item_image['+lang+']"]').files[0];
        var reader  = new FileReader();

        reader.addEventListener("load", function () {        
            //preview.src = reader.result;                
            preview.css('background-image', 'url(' + reader.result + ')');
        }, false);
        if (file) {
            reader.readAsDataURL(file);
        }
    });
}); 


</script>



