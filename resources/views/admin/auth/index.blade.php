<!DOCTYPE html>
<html class="login_page">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> {{ config('app.name') }}</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">  
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="_url" content="{{url('/')}}" />
  <meta name="_routeName" content="{{  strstr(Route::currentRouteName(), '.' , true) }}" />
  {!! AssetHelper::loadAdminAsset(1) !!}
  <!--[if lt IE 9]>
  <script src="js/html5shiv.min.js"></script>
  <![endif]-->
</head>
<body>

<div class="container sm authlogin">
  <h1>{{ (APP_GUARD === GUARD_ADMIN) ? 'Admin' : ( (APP_GUARD === GUARD_VENDOR) ? 'Vendor' : ((APP_GUARD === GUARD_OUTLET) ? 'Branch' : '' )) }} - Login</h1>
  @include('admin.layouts.partials._flashmsg')
  {!! Form::open(['url' => route('admin-login'), 'id' => 'login-form', 'class' => 'form']) !!}
    <div class="form-group icon">
      {!! Form::text('username', Old('username'),['class' => 'form-control', 'placeholder' => 'Email'] ) !!}
      <i class="fa fa-user-o"></i>
    </div>
    <div class="form-group icon">
      {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password'] ) !!}
      <i class="fa fa-lock"></i>
    </div>
    <div class="form-group action mb0">
      {{ Form::button('Sign In <i class="fa fa-angle-right"></i>', ['type' => 'submit', 'class' => 'btn full'] )  }}
    </div>
  {!! Form::close() !!}

  <a data-toggle="modal" data-target="#modal_forgot" class="forgot"><i class="fa fa-lock"></i> Forgot Password?</a>
</div> <!--container-->

<!-- modal_forgot -->
<div id="modal_forgot" class="modal fade" role="dialog" data-backdrop="static">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Forgot Password?</h4>
        <i class="fa fa-close" data-dismiss="modal"></i>
      </div><!--modal-header-->
      
      {!! Form::open(['url' => route('admin-send-reset-link'), 'id' => 'forgot-form', 'class' => 'modal-body full_row']) !!}
        <p>Please enter the registered email address to <br>reset the password</p>
        <div class="form-group icon">
          {!! Form::text('email', Old('email'),['class' => 'form-control', 'placeholder' => 'Email'] ) !!}
          <i class="fa fa-envelope-o"></i>
        </div>              
        <div class="form-group action">
          {{ Form::button('Submit <i class="fa fa-angle-right"></i>', ['type' => 'submit', 'class' => 'btn full', 'id' => 'forgot-btn'] )  }}
        </div>
      {!! Form::close() !!} <!--modal-body-->
    </div><!--modal-content-->
  </div><!--modal-dialog-->
</div><!--modal_forgot-->  

<!-- JS -->
{!! JsValidator::formRequest('App\Http\Requests\Admin\LoginRequest', '#login-form') !!}

{{-- JsValidator::formRequest('App\Http\Requests\Admin\ForgotPasswordRequest', '#forgot-form') --}}

{!! AssetHelper::loadAdminAsset() !!}

<script type="text/javascript">
$(document).ready(function() {
    $('#forgot-form').on('submit', function(e) {                
        e.preventDefault();
        $.ajax({
            type : 'POST',
            url : $('#forgot-form').attr('action'),
            data : $('#forgot-form').serialize(),
            success:function(response){
                if(response.status == {{AJAX_FAIL}}) {
                    errorNotify(response.errors.email);
                } else {  
                    //$('#forgot-form').submit();   
                   $('#forgot-form').unbind('submit').submit();
                }                        
            }
        });
    });
});
</script>

</body>
</html>
