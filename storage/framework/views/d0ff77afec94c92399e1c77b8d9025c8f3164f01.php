<?php $__env->startSection('content'); ?>
<div class="content-wrapper error_page">
    <section class="content">
        <img src="<?php echo e(asset('resources/assets/admin/images/404.png')); ?>" class="mb30">
        <h2>OOPS, SORRY WE CAN'T FIND THAT PAGE!</h2>
        <p>Either something went wrong or the page doesn't exist anymore.</p>
    </section>
</div> <!--content-wrapper-->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>