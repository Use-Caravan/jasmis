{{ Form::open(['url' => $url, 'id' => 'category-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST','enctype' => 'multipart/form-data' ]) }}
    <div class="box-body">
        <div class="col-md-12">
            <div class="form-group radio_group{{ ($errors->has("is_main_category")) ? 'has-error' : '' }}">                
                {{ Form::label("category_type", __('admincrud.Category Type'), ['class' => 'required']) }} 

                {{ Form::radio('is_main_category', MAIN_CATEGORY, ($model->is_main_category == MAIN_CATEGORY), ['class' => 'hide','id'=> 'maincategory' ]) }}
                {{ Form::label("maincategory", __('admincrud.Main Category'), ['class' => ' radio']) }}

                {{ Form::radio('is_main_category', SUB_CATEGORY, ($model->is_main_category == SUB_CATEGORY), ['class' => 'hide','id'=>'subcategory']) }}
                {{ Form::label("subcategory", __('admincrud.Sub Category'), ['class' => 'radio']) }}
                @if($errors->has("is_main_category"))
                    <span class="help-block error-help-block">{{ $errors->first("is_main_category") }}</span>
                @endif                    
            </div>
        </div>  
        <div class="col-md-12 {{ ($model->is_main_category != SUB_CATEGORY || $model->is_main_category = '') ? 'hide' : '' }} " id="main_category_id">
            <div class="form-group {{ ($errors->has("main_category_id")) ? 'has-error' : '' }}">            
            {{ Form::label("main_category_id", __('admincrud.Main Category'), ['class' => 'required']) }} 
            {{ Form::select('main_category_id', $mainCategories, $model->main_category_id ,['class' => 'selectpicker','placeholder' => __('admincrud.Please Choose Category'),'id' => 'category_id'] )}}
                @if($errors->has("main_category_id"))
                    <span class="help-block error-help-block">{{ $errors->first("main_category_id") }}</span>
                @endif 
            </div>
        </div>         
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{$errors->has("category_name.$key")}}"> 
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content full_row">            
            @foreach ($languages as $key => $language)
                <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                    <div class="form-group {{ ($errors->has("category_name.$key")) ? 'has-error' : '' }}" >
                        <div class="col-md-12">
                            {{ Form::label("category_name[$key]", __('admincrud.Category Name'), ['class' => 'required']) }}
                            {{ Form::text("category_name[$key]", $modelLang['category_name'][$key], ['class' => 'form-control']) }} 
                            @if($errors->has("category_name.$key"))
                                <span class="help-block error-help-block">{{ $errors->first("category_name.$key") }}</span>
                            @endif                    
                        </div>
                    </div>                    
                </div>                
            @endforeach
        </div>
        <div class="form-group {{ ($errors->has('category_image')) ? 'has-error' : '' }}">
            <div class="col-md-12">
                {{ Form::label("category_image", __('admincrud.Category Image')) }}
                {{ Form::file('category_image', ['class' => 'form-control','placeholder' => __('admincrud.Category Image')]  ) }}
                @if($errors->has("category_image"))
                    <span class="help-block error-help-block">{{ $errors->first("category_image") }}</span>
                @endif 
            </div>
        </div>

        

         <!--tab-pane-->  
        <div class="form-group {{ ($errors->has("sort_no")) ? 'has-error' : '' }}">
            <div class="col-sm-12">
                {{ Form::label('sort_no', __('admincrud.Sort No')), ['class' => 'required']}}            
                {{ Form::text('sort_no', $model->sort_no, ['class' => 'form-control']) }}
                @if($errors->has("sort_no"))
                    <span class="help-block error-help-block">{{ $errors->first("sort_no") }}</span>
                @endif
            </div>
        </div>  
        <div class="col-md-12">
            <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">
                {{ Form::label("status", __('admincommon.Status'),['class' => 'required']) }}
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
        {{ Html::link(route('category.index'), __('admincommon.Cancel'),['class' => 'btn btn-default']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\CategoryRequest','#category-form')  !!}

<script>
$(document).ready(function(){
    $('input[type=radio][name=is_main_category]').change(function() {
        var checkedValue = $("input[type=radio][name=is_main_category]:checked").val();    
        if(checkedValue == {{SUB_CATEGORY}}){
            $('#main_category_id').removeClass('hide');
        }else{
            $('#main_category_id').addClass('hide');
        }
        
    });
 
}); 
</script>