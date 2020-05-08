<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.User Management'); ?></h1>
            <div class="top-action">
                <a href="<?php echo route('user.create'); ?>" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincommon.Add New'); ?></a>
            </div>
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.Name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.User Name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.Email'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.Phone Number'); ?></th>
                        <th class="status"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                        <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>
                            <?php echo e(Form::text("name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Name'), "data-name" => "1"])); ?>                         
                        </th>
                        <th>
                            <?php echo e(Form::text("username", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.User Name'), "data-name" => "2"])); ?>                         
                        </th>
                        <th>
                            <?php echo e(Form::text("email", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Email'), "data-name" => "3"])); ?>                         
                        </th>
                        <th>
                            <?php echo e(Form::text("phone_number", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Phone Number'), "data-name" => "4"])); ?>                         
                        </th>
                        <th class="status">
                            <?php echo e(Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "5"] )); ?>

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
        'ajax' : "<?php echo e(route('user.index')); ?>",
        'columns': [
            { 'data' : 'DT_RowIndex', 'name' : 'user_id', 'searchable' : false},
            { 'data' : 'name','name' : 'first_name',},
            { 'data' : 'username'},
            { 'data' : 'email'},
            { 'data' : 'phone_number'},
            { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
            { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
        ],     
    });    
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>