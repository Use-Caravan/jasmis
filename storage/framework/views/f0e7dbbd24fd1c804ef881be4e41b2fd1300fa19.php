<?php switch($type):
    case (TYPE_STATUS_COLUMN): ?>
           <?php if(method_exists($model,'uniqueKey')): ?>
            <label class="switch" for="id_<?php echo e($model->{$model::uniqueKey()}); ?>">
                <input type="checkbox" itemkey="<?php echo e($model->{$model::uniqueKey()}); ?>" action="<?php echo e(route($route)); ?>" class="SwitchStatus" id="id_<?php echo e($model->{$model::uniqueKey()}); ?>" <?php if( $model->status === ITEM_ACTIVE ): ?> checked="true" <?php endif; ?> >
                <span class="slider"></span>
            </label>                
            <?php else: ?>
            <label class="switch" for="id_<?php echo e($model->{$model::primaryKey()}); ?>">
                <input type="checkbox" itemkey="<?php echo e($model->{$model::primaryKey()}); ?>" action="<?php echo e(route($route)); ?>" class="SwitchStatus" id="id_<?php echo e($model->{$model::primaryKey()}); ?>" <?php if( $model->status === ITEM_ACTIVE ): ?> checked="true" <?php endif; ?> >
                <span class="slider"></span>
            </label>
            <?php endif; ?>
            
        <?php break; ?>
        <?php case (TYPE_POPULARSTATUS_COLUMN): ?>
           <?php if(method_exists($model,'uniqueKey')): ?>
            <label class="switch" for="popid_<?php echo e($model->{$model::uniqueKey()}); ?>">
                <input type="checkbox" itemkey="<?php echo e($model->{$model::uniqueKey()}); ?>" action="<?php echo e(route($route)); ?>" class="SwitchPopular" id="popid_<?php echo e($model->{$model::uniqueKey()}); ?>" <?php if( $model->popular_status === ITEM_ACTIVE ): ?> checked="true" <?php endif; ?> >
                <span class="slider"></span>
            </label>                
            <?php else: ?>
            <label class="switch" for="popid_<?php echo e($model->{$model::primaryKey()}); ?>">
                <input type="checkbox" itemkey="<?php echo e($model->{$model::primaryKey()}); ?>" action="<?php echo e(route($route)); ?>" class="SwitchPopular" id="popid_<?php echo e($model->{$model::primaryKey()}); ?>" <?php if( $model->popular_status === ITEM_ACTIVE ): ?> checked="true" <?php endif; ?> >
                <span class="slider"></span>
            </label>
            <?php endif; ?>
            
        <?php break; ?>

        <?php case (TYPE_QUICKBUYSTATUS_COLUMN): ?>
           <?php if(method_exists($model,'uniqueKey')): ?>
            <label class="switch" for="quickid_<?php echo e($model->{$model::uniqueKey()}); ?>">
                <input type="checkbox" itemkey="<?php echo e($model->{$model::uniqueKey()}); ?>" action="<?php echo e(route($route)); ?>" class="SwitchQuickbuy" id="quickid_<?php echo e($model->{$model::uniqueKey()}); ?>" <?php if( $model->quickbuy_status === ITEM_ACTIVE ): ?> checked="true" <?php endif; ?> >
                <span class="slider"></span>
            </label>                
            <?php else: ?>
            <label class="switch" for="quickid_<?php echo e($model->{$model::primaryKey()}); ?>">
                <input type="checkbox" itemkey="<?php echo e($model->{$model::primaryKey()}); ?>" action="<?php echo e(route($route)); ?>" class="SwitchQuickbuy" id="quickid_<?php echo e($model->{$model::primaryKey()}); ?>" <?php if( $model->quickbuy_status === ITEM_ACTIVE ): ?> checked="true" <?php endif; ?> >
                <span class="slider"></span>
            </label>
            <?php endif; ?>
            
        <?php break; ?>
    <?php case (TYPE_ACTION_COLUMN): ?>
            <?php if($isdelete == true): ?>
                <a href="javascript:"
                <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e($key); ?>="<?php echo e($value); ?>"
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                >
                    <?php echo $title; ?>

                </a>

                <form action="<?php echo e(route($route,$params)); ?>" id="deleteForm<?php echo e($model->cuisine_key); ?>" method="post">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                </form>

            <?php else: ?>

                <a href="<?php echo e(route($route,$params)); ?>" 
                <?php $__currentLoopData = $attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e($key); ?>="<?php echo e($value); ?>"
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                >
                    <?php echo $title; ?>

                </a>

            <?php endif; ?>    
        <?php break; ?>
    <?php case (APPROVED_STATUS_COLUMN): ?>
        <?php echo e(Form::select('approved_status', $approvedStatus, $model->approved_status ,['class' => 'selectpicker approvedStatuss',"action" => route($route), "id" => $model->{$model::uniqueKey()} ] )); ?>

    <?php break; ?>
<?php endswitch; ?>