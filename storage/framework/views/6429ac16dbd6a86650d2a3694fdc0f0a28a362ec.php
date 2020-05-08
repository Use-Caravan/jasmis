<?php echo $__env->make('admin.layouts._header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<aside class="main-sidebar">
    <section class="sidebar full_row">
      <ul class="sidebar-menu" data-widget="tree">
        <?php echo $__env->make('admin.layouts._sideBar', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      </ul>
    </section>
  </aside>
<?php echo $__env->yieldContent('content'); ?>
<?php echo $__env->make('admin.layouts._footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
