<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Delivery Boy Management'); ?></h1>
            <div class="top-action">
                <a href="<?php echo route('deliveryboy.create'); ?>" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincommon.Add New'); ?></a>
                <a href="<?php echo route('deliveryboy.tracking'); ?>" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincrud.Track Delivery boy'); ?></a>
            </div>

        </div> <!--box-header-->

        <div class="box-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped" id="deliveryboyTable">
              <thead>
              <tr>
                  <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>                  
                  <th><?php echo app('translator')->getFromJson('admincommon.User Name'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincommon.Email'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincommon.Mobile Number'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.City Name'); ?></th>                                              
                  <th><?php echo app('translator')->getFromJson('admincrud.Online Status'); ?></th>
                  <th class="status"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                  <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
              </tr>
                <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e($value['name']); ?></td>
                        <td><?php echo e($value['email']); ?></td>
                        <td><?php echo e($value['phone_number']); ?></td>                        
                        <td><?php echo e($value['city']); ?></td>                                           
                        <td>
                            <?php echo e($deliveryboy->onlineStatus($value['status'])); ?>

                        </td>
                        <td>
                            <label class="switch" for="id_<?php echo e($value['_id']); ?>">
                                <input type="checkbox" itemkey="<?php echo e($value['_id']); ?>" class="deliveryboy_status" id="id_<?php echo e($value['_id']); ?>" <?php if( $value['status'] === ITEM_ACTIVE ): ?> checked="true" <?php endif; ?> >
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td class="action">                            
                            <a href="<?php echo e(route('deliveryboy.edit',['deliveryboy' => $value['_id'] ])); ?>" title="Edit">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="javascript:" class="trash" title="Are you sure?" data-toggle="popover" data-placement="left" data-target="#delete_confirm" data-original-title="Are you sure?">
                                <i class="fa fa-trash"></i>
                            </a>
                            <form action="<?php echo e(route('deliveryboy.destroy',$value['_id'])); ?>" id="deleteForm<?php echo e($value['_id']); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </thead>
            </table>
          </div>
        </div> <!--box-body-->
    </div> <!--box-->
  </section>
</div> <!--content-wrapper-->
<script>
$(document).ready(function()
{    
    $('body').on('change','.deliveryboy_status',function (e, data) {
        var itemkey = $(this).attr('itemkey');        
        var action = "<?php echo e(route('deliveryboy.status')); ?>"
        var status = ($(this).prop('checked') == true) ?  1  : 4 ;        
        $.ajax({
            url: action,
            type: 'post',
            data : { itemkey : itemkey,status : status },
            success: function(result) {
                if(result.status == AJAX_SUCCESS ){
                    successNotify(result.msg);
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