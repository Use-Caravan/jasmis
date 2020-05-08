<?php if($message = Session::get('success')): ?>
<div class="flash-message">
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        
        <?php echo e($message); ?>

    </div>
</div>
<?php endif; ?>

<?php if(session('status')): ?>
<div class="flash-message">
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        
        <?php echo e(session('status')); ?>

    </div>
</div>   
<?php endif; ?>

<?php if($message = Session::get('error')): ?>
<div class="flash-message">
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	    
        
        <?php echo e(session('error')); ?>

    </div>
</div>
<?php endif; ?>


<?php if($message = Session::get('warning')): ?>
<div class="flash-message">
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        
        <?php echo e($message); ?>

    </div>
</div>
<?php endif; ?>


<?php if($message = Session::get('info')): ?>
<div class="flash-message">
    <div class="alert alert-info alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        
        <?php echo e($message); ?>

    </div>
</div>
<?php endif; ?>


<?php if($errors->any()): ?>
<div class="flash-message">
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
        
        <?php echo app('translator')->getFromJson('admincommon.Please check the form below for errors'); ?>
    </div>
</div>
<?php endif; ?>