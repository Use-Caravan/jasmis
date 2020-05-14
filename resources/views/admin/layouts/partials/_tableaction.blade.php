@switch($type)
    @case(TYPE_STATUS_COLUMN)
           @if(method_exists($model,'uniqueKey'))
            <label class="switch" for="id_{{$model->{$model::uniqueKey()} }}">
                <input type="checkbox" itemkey="{{$model->{$model::uniqueKey()} }}" action="{{ route($route) }}" class="SwitchStatus" id="id_{{$model->{$model::uniqueKey()} }}" @if( $model->status === ITEM_ACTIVE ) checked="true" @endif >
                <span class="slider"></span>
            </label>                
            @else
            <label class="switch" for="id_{{$model->{$model::primaryKey()} }}">
                <input type="checkbox" itemkey="{{$model->{$model::primaryKey()} }}" action="{{ route($route) }}" class="SwitchStatus" id="id_{{$model->{$model::primaryKey()} }}" @if( $model->status === ITEM_ACTIVE ) checked="true" @endif >
                <span class="slider"></span>
            </label>
            @endif
            
        @break
        @case(TYPE_POPULARSTATUS_COLUMN)
           @if(method_exists($model,'uniqueKey'))
            <label class="switch" for="popid_{{$model->{$model::uniqueKey()} }}">
                <input type="checkbox" itemkey="{{$model->{$model::uniqueKey()} }}" action="{{ route($route) }}" class="SwitchPopular" id="popid_{{$model->{$model::uniqueKey()} }}" @if( $model->popular_status === ITEM_ACTIVE ) checked="true" @endif >
                <span class="slider"></span>
            </label>                
            @else
            <label class="switch" for="popid_{{$model->{$model::primaryKey()} }}">
                <input type="checkbox" itemkey="{{$model->{$model::primaryKey()} }}" action="{{ route($route) }}" class="SwitchPopular" id="popid_{{$model->{$model::primaryKey()} }}" @if( $model->popular_status === ITEM_ACTIVE ) checked="true" @endif >
                <span class="slider"></span>
            </label>
            @endif
            
        @break

        @case(TYPE_QUICKBUYSTATUS_COLUMN)
           @if(method_exists($model,'uniqueKey'))
             
            <label class="switch" for="quickid_{{$model->{$model::uniqueKey()} }}">
                <input type="checkbox" itemkey="{{$model->{$model::uniqueKey()} }}" action="{{ route($route) }}" class="SwitchQuickbuy" id="quickid_{{$model->{$model::uniqueKey()} }}" @if( $model->quickbuy_status === ITEM_ACTIVE ) checked="true" @endif >
                <span class="slider"></span>
            </label>            
            @else
             
             <label class="switch" for="quickid_{{$model->{$model::primaryKey()} }}">
                <input type="checkbox" itemkey="{{$model->{$model::primaryKey()} }}" action="{{ route($route) }}" class="SwitchQuickbuy" id="quickid_{{$model->{$model::primaryKey()} }}" @if( $model->quickbuy_status === ITEM_ACTIVE ) checked="true" @endif >
                <span class="slider"></span>
            </label>  
            @endif
            
        @break
    @case(TYPE_ACTION_COLUMN)
            @if($isdelete == true)
                <a href="javascript:"
                @foreach($attributes as $key => $value)
                    {{$key}}="{{$value}}"
                @endforeach
                >
                    {!! $title !!}
                </a>

                <form action="{{ route($route,$params) }}" id="deleteForm{{ $model->cuisine_key  }}" method="post">
                @csrf
                @method('DELETE')
                </form>

            @else

                <a href="{{ route($route,$params) }}" 
                @foreach($attributes as $key => $value)
                    {{$key}}="{{$value}}"
                @endforeach
                >
                    {!! $title !!}
                </a>

            @endif    
        @break
    @case(APPROVED_STATUS_COLUMN)
        {{ Form::select('approved_status', $approvedStatus, $model->approved_status ,['class' => 'selectpicker approvedStatuss',"action" => route($route), "id" => $model->{$model::uniqueKey()} ] )}}
    @break
@endswitch