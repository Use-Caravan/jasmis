<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.User Address Management'); ?></h1>
            
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.Name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.User Name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Address Type'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.Address'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.Email'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Landmark'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Company'); ?></th>
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
                            <?php echo e(Form::text("address_type_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Address Type'), "data-name" => "3"])); ?>                         
                        </th>
                        <th>
                            <?php echo e(Form::text("address", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Address'), "data-name" => "4"])); ?>                         
                        </th>
                        <th>
                            <?php echo e(Form::text("email", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Email'), "data-name" => "5"])); ?>                         
                        </th>
                        <th>
                            <?php echo e(Form::text("landmark", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Landmark'), "data-name" => "6"])); ?>                         
                        </th>
                        <th>
                            <?php echo e(Form::text("company", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Company'), "data-name" => "7"])); ?>                         
                        </th>                        
                        <th class="status">
                            <?php echo e(Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "8"] )); ?>

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
      'ajax' : "<?php echo e(route('useraddress.index')); ?>",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'user_address_id', 'searchable' : false},
        { 'data' : 'name','name' : 'user.first_name',},
        { 'data' : 'username','name' : 'user.username',},
        { 'data' : 'address_type_name','name' : 'ATL.address_type_name',},
        { 'data' : 'address','name' : 'address_line_one',},
        { 'data' : 'email','name' : 'user.email'}, 
        { 'data' : 'landmark'},
        { 'data' : 'company'},
        
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
     
    }); 
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>