<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
          <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Offer Management'); ?></h1>
          <div class="top-action">
            <a href="<?php echo route('offer.create'); ?>" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincommon.Add New'); ?></a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                  <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.Offer Name'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.Offer Type'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.Offer Value'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.Start Date'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.End Date'); ?></th>
                  <th class="status"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                  <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
              </tr>
              <tr>
                <th></th>
                <th>
                    <?php echo e(Form::text("promo_code", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Promo Code'), "data-name" => "1"])); ?>                         
                </th>
                <th>
                    <?php echo e(Form::text("value", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Value'), "data-name" => "2"])); ?>                         
                </th>
                <th></th>
                <th></th>
                <th></th>                
                <th class="status">
                    <?php echo e(Form::select('status', Common::status(), '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "6"] )); ?>

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
      'ajax' : "<?php echo e(route('offer.index')); ?>",
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'offer_id', 'searchable' : false},
        { 'data' : 'offer_name', 'name' : 'OL.offer_name'},
        { 'data' : 'offer_type', 'name' : 'offer_type'},
        { 'data' : 'offer_value', 'name' : 'offer_value'},
        { 'data' : 'start_datetime'},        
        { 'data' : 'end_datetime'},        
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],
      
    });    
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>