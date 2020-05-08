<div class="row"> 
    <?php $__currentLoopData = $driverslist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th><p>Name:</p></th>
                    <th><p>Mobile:</p></th>
                    <th><p>Status:</p></th>
                    <th><p>Action:</p></th>
                </tr>
                <tr>                    
                    <td>
                        <b><p><?php echo e($value['name']); ?></p></b>
                    </td>                 
                    <td>
                        <b><p><?php echo e($value['phone_number']); ?></p></b>
                    </td>                                     
                    <td>
                        <b><p><?php echo e($deliveryboy->onlineStatus($value['status'])); ?></p></b>
                    </td>
                    <td>
                        <span data-order_key="<?php echo e($order_key); ?>" data-deliveryboy_key="<?php echo e($value['_id']); ?>" class="pull-right delivery-boy-assign btn btn-success ">Assign</span>
                    </td>
                </tr>                                    
            </tbody>
        </table>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
</div>