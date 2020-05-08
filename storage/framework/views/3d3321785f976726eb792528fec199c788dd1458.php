<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
          <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Banner Management'); ?></h1>
        <div class="top-action">
            <a href="<?php echo route('banner.create'); ?>" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincommon.Add New'); ?></a>
          </div>
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
                <tr>
                    <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>
                    <th><?php echo app('translator')->getFromJson('admincrud.Banner Name'); ?></th>
                    <th><?php echo app('translator')->getFromJson('admincrud.Banner Image'); ?></th>
                    <th><?php echo app('translator')->getFromJson('admincrud.Redirect URL'); ?></th>
                    <th class="status"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                    <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
                </tr>
                <tr>
                    <th></th>
                    <th>
                        <?php echo e(Form::text("banner_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Banner Name'), "data-name" => "1"])); ?>                         
                    </th>
                    <th> </th>
                    <th>
                        <?php echo e(Form::text("redirect_url", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Redirect URL'), "data-name" => "3"])); ?>                         
                    </th>   
                    <th class="status">
                        <?php echo e(Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "4"] )); ?>

                    </th>
                    <th class="action"></th>
                </tr>
              </thead>
            </table>
          </div>
        </div> <!--box-body-->
    </div> <!--box-->
  </section>
</div> <!--content-wrapper-->
<?php echo $__env->make('admin.layouts.partials._tableconfig', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script type="text/javascript">
$(document).ready(function(){
    window.dataTable = $('#dataTable').dataTable({
      'ajax' : "<?php echo e(route('banner.index')); ?>",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'banner_id', 'searchable' : false},
        { 'data' : 'banner_name', 'name' : 'BL.banner_name'},
        { 'data' : 'banner_file','searchable' : false, 'sortable' : false},
        { 'data' : 'redirect_url','name':'redirect_url'},
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
      
    });    
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>