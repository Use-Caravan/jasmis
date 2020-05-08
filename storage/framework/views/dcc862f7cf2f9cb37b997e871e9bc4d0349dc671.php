<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
          <div class="flash-message">
            <?php if(Session::has('success')): ?>
              <p class="alert alert-success">
                <?php echo e(Session('success')); ?> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              </p>
            <?php endif; ?>            
          </div> <!-- end .flash-message -->
          <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.TimeSlot Management'); ?></h1>
        </div> <!--box-header-->

        <div class="box-body">          
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="categoryTable">
                <thead>
                    <tr>
                        <th rowspan="2"><?php echo app('translator')->getFromJson('admincrud.Days'); ?></th>                            
                        <th colspan="3" class="textCenter"> <?php echo app('translator')->getFromJson('admincrud.Delivery Hours'); ?></th>
                        <th colspan="3" class="textCenter"> <?php echo app('translator')->getFromJson('admincrud.Take Away / DineIn Hours'); ?></th>
                    </tr>
                    <tr>                            
                        <th class="text-info"><?php echo app('translator')->getFromJson('admincrud.Start Time'); ?></th>
                        <th class="text-info"><?php echo app('translator')->getFromJson('admincrud.End Time'); ?></th>
                        <th class="text-info"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                        <th class="text-info"><?php echo app('translator')->getFromJson('admincrud.Start Time'); ?></th>
                        <th class="text-info"><?php echo app('translator')->getFromJson('admincrud.End Time'); ?></th>
                        <th class="text-info"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                    </tr>
                </thead>                
                <tbody>
                    <?php $__currentLoopData = $slotType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr id="">
                        <td><?php echo e($value['day']); ?></td>
                        <td>
                            <div class="form-group time_picker icon"> 
                                <input type="text" class="form-control timeslotpicker timeslotAction <?php echo e($key); ?>startTime<?php echo e(ORDER_TYPE_DELIVERY); ?>" branch_timeslot_key="<?php echo e(($value[1] != null) ? $value[1]['branch_timeslot_key'] : ''); ?>" orderType="<?php echo e(ORDER_TYPE_DELIVERY); ?>" dayNo="<?php echo e($key); ?>" branchKey="<?php echo e($branchKey); ?>" oldValue="<?php echo e(($value[1] != null) ? $value[1]['start_time'] : ''); ?>" value="<?php echo e(($value[1] != null) ? $value[1]['start_time'] : ''); ?>"><i class="fa fa-clock-o"></i>
                            </div>
                        </td>
                        <td>
                            <div class="form-group time_picker icon">
                                <input type="text" class="form-control timeslotpicker timeslotAction  <?php echo e($key); ?>endTime<?php echo e(ORDER_TYPE_DELIVERY); ?>" branch_timeslot_key="<?php echo e(($value[1] != null) ? $value[1]['branch_timeslot_key'] : ''); ?>" orderType="<?php echo e(ORDER_TYPE_DELIVERY); ?>" dayNo="<?php echo e($key); ?>" branchKey="<?php echo e($branchKey); ?>" oldValue="<?php echo e(($value[1] != null) ? $value[1]['end_time'] : ''); ?>" value="<?php echo e(($value[1] != null) ? $value[1]['end_time'] : ''); ?>"><i class="fa fa-clock-o"></i>
                            </div>
                        </td>
                        <td class=" status">
                            <label class="switch" for="id_<?php echo e($key.ORDER_TYPE_DELIVERY); ?>">
                                <input type="checkbox" class="timeslotSwitch <?php echo e($key); ?>status<?php echo e(ORDER_TYPE_DELIVERY); ?>" branch_timeslot_key="<?php echo e(($value[1] != null) ? $value[1]['branch_timeslot_key'] : ''); ?>" orderType="<?php echo e(ORDER_TYPE_DELIVERY); ?>" dayNo="<?php echo e($key); ?>"  branchKey="<?php echo e($branchKey); ?>" id="id_<?php echo e($key.ORDER_TYPE_DELIVERY); ?>" <?php echo e(($value[1] != null && $value[1]["status"] == ITEM_ACTIVE) ? 'checked' : ''); ?>>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <div class="form-group time_picker icon">
                                <input type="text" class="form-control timeslotpicker timeslotAction <?php echo e($key); ?>startTime<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>" branch_timeslot_key="<?php echo e(($value[2] != null) ? $value[2]['branch_timeslot_key'] : ''); ?>" orderType="<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>" dayNo="<?php echo e($key); ?>"  branchKey="<?php echo e($branchKey); ?>"  oldValue="<?php echo e(($value[2] != null) ? $value[2]['start_time'] : ''); ?>" value="<?php echo e(($value[2] != null) ? $value[2]['start_time'] : ''); ?>"><i class="fa fa-clock-o"></i>
                            </div>
                        </td>
                        <td>
                            <div class="form-group time_picker icon">
                                <input type="text" class="form-control timeslotpicker timeslotAction <?php echo e($key); ?>endTime<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>" branch_timeslot_key="<?php echo e(($value[2] != null) ? $value[2]['branch_timeslot_key'] : ''); ?>" orderType="<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>" dayNo="<?php echo e($key); ?>"  branchKey="<?php echo e($branchKey); ?>" oldValue="<?php echo e(($value[2] != null) ? $value[2]['end_time'] : ''); ?>" value="<?php echo e(($value[2] != null) ? $value[2]['end_time'] : ''); ?>"><i class="fa fa-clock-o"></i>
                            </div>
                        </td>
                        <td class=" status">
                            <label class="switch" for="id_<?php echo e($key.ORDER_TYPE_PICKUP_DINEIN); ?>">
                                <input type="checkbox" class="timeslotSwitch <?php echo e($key); ?>status<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>" branch_timeslot_key="<?php echo e(($value[2] != null) ? $value[2]['branch_timeslot_key'] : ''); ?>" orderType="<?php echo e(ORDER_TYPE_PICKUP_DINEIN); ?>" dayNo="<?php echo e($key); ?>"  branchKey="<?php echo e($branchKey); ?>" id="id_<?php echo e($key.ORDER_TYPE_PICKUP_DINEIN); ?>" <?php echo e(($value[2] != null && $value[2]["status"] == ITEM_ACTIVE) ? 'checked' : ''); ?>>
                                <span class="slider"></span>
                            </label>
                        </td>
                    </tr> 
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                    
                </tbody>
            </table>
          </div>
        </div> <!--box-body-->
    </div> <!--box-->
  </section>
</div> <!--content-wrapper-->
<script>    
$(document).ready(function()
{
    $('.timeslotpicker').datetimepicker({
        format: 'LT'
    }).on('dp.change', timeSlotPicker);
    $('.timeslotSwitch').on('change', timeSlotPicker);
});
function timeSlotPicker() {
    var branchKey = $(this).attr('branchKey');    
    var orderType = $(this).attr('orderType');
    var branchTimeSlotKey = $(this).attr('branch_timeslot_key');
    var dayNo = $(this).attr('dayNo');            
    var startTime = $('.'+dayNo+'startTime'+orderType).val();
    var endTime = $('.'+dayNo+'endTime'+orderType).val();
    var status = ($('.'+dayNo+'status'+orderType).prop("checked") == true) ? <?php echo e(ITEM_ACTIVE); ?> : <?php echo e(ITEM_INACTIVE); ?> ;
    if(startTime == '' || endTime == '') {
        if($('.'+dayNo+'startTime'+orderType).attr('oldValue') != ''){
            $('.'+dayNo+'startTime'+orderType).val( $('.'+dayNo+'startTime'+orderType).attr('oldValue') );
        }        
        if($('.'+dayNo+'endTime'+orderType).attr('oldValue') != ''){
            $('.'+dayNo+'endTime'+orderType).val( $('.'+dayNo+'endTime'+orderType).attr('oldValue') );        
        }
        $('.'+dayNo+'status'+orderType). prop("checked", false);
        if(startTime == '' && endTime != '') {
            errorNotify(" <?php echo e(__('Start time and end time should not be empty')); ?> ");
        }        
        return false;
    }
    $.ajax({
        url: "<?php echo e(route('branch.timeslot')); ?>",
        type: 'post',
        data : { branchKey : branchKey, branch_timeslot_key: branchTimeSlotKey, timeslot_type : orderType, day_no : dayNo, start_time : startTime, end_time : endTime, status : status  },
        success: function(result) {
            if(result.status == AJAX_SUCCESS ) {
                if(result.data != null) {
                    $('.'+dayNo+'startTime'+orderType).attr('branch_timeslot_key',result.data.branch_timeslot_key);
                    $('.'+dayNo+'endTime'+orderType).attr('branch_timeslot_key',result.data.branch_timeslot_key);
                    $('.'+dayNo+'status'+orderType).attr('branch_timeslot_key',result.data.branch_timeslot_key);
                }
                $('.'+dayNo+'startTime'+orderType).attr('oldValue', $('.'+dayNo+'startTime'+orderType).val() );
                $('.'+dayNo+'endTime'+orderType).attr('oldValue', $('.'+dayNo+'endTime'+orderType).val() );
                successNotify(result.msg);
            } else {
                errorNotify(result.msg);
                $('.'+dayNo+'startTime'+orderType).val( $('.'+dayNo+'startTime'+orderType).attr('oldValue') );
                $('.'+dayNo+'endTime'+orderType).val( $('.'+dayNo+'endTime'+orderType).attr('oldValue') );
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if(jqXHR.status == <?php echo e(AJAX_VALIDATION_ERROR_CODE); ?>){
            var errors = jqXHR.responseJSON.errors;
            var message = '';
                $.each(errors, function (key, val) {                            
                    $.each(val, function (ikey, ival) {
                        message += ival+"<br/>";
                    });
                });
                if($('.'+dayNo+'startTime'+orderType).attr('oldValue') != ''){
                    $('.'+dayNo+'startTime'+orderType).val( $('.'+dayNo+'startTime'+orderType).attr('oldValue') );
                }        
                if($('.'+dayNo+'endTime'+orderType).attr('oldValue') != ''){
                    $('.'+dayNo+'endTime'+orderType).val( $('.'+dayNo+'endTime'+orderType).attr('oldValue') );        
                }                
                errorNotify(message);
            }
        }
    }); 
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>