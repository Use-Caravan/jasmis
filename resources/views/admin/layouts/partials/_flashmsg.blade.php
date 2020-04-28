@if ($message = Session::get('success'))
<div class="flash-message">
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        {{-- <strong>@lang('admincommon.Success')</strong>  --}}
        {{ $message }}
    </div>
</div>
@endif

@if (session('status'))
<div class="flash-message">
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        {{-- <strong>@lang('admincommon.Success')</strong>  --}}
        {{ session('status') }}
    </div>
</div>   
@endif

@if ($message = Session::get('error'))
<div class="flash-message">
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	    
        {{-- <strong>@lang('admincommon.Error')</strong>  --}}
        {{ session('error') }}
    </div>
</div>
@endif


@if ($message = Session::get('warning'))
<div class="flash-message">
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        {{-- <strong>@lang('admincommon.Warning')</strong> --}}
        {{ $message }}
    </div>
</div>
@endif


@if ($message = Session::get('info'))
<div class="flash-message">
    <div class="alert alert-info alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        {{-- <strong>@lang('admincommon.Info')</strong> --}}
        {{ $message }}
    </div>
</div>
@endif


@if ($errors->any())
<div class="flash-message">
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        {{-- <strong>@lang('admincommon.Validation Error')</strong>  --}}
        @lang('admincommon.Please check the form below for errors')
    </div>
</div>
@endif