{{ Form::open(['url' => $url, 'id' => 'cms-form', 'class' => 'form-horizontal', 'method' => ($model->exists) ? 'PUT' : 'POST','enctype' => "multipart/form-data" ]) }}

    <div class="box-body">
        
        <ul class="nav nav-tabs full_row">
            @foreach ($languages as $key => $language)                             
                
                <li @if($key == App::getLocale()) class="active" @endif haserror="{{ $errors->has("title.$key").$errors->has("keywords.$key").$errors->has("description.$key").$errors->has("cms_content.$key") }}"> 
                    <a data-toggle="tab" href="#tab{{$key}}">{{ $language }} </a>
                </li>
            @endforeach
            <li @if($key == App::getLocale()) class="active" @endif haserror="{{ $errors->has("title.$key").$errors->has("keywords.$key").$errors->has("description.$key").$errors->has("cms_content.$key") }}"> 
                    <a data-toggle="tab" href="#tab_image">Image </a>
            </li>
        </ul>
        <div class="tab-content full_row">
            @foreach ($languages as $key => $language)
            <div id="tab{{$key}}" class="tab-pane fade @if($key == App::getLocale()) active in @endif">
                <div class="form-group {{ ($errors->has("title.$key")) ? 'has-error' : '' }}"> 
                    <div class="col-sm-12">                   
                        {{ Form::label("title[$key]", __('admincrud.Title'), ['class' => 'required']) }}
                        {{ Form::text("title[$key]", $modelLang['title'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("title.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("title.$key") }}</span>
                        @endif                    
                    </div>
                </div>
                <div class="form-group {{ ($errors->has("keywords.$key")) ? 'has-error' : '' }}"> 
                    <div class="col-sm-12">                   
                        {{ Form::label("keywords[$key]", __('admincrud.Keywords'), ['class' => 'required']) }}
                        {{ Form::textarea("keywords[$key]", $modelLang['keywords'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("keywords.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("keywords.$key") }}</span>
                        @endif                    
                    </div>
                </div>
                <div class="form-group {{ ($errors->has("description.$key")) ? 'has-error' : '' }}"> 
                    <div class="col-sm-12">                   
                        {{ Form::label("description[$key]", __('admincrud.Description'), ['class' => 'required']) }}
                        {{ Form::textarea("description[$key]", $modelLang['description'][$key], ['class' => 'form-control']) }}
                        @if($errors->has("description.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("description.$key") }}</span>
                        @endif                    
                    </div>
                </div>
                <div class="form-group {{ ($errors->has("cms_content.$key")) ? 'has-error' : '' }}"> 
                    <div class="col-sm-12">                   
                        {{ Form::label("cms_content[$key]", __('admincrud.Cms Content'), ['class' => 'required']) }}
                        {!! Form::textarea("cms_content[$key]", $modelLang['cms_content'][$key], ['class' => 'ckEditor form-control','id' => 'cms_content']) !!}
                        @if($errors->has("cms_content.$key"))
                            <span class="help-block error-help-block">{{ $errors->first("cms_content.$key") }}</span>
                        @endif                    
                    </div>
                </div>
            </div> 
            <!--tab-pane-->
            @endforeach
            <div id="tab_image" class="form-group {{ ($errors->has("section")) ? 'has-error' : '' }}">
                <div class="col-md-12">
                    {{ Form::label("section", __('admincrud.Sections'), ['class' => 'required']) }} 
                    {{ Form::select('section[]', $model->selectsections(),$sectionslist ,['class' => 'selectpicker','title' => __('admincrud.Please Choose Sections')] )}}
                        @if($errors->has("section"))
                            <span class="help-block error-help-block">{{ $errors->first("section") }}</span>
                        @endif 
                </div>
                                   
                <div class="col-md-12 ">                          
                    {{ Form::label("ldpi_image_path", __('admincrud.ldpi'), ['class' => (!$model->exists) ? 'required' : '']) }}
                    {{ Form::file("ldpi_image_path", ['class' => 'form-control',"accept" => "image/*"]) }}
                    @if($errors->has("ldpi_image_path"))
                        <span class="help-block error-help-block">{{ $errors->first("ldpi_image_path") }}</span>
                    @endif  
                </div>
                
                                   
                <div class="col-md-12">                          
                    {{ Form::label("mdpi_image_path", __('admincrud.mdpi'), ['class' => (!$model->exists) ? 'required' : '']) }}
                    {{ Form::file("mdpi_image_path", ['class' => 'form-control',"accept" => "image/*"]) }}
                    @if($errors->has("mdpi_image_path"))
                        <span class="help-block error-help-block">{{ $errors->first("mdpi_image_path") }}</span>
                    @endif  
                </div>
                
                                   
                <div class="col-md-12">                          
                    {{ Form::label("hdpi_image_path", __('admincrud.hdpi'), ['class' => (!$model->exists) ? 'required' : '']) }}
                    {{ Form::file("hdpi_image_path", ['class' => 'form-control',"accept" => "image/*"]) }}
                    @if($errors->has("ldpi_image_path"))
                        <span class="help-block error-help-block">{{ $errors->first("hdpi_image_path") }}</span>
                    @endif  
                </div>
                
                                   
                <div class="col-md-12">                          
                    {{ Form::label("xhdpi_image_path", __('admincrud.xhdpi'), ['class' => (!$model->exists) ? 'required' : '']) }}
                    {{ Form::file("xhdpi_image_path", ['class' => 'form-control',"accept" => "image/*"]) }}
                    @if($errors->has("xhdpi_image_path"))
                        <span class="help-block error-help-block">{{ $errors->first("xhdpi_image_path") }}</span>
                    @endif  
                </div>
                
                                  
                <div class="col-md-12">                          
                    {{ Form::label("xxhdpi_image_path", __('admincrud.xxhdpi'), ['class' => (!$model->exists) ? 'required' : '']) }}
                    {{ Form::file("xxhdpi_image_path", ['class' => 'form-control',"accept" => "image/*"]) }}
                    @if($errors->has("xxhdpi_image_path"))
                        <span class="help-block error-help-block">{{ $errors->first("xxhdpi_image_path") }}</span>
                    @endif  
                </div>
                
                                   
                <div class="col-md-12">                          
                    {{ Form::label("xxxhdpi_image_path", __('admincrud.xxhdpi'), ['class' => (!$model->exists) ? 'required' : '']) }}
                    {{ Form::file("xxxhdpi_image_path", ['class' => 'form-control',"accept" => "image/*"]) }}
                    @if($errors->has("xxxhdpi_image_path"))
                        <span class="help-block error-help-block">{{ $errors->first("xxxhdpi_image_path") }}</span>
                    @endif  
                </div>
                
            </div>
            
            
                
                
            
        </div>
        <div class="form-group {{ ($errors->has("sort_no")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">                          
                {{ Form::label("sort_no", __('admincrud.Sort No')) }}
                {{ Form::text("sort_no", $model->sort_no, ['class' => 'form-control']) }} 
                @if($errors->has("sort_no"))
                    <span class="help-block error-help-block">{{ $errors->first("sort_no") }}</span>
                @endif                    
            </div>
        </div>
        <div class="form-group radio_group{{ ($errors->has("status")) ? 'has-error' : '' }}">                    
            <div class="col-md-12">
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
        {{ Html::link(route('cms.index'), __('admincommon.Cancel'),['class' => 'btn btn-default active']) }}        
        {{ Form::submit($model->exists ? __('admincommon.Update') : __('admincommon.Save'), ['class' => 'btn btn-info pull-right']) }}
    </div>
  <!-- /.box-footer -->
{{ Form::close() }}

{!! JsValidator::formRequest('App\Http\Requests\Admin\CmsRequest', '#cms-form')  !!}
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
<script>
    //CKEDITOR.replace( 'cms_content' );
    
    var elements = CKEDITOR.document.find( '.ckEditor' ),
    i = 0,
    element;
    while ( ( element = elements.getItem( i++ ) ) ) {
        
        CKEDITOR.replace( element );
        
               
    }
</script>   