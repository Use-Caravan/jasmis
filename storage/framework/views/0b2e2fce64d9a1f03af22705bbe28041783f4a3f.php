<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
  <section class="content">
    <div class="box">
        <div class="box-header with-border">
        <?php echo $__env->make('admin.layouts.partials._flashmsg', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
          <h1 class="box-title"><?php echo app('translator')->getFromJson('admincrud.Category Management'); ?></h1>
          <div class="top-action">
            <a href="<?php echo route('category.create'); ?>" title="Add New" class="btn mb15"><i class="fa fa-plus-circle"></i><?php echo app('translator')->getFromJson('admincommon.Add New'); ?></a>
          </div>
        </div> <!--box-header-->

        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable">
              <thead>
              <tr>
                  <th width="20"><?php echo app('translator')->getFromJson('admincommon.S.No'); ?></th>
                   <th><?php echo app('translator')->getFromJson('admincrud.Category Type'); ?></th>
                  <th><?php echo app('translator')->getFromJson('admincrud.Category Name'); ?></th>
                  <th width="20"><?php echo app('translator')->getFromJson('admincommon.Sort No'); ?></th>
                  <th class="status"><?php echo app('translator')->getFromJson('admincommon.Status'); ?></th>
                  <th class="action"><?php echo app('translator')->getFromJson('admincommon.Action'); ?></th>
              </tr>
              <tr>
                    <th></th>
                    <th>
                        <?php echo e(Form::select('status', [1 => __('admincrud.Main Category'),2 =>  __('admincrud.Sub Category')], '' ,['class' => 'selectpicker filterSelect', 'placeholder' => __('admincommon.All'), "data-name" => "1"] )); ?>

                    </th>
                    <th>
                        <?php echo e(Form::text("category_name", '', ['class' => 'form-control filterText', "placeholder" =>__('admincrud.Category Name'), "data-name" => "2"])); ?>                         
                    </th>
                    <th>
                        <?php echo e(Form::text("sort_no", '', ['class' => 'form-control filterText', "placeholder" =>__('admincommon.Sort No'), "data-name" => "3"])); ?>

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
        'ajax' : {
            url: "<?php echo e(route('category.index')); ?>",
            type: 'GET',
            beforeSend: function (request) {
                request.setRequestHeader("Content-Type", "application/json; charset=utf-8");
            }
        },
        'columns': [
            { 'data' : 'DT_RowIndex', 'name' : 'category_id', 'searchable' : false},
            { 'data' : 'is_main_category'},
            { 'data' : 'category_name', 'name' : 'CL.category_name'},
            { 'data' : 'sort_no'},
            { 'data' : 'status', 'sClass' : 'status', 'orderable' : false},
            { 'data' : 'action', 'sClass' : 'action', 'orderable' : false}
        ],
    });    
});

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>