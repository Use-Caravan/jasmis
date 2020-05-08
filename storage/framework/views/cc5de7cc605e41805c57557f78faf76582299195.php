<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Delivery Area Management'); ?></h1>
            <div class="top-action">
                <a href="<?php echo route('delivery-area.create'); ?>" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincommon.Add New'); ?></a>
            </div>
        </div> <!--box-header-->

        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                  <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>                  
                  <th><?php echo app('translator')->getFromJson('admincrud.City Name'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.Area Name'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.Delivery Area Name'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.Zone Type'); ?></th>
                  <th class="status"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                  <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
              </tr>
              <tr>
                <th></th>
                <th>
                    <?php echo e(Form::text("city_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.City Name'), "data-name" => "1"])); ?>                         
                </th>
                <th>
                    <?php echo e(Form::text("area_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Area Name'), "data-name" => "2"])); ?>                         
                </th>
                <th>
                    <?php echo e(Form::text("delivery_area_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Delivery Area Name'), "data-name" => "3"])); ?>                         
                </th>
                <th>
                    <?php echo e(Form::select('zone_type', $model->getZonetype(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "4"] )); ?>

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
      'ajax' : "<?php echo e(route('delivery-area.index')); ?>",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'area_id', 'searchable' : false},        
        { 'data' : 'city_name', 'name' : 'CL.city_name'},
        { 'data' : 'area_name', 'name' : 'AL.area_name' },
        { 'data' : 'delivery_area_name', 'name' : 'DAL.delivery_area_name' },
        { 'data' : 'zone_type',},
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
      
    });    
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>