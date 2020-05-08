<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Delivery Charge Management'); ?></h1>
            <div class="top-action">
                <a href="<?php echo route('deliverycharge.create'); ?>" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincommon.Add New'); ?></a>
            </div>
        </div> <!--box-header-->

        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                  <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>                  
                  <th><?php echo app('translator')->getFromJson('admincrud.From Kilometer'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.To Kilometer'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.Price'); ?></th>
                  <th class="status"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                  <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
              </tr>
              <tr>
                <th></th>
                <th>
                    <?php echo e(Form::text("from_km", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.From Kilometer'), "data-name" => "1"])); ?>                         
                </th>
                <th>
                    <?php echo e(Form::text("to_km", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.To Kilometer'), "data-name" => "2"])); ?>                         
                </th>
                <th>
                    <?php echo e(Form::text("price", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Price'), "data-name" => "3"])); ?>                         
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
      'ajax' : "<?php echo e(route('deliverycharge.index')); ?>",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'delivery_charge_id', 'searchable' : false},        
        { 'data' : 'from_km', 'name' : 'from_km'},
        { 'data' : 'to_km', 'name' : 'to_km' },
        { 'data' : 'price', 'name' : 'price' },
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
      
    });   
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>