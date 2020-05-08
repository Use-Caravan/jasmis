<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Branch Management'); ?></h1>
            <div class="top-action">
                <a href="<?php echo route('branch.create'); ?>" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincommon.Add New'); ?></a>
            </div>
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>
                        <?php if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR): ?>
                        <th><?php echo app('translator')->getFromJson('admincrud.Branch Name'); ?></th>
                        <?php endif; ?>
                        <?php if(APP_GUARD == GUARD_ADMIN): ?>
                        <th><?php echo app('translator')->getFromJson('admincrud.Vendor Name'); ?></th>
                        <?php endif; ?>
                        <th><?php echo app('translator')->getFromJson('admincrud.Area Name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Availability Status'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Approved Status'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Created Date'); ?></th>
                        <th class="status"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                        <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
                    </tr>
                    <tr>
                        <th></th>
                        <?php if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR): ?>
                        <th>
                            <?php echo e(Form::text("branch_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Branch Name'), "data-name" => "1"])); ?>                         
                        </th>
                        <?php endif; ?>
                        <?php if(APP_GUARD == GUARD_ADMIN): ?>
                        <th>
                            <?php echo e(Form::text("vendor_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Vendor Name'), "data-name" => "2"])); ?>                         
                        </th>
                        <?php endif; ?>
                        <th>
                            <?php echo e(Form::text("area_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Area Name'), "data-name" => "3"])); ?>                         
                        </th>
                        <th class="status">
                            <?php echo e(Form::select('availability_status',$model->availablityStatus(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "4"] )); ?>

                        </th>
                        <th>
                            <?php echo e(Form::select('approved_status', $model->approvedStatus(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "5"] )); ?> 
                        </th>
                         <th>
                            <?php echo e(Form::text("created_at", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Created Date'), "data-name" => "6"])); ?>                         
                        </th>
                        <th class="status">
                            <?php echo e(Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "7"] )); ?>

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
      'ajax' : "<?php echo e(route('branch.index')); ?>",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'branch_id', 'searchable' : false},
        <?php if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR): ?>
        { 'data' : 'branch_name', 'name' : 'BL.branch_name'},
        <?php endif; ?>
        <?php if(APP_GUARD == GUARD_ADMIN): ?>
        { 'data' : 'vendor_name', 'name' : 'VL.vendor_name'},
        <?php endif; ?>
        { 'data' : 'area_name','name' : 'AL.area_name'},
        { 'data' : 'availability_status'},
        { 'data' : 'approved_status'},
        { 'data' : 'created_at',},  
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
     
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>