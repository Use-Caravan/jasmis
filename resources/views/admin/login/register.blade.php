@php exit; @endphp
<!DOCTYPE html>
<html class="login_page">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= config('app.name') ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
  {!! AssetHelper::loadAdminAsset(1) !!}
  <!--[if lt IE 9]>
  <script src="js/html5shiv.min.js"></script>
  <![endif]-->
</head>
<body>

<div class="container sm">
  <h1><?= config('app.name') ?> - Login</h1>
  
  {!! Form::open(['url' => route('admin-register'), 'id' => 'login-form', 'class' => 'form']) !!}
    <div class="form-group icon">
      {!! Form::text('username', Old('username'),['class' => 'form-control', 'placeholder' => 'Email'] ) !!}
      <i class="fa fa-user-o"></i>
    </div>
    <div class="form-group icon">
      {!! Form::text('email', Old('email'), ['class' => 'form-control', 'placeholder' => 'Email'] ) !!}
      <i class="fa fa-lock"></i>
    </div>
    <div class="form-group icon">
      {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password'] ) !!}
      <i class="fa fa-lock"></i>
    </div>
    <div class="form-group action mb0">
      {{ Form::button('Sign In <i class="fa fa-angle-right"></i>', ['type' => 'submit', 'class' => 'btn full'] )  }}
    </div>
  {!! Form::close() !!}

</div> <!--container-->

<!-- JS -->


{!! AssetHelper::loadAdminAsset() !!}


</body>
</html>
