<!DOCTYPE html>
<html class="login_page">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= config('app.name') ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <?php echo AssetHelper::loadAdminAsset(1); ?>


</head>
<body>

<div class="container sm">
<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

 <h1><?= config('app.name') ?> - Login</h1>
  <?php echo Form::open(['url' => route('frontend.reset-password'), 'method' => 'POST', 'id' => 'reset-form', 'class' => 'form']); ?>

  <input type="hidden" name="token" value="<?php echo e($token); ?>">
    <div class="form-group icon">
      <?php echo Form::text('email', $email,['class' => 'form-control', 'placeholder' => 'Email'] ); ?>

      <i class="fa fa-user-o"></i>
    </div>
    <div class="form-group icon">
      <?php echo Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password'] ); ?>

      <i class="fa fa-lock"></i>
    </div>
    <div class="form-group icon">
      <?php echo Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password'] ); ?>

      <i class="fa fa-lock"></i>
    </div>
    <div class="form-group action mb0">
      <?php echo e(Form::button('Reset Password <i class="fa fa-angle-right"></i>', ['type' => 'submit', 'class' => 'btn full'] )); ?>

    </div>
  <?php echo Form::close(); ?>  
</div> <!--container-->

<!-- JS -->
<?php echo JsValidator::formRequest('App\Http\Requests\Admin\ResetPasswordRequest', '#reset-form'); ?>


<?php echo AssetHelper::loadAdminAsset(); ?>


</body>
</html>
