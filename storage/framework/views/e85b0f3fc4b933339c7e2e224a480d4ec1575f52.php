<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Report Management'); ?></h1>
            <div class="top-action">
                <a exporthref="<?php echo e(route('report-export')); ?>" title="<?php echo app('translator')->getFromJson('admincrud.Export'); ?>" class="btn mb15" id="report_export"><i class="fa fa-file-excel-o"></i><?php echo app('translator')->getFromJson('admincrud.Export'); ?></a>
                
            </div>
        </div> <!--box-header-->
        
        <div class="box-body report_fil">
            <?php echo e(Form::open(['route' => 'report.index', 'id' => 'report-filter', 'class' => 'form-horizontal', 'method' => 'GET'])); ?>

            <div class="row">
                <div class="col-md-3">    
                    <?php if(APP_GUARD == GUARD_ADMIN): ?>
                        <?php echo e(Form::select('vendor_id',$vendorList, request()->vendor_id ,['class' => 'selectpicker filterSelect','id' => 'vendor', 'placeholder' => __('admincrud.Vendor Name')] )); ?> 
                    <?php endif; ?>
                </div>
                <div class="col-md-3">    
                    <?php if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR): ?>
                        <?php echo e(Form::select('branch_id', $branchList, request()->branch_id ,['class' => 'selectpicker filterSelect', 'id' => 'Vendor-branch_id','placeholder' => __('admincrud.Branch Name')])); ?> 
                    <?php endif; ?>
                </div>
                <div class="col-md-3">    
                    <?php echo e(Form::text('order_number',request()->order_number,['class' => 'form-control', 'placeholder' => __('admincrud.Order Number')] )); ?> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?php echo e(Form::text("from_date",request()->from_date, [ 'class' => 'form-control date_picker','id'=>'','placeholder' => __('admincrud.From Date'), "autocomplete" => "off"])); ?> 
                </div>
                <div class="col-md-3">
                    <?php echo e(Form::text("to_date",request()->to_date, [ 'class' => 'form-control date_picker','id'=>'','placeholder' => __('admincrud.To Date') ,"autocomplete" => "off"])); ?> 
                </div>
                <div class="col-md-3">
                    <?php echo e(Form::select('order_status', $modelOrder->approvedStatus(), request()->order_status ,['class' => 'selectpicker', 'id' => 'order_id','placeholder' => __('admincrud.Order Status')])); ?>     
                </div>
                <div class="col-md-3">
                    <div class="top-action">
                        <button value="submit" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincommon.Filter'); ?></button>
                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

         <div class="table-responsive">
            <table class="table table-bordered table-striped" id="reportTable">
                <thead>
                    <tr>
                        <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Order Number'); ?></th>
                        <?php if(APP_GUARD == GUARD_ADMIN): ?>
                        <th><?php echo app('translator')->getFromJson('admincrud.Vendor Name'); ?></th>
                        <?php endif; ?>
                        <?php if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR): ?>
                        <th><?php echo app('translator')->getFromJson('admincrud.Branch Name'); ?></th>
                        <?php endif; ?>
                        <th><?php echo app('translator')->getFromJson('admincrud.Order Date Time'); ?></th>
                        <th><?php echo app('translator')->getFromJson('admincrud.Order Status'); ?></th> 
                        <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
                    </tr>
                    <?php $__currentLoopData = $model; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e($value->order_number); ?></td>
                        <?php if(APP_GUARD == GUARD_ADMIN): ?>
                        <td><?php echo e($value->vendor_name); ?></td>
                        <?php endif; ?>
                        <?php if(APP_GUARD == GUARD_ADMIN || APP_GUARD == GUARD_VENDOR): ?>
                        <td><?php echo e($value->branch_name); ?></td>
                        <?php endif; ?>
                        <td><?php echo e($value->order_datetime); ?></td>
                        <td><?php echo e($modelOrder->approvedStatus($value->order_status)); ?></td> 
                        <td class="action">
                            <a href ="<?php echo e(route('report.show',['id' => $value->order_key])); ?>" ><i class="fa fa-eye"></i></a>
                            
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </thead>
            </table>
            <div class="pull-right">
                <?php echo e($model->links()); ?>

            </div>
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
$(document).ready(function(){
    $('.date_picker').datetimepicker({
        format: 'DD-MM-YYYY'
    });
     
    $('#report_export').on('click',function() {
        var action = $(this).attr('exporthref');        
        $('#report-filter').attr('action',action);
        $('#report-filter').submit();
    });
    $('#vendor').on('change',function() {
        $vendorId = $(this).val();
        $.ajax({
            url: "<?php echo e(route('get-branch-by-vendor')); ?>",
            type: 'post',
            data: {vendor_id:$vendorId},
            success: function(result){ 
                if(result.status == AJAX_SUCCESS){
                    $('#Vendor-branch_id').html('');                    
                     $('#Vendor-branch_id').append($('<option>', { value : "" }).text("All"));
                    $.each(result.data,function(key,title)
                    {  
                        $('#Vendor-branch_id').append($('<option>', { value : key }).text(title));                       
                    });                    
                    $('.selectpicker').selectpicker('refresh');
                    
                }else{
                    Error('Something went wrong','Error');
                }
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>