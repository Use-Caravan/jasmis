<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{!! config('webconfig.app_name') !!}</title>
  <link rel="shortcut icon" href="{{ FileHelper::loadImage(config('webconfig.app_favicon')) }}" type="image/x-icon" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="_url" content="{{url('/')}}" />
  <meta name="_routeName" content="{{  strstr(Route::currentRouteName(), '.' , true) }}" />
  <meta name="_currencySymbol" content="{{  config('webconfig.currency_symbol') }}" />
  <meta name="_currencyPosition" content="{{  config('webconfig.currency_position') }}" />
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  {!! AssetHelper::loadAdminAsset(1) !!}
  <script src="{{ asset('resources/assets/general/ajax-init.js')}}"></script>
  <!--[if lt IE 9]>
  <script src="js/html5shiv.min.js"></script>
  <![endif]-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>



<body class="hold-transition skin-blue fixed sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <header class="main-header">    
    <a href="{{ route('admin-dashboard') }}" class="logo"><img src="{{ FileHelper::loadImage(config('webconfig.app_logo')) }}"></a>    
    <nav class="navbar navbar-static-top">      
      <a class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
      </a>      
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li>
            {{Form::open( ['url' => route('change-language'), 'method' => 'POST', 'id' => "changeLanguageForm" ] )  }}                
                {{ Form::select('language', $languages, App::getLocale(), [ 'class' => 'selectpicker', "onchange" => "$('#changeLanguageForm').submit()" ] ) }}
            {{Form::close()}}
          </li>
          <li class="dropdown user user-menu">
            <a class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?= asset('resources/assets/admin/images/user-small.png') ?>" class="user-image"> 
              <span class="hidden-xs">@lang('admincommon.Hi',['name' => Auth::guard(APP_GUARD)->user()->username])</span>
            </a>          
            <ul class="dropdown-menu">              
              <li class="user-header">
                <img src="<?= asset('resources/assets/admin/images/user.png') ?>" class="img-circle">
                <p>{{ Auth::guard(APP_GUARD)->user()->username }}</p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                @if(APP_GUARD === GUARD_ADMIN)
                    <a href="{{ route('admin-user.show',['id' => auth()->guard(APP_GUARD)->user()->admin_user_key ]) }}" class="btn">@lang('admincommon.Profile')</a>
                @elseif(APP_GUARD === GUARD_VENDOR)
                    <a href="{{ route('vendor.edit',['id' => auth()->guard(APP_GUARD)->user()->vendor_key ]) }}" class="btn">@lang('admincommon.Profile')</a>
                @elseif(APP_GUARD === GUARD_OUTLET)
                    <a href="{{ route('branch.edit',['id' => auth()->guard(APP_GUARD)->user()->admin_user_key ]) }}" class="btn">@lang('admincommon.Profile')</a>
                @endif
                </div>                
                <div class="pull-right">
                  <a href="javascrip:" data-toggle="modal" data-target="#logout_conform" class="btn grey" >@lang('admincommon.Sign out')</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

<div class="modal fade conform_logout" id="logout_conform" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">   
       <h3> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Alert</h3>
       
      </div>
     <div class="modal-body">
       <p>Do You Want to logout?</p>
 
       </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="<?= route('admin-logout') ?>" class="btn btn-primary">Ok</a>
      </div>
    </div>
  </div>
</div>
