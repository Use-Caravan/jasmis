<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Order Management'); ?></h1>
           
        </div> <!--box-header-->
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.Name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Order Number'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Branch Name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincommon.Email'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Payment Type'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Payment Status'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Order Status'); ?></th>
                        <th class="status"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                        <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>
                            <?php echo e(Form::text("name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Name'), "data-name" => "1"])); ?>                         
                        </th>
                       <th>
                            <?php echo e(Form::text("order_number", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Order Number'), "data-name" => "2"])); ?>                         
                        </th>
                        <th>
                            <?php echo e(Form::text("branch_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Branch Name'), "data-name" => "3"])); ?>                         
                        </th>
                       
                        <th>
                            <?php echo e(Form::text("email", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Email'), "data-name" => "4"])); ?>                         
                        </th>
                        <th>
                            <?php echo e(Form::select('payment_type', $model->paymentTypes(), '' ,['class' => 'selectpicker filterSelect ', 'placeholder' => __('admincommon.All'), "data-name" => "5"] )); ?> 
                        </th>                        
                        <th>
                            <?php echo e(Form::select('payment_status', $model->paymentStatus(), '' ,['class' => 'selectpicker filterSelect','placeholder' => __('admincommon.All'), "data-name" => "6"] )); ?> 
                        </th>
                        <th>
                            <?php echo e(Form::select('order_status', $model->approvedStatus(), '' ,['class' => 'filterSelect selectpicker', 'placeholder' => __('admincommon.All'), "data-name" => "7"] )); ?> 
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
<!-- modal_medium -->
<div id="modal_medium" class="modal fade" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Drivers List</h4>
                <i class="fa fa-close" data-dismiss="modal"></i>
            </div><!--modal-header-->            
            <div class="modal-body full_row" id="drivers_list">
                
            </div> <!--modal-body-->
        </div><!--modal-content-->
    </div><!--modal-dialog-->
</div><!--modal_medium-->
<?php echo $__env->make('admin.layouts.partials._tableconfig', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script type="text/javascript">
$(document).ready(function() {
    window.dataTable = $('#dataTable').dataTable({
      'ajax' : "<?php echo e(route('order.index')); ?>",      
      'columns': [
        { 'data' : 'DT_RowIndex', 'name' : 'order_id', 'searchable' : false},
        { 'data' : 'name','name' : 'user.first_name',},
        { 'data' : 'order_number'},
        { 'data' : 'branch_name','name' : 'BL.branch_name',},
        { 'data' : 'user_email',},
        { 'data' : 'payment_type'},
        { 'data' : 'payment_status'},
        { 'data' : 'order_status'},
        { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
        { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
      ],     
      "order": [[ 0, 'desc' ]],
    });

    setInterval(function()
    {
        window.dataTable.api().ajax.reload(null,false);
    },10000)

    $('body').on('click','.delivery-boy-assign',function()
    {
        var order_key = $(this).data('order_key');
        var deliveryboy_key = $(this).data('deliveryboy_key');        
        $.ajax({
            url: "<?php echo e(url(route('order.assign-deliveryboy'))); ?>",
            type: 'POST',
            data : { order_key : order_key,deliveryboy_key : deliveryboy_key },
            success: function(result) {
                if(result.status == <?php echo e(HTTP_SUCCESS); ?> ){
                    location.reload();
                }else{
                    errorNotify(result.message);
                }
            }
        }); 
    });
    $('body').on('change','.order_status select',function (e) {
        e.preventDefault();
        var order_key = $(this).attr('id');
        var order_status = $(this).val();
        $.ajax({
            url: "<?php echo e(url(route('order.approvedstatus'))); ?>",
            type: 'POST',
            data : { order_key : order_key,order_status : order_status },
            success: function(result) {               
                if(result.status == AJAX_SUCCESS ){
                    window.dataTable.api().ajax.reload(null,false);
                    successNotify(result.msg);
                }else{
                    errorNotify(result.msg);
                }
            }
        }); 
    });
    $('body').on('click','.assignOrder',function (e, data) {
        var orderkey = $(this).attr('orderKey');    
        $.ajax({
            url: "<?php echo e(url(route('order.get-available-deliveryboy'))); ?>",
            type: 'POST',
            data : { order_key : orderkey },
            success: function(result) {
                
                if(result.status == <?php echo e(HTTP_SUCCESS); ?> ){
                    $('#modal_medium').modal('toggle');
                    $('#drivers_list').html(result.data)
                    /* successNotify(result.msg); */
                }else{
                    errorNotify(result.msg);
                }
            }
        }); 
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>