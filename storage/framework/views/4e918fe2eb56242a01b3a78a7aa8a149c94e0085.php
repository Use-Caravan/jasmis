<?php $__env->startSection('content'); ?>
<div class="content-wrapper error_page">
    <section class="content">
        <img src="<?php echo e(asset('resources/assets/admin/images/forbidden.png')); ?>" class="mb25">
        <h2>Access Denied / Forbidden</h2>
        <p>This page or resource you were trying to reach is absolutely forbidden for some reason.</p>
    </section>
</div> <!--content-wrapper-->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>