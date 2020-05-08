<?php $__env->startSection('content'); ?>

<?php
  $url = route('category.store');
?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-offset-2 col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
          <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <h3 class="box-title"><?php echo app('translator')->getFromJson('admincommon.Create'); ?></h3>
          </div>
          <?php echo $__env->make('admin.category._form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>