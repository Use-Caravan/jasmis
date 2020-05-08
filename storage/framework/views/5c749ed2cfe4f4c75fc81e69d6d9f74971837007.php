<?php $__env->startSection('content'); ?>

 <!-- listing restaurant -->

    <section class="listing-restaurant">
        <div class="container">

            <!-- breadcums -->
            <div class="breadcums wow fadeInUp">
                <ul class="reset">
                    <li><a href="<?php echo e(route('frontend.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                    <li><span><?php echo e(__('Restaurants')); ?></span></li>
                </ul>
            </div>
            <!-- breadcums -->

            <?php if($bannerImage != null): ?> 
            <!-- advertisement Banner -->            
            <div class="advertisement-slider wow fadeInUp owl-carousel">
               <?php $__currentLoopData = $bannerImage; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="slide">
                    <?php if(preg_match('/http/',$value['redirect_url']) || preg_match('/https/',$value['redirect_url'])): ?>
                     <a href="<?php echo e($value['redirect_url']); ?>" target="_blank" />
                    <?php else: ?>
                     <a href="http://<?php echo e($value['redirect_url']); ?>" target="_blank" /> 
                    <?php endif; ?>       
                        <?php echo Html::image( FileHelper::loadImage($value['banner_file']),$value['banner_name']);; ?>   
                        </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
            </div>            
            <!-- advertisement Banner -->
            <?php endif; ?>

            <!-- search and filter -->
            <?php echo e(Form::open(['route' => 'frontend.branch.index','id' => 'branch-search-form', 'class' => 'form-horizontal', 'method' => 'GET'])); ?>

            <!-- search filter overlay -->
            <div class="filter-overlay"></div>
            <div class="search-and-filter wow fadeInUp">
                <div class="d-table w100">                    
                    <?php echo e(Form::hidden('latitude',request()->latitude)); ?>

                    <?php echo e(Form::hidden('longitude',request()->longitude)); ?>

                    <?php echo e(Form::hidden('location',request()->location)); ?>

                    <?php echo e(Form::hidden('order_type',request()->order_type)); ?>

                    <?php echo e(Form::hidden('cuisine',request()->cuisine)); ?>

                    <div class="d-table-cell">
                        <div class="search-form" id = "listing">
                            <i class="fa fa-search flyo"></i>
                            <input type="text" name="branch_name" value="<?php echo e(request()->branch_name); ?>" class="form-control listing" id="branch_name" placeholder="<?php echo e(__('Search')."..."); ?>">
                            <button type="button" id="filter-toggle"> <i class="fa fa-sliders" aria-hidden="true"></i> <?php echo e(__('Filter')); ?> <i class="fa fa-angle-down"></i></button>
                        </div>
                    </div>
                
                    <div class="d-table-cell text-right">
                        <div class="p f18"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo e(request()->location); ?> <a href="<?php echo e(route('frontend.index')); ?>"><?php echo e(__('Change')); ?></a></div>
                    </div>
                </div>

                <!-- search sidebar -->
                                
                <div class="dd-menu">

                    <div class="filter-drop cuisine-filter">

                        <div class="full_row ">
                            <h4><?php echo e(__('Sort by')); ?></h4>

                            <input type="checkbox" name="orderby_popularity" class="checkbox order_by_check" value= "desc" id="f1" <?php echo e(request()->orderby_popularity !== null ? 'checked' : ''); ?>>
                            <label class="checkbox" for="f1"><?php echo e(__('Popularity')); ?></label>

                            <input type="checkbox" name="orderby_rating" class="checkbox order_by_check" value = "desc" id="f2" <?php echo e(request()->orderby_rating !== null ? 'checked' : ''); ?>>
                            <label class="checkbox" for="f2"><?php echo e(__('Rating')); ?></label>

                            <input type="checkbox" name="orderby_min_order_value" class="checkbox order_by_check" value = "asc" id="f3" <?php echo e(request()->orderby_min_order_value !== null ? 'checked' : ''); ?>>
                            <label class="checkbox" for="f3"><?php echo e(__('Min.order')); ?></label>

                            <input type="checkbox" name="orderby_delivery_time" class="checkbox order_by_check" value = "asc" id="f4" <?php echo e(request()->orderby_delivery_time !== null ? 'checked' : ''); ?>>
                            <label class="checkbox" for="f4"><?php echo e(__('Del.time')); ?></label>

                        </div>

                        <div class="full_row">
                            <h4><?php echo e(('Cuisine Type')); ?></h4>
                            <?php $cuisines = explode(',', (request()->cuisine === null) ? '' : request()->cuisine ) ?>
                            <?php $__currentLoopData = $cuisineList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <input type="checkbox" value ="<?php echo e($value['cuisine_id']); ?>" data="<?php echo e($value['cuisine_name']); ?>" class="checkbox cuisines" id="c<?php echo e($value['cuisine_id']); ?>"  <?php echo e(in_array($value['cuisine_id'],$cuisines) ? 'checked' : ''); ?>>
                            <label class="checkbox" for="c<?php echo e($value['cuisine_id']); ?>"><?php echo e($value['cuisine_name']); ?></label> 
                            
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            

                        </div>

                        <div class="full_row last text-right">
                           
                           <?php echo Html::decode( Form::button('<span class="shape">Done</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ); ?>

                        </div>

                    </div>
                    
                </div>

            </div>
            <?php echo e(form::close()); ?>

            <!-- search and filter -->

            <!-- tags -->

            <div class="row mb-4">
                <div class="col-md-12">
                    <span id="select-cuisines">
                        <?php $__currentLoopData = $checkedCuisines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                        
                        <button class="tags wow zoomIn remove-cuisine" style="visibility: visible; animation-name: zoomIn;" cuisine_id=<?php echo e($value->cuisine_id); ?>><?php echo e($value->cuisine_name); ?><span>×</span> </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         
                    </span>              
                    <a href="javascript:" class="cuisine-clear clearall wow zoomIn" style="visibility : visible;animation-name: zoomIn;<?php echo e((request()->cuisine === null) ? 'display:none;' : ''); ?>">Clear All</a>                    
                </div>                                        
            </div>
            
            <!-- tags -->
            
            <div class="listing-restaurants" id="branch_lising_search">
                    
                <?php echo $__env->make('frontend.branch.search-branch', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            </div>
            <?php if(false): ?>
            /* For future update */
            <div class="corporate_rest">
                <h2 class="heading-1">Restaurants</h2>
               

                <div class="row">
                <ul class="rest_ul">
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r1.png) #EA362F no-repeat center center"></a>
                        <span>Jasmi's</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/list-2.png) #FFFFFF no-repeat center center"></a>
                        <span>Dajajio</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r3.png) #522A14 no-repeat center center"></a>
                        <span>Marash</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r4.png) #8DC242 no-repeat center center"></a>
                        <span>Jasmi's Coffee</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r5.png) #295133 no-repeat center center"></a>
                        <span>Le Chocolat</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r6.png) #C3342F no-repeat center center"></a>
                        <span>Wood Pizza Pasta</span>
                    </li>
                    <li>
                        <a class="" href="" style="background: url(../resources/assets/frontend/img/r7.png) #303838 no-repeat center center"></a>
                        <span>Tony Roma's</span>
                    </li>
                </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- listing restaurant -->

    <!-- restaurant popup -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {     
    $('#branch-search-form input[name="branch_name"]').on('keyup',function(){
        var action = $('#branch-search-form').attr('action');
        var branch_name = $('#branch_name').val();
        if(branch_name.length <= 3 && branch_name.length > 0) {
            return ;
        }
        var data = $('#branch-search-form').serializeArray();
        $.ajax({
            type : 'get',
            url : action,            
            data: data,
            beforeSend:function() {
            },              
            success:function(result){
                $('#branch_lising_search').html(result.list);                
                changeUrl();
            }
        });
    }); 
    
    $('.order_by_check').on('change', function() {
        $('.order_by_check').not(this).prop('checked', false);  
    });

    $('#branch-search-form').on('submit',function(e){
        e.preventDefault();
        var action = $(this).attr('action');
        
        var cuisine = '';        
        $('#select-cuisines button').remove();
        $('#branch-search-form .cuisines').each(function() {
           if($(this).prop('checked') == true) {
                var cuisineName = $(this).attr('data');
                var cuisineId = $(this).val();
                cuisine += ","+$(this).val();       
                $('#select-cuisines').append(`<button class="tags wow zoomIn remove-cuisine" style="visibility: visible; animation-name: zoomIn;" cuisine_id=`+cuisineId+`>`+cuisineName+`<span>×</span> </button>`);
            }                        
        });        
        cuisine = cuisine.substring(1);
        $('#branch-search-form input[name="cuisine"]').val(cuisine);
        if($('#select-cuisines button').length > 0) {
            $('.cuisine-clear').show();
        }
        var data = $('#branch-search-form').serializeArray();       
        $.ajax({
            type : 'get',
            url : action,            
            data: data,
            success:function(result){
                $('#branch_lising_search').html(result.list); 
                $(".search-and-filter").toggleClass("open");
                $(".filter-overlay").toggleClass("open");
                changeUrl();
            }
        });        
    });
    $('.cuisine-clear').on('click',function() {
        $('#select-cuisines button').remove();
        $('.cuisines').prop('checked',false);
        $('.cuisine-clear').hide();            
    });
    
    $('body').on('click','.remove-cuisine',function() {
        $(this).remove();
        //var cuisineLength = $(this).val();
        var cuisineId = $(this).attr('cuisine_id');
        $('#c'+cuisineId).prop('checked',false);
    });
    $('.wishlist_heart').click(function()
    {
        var ths = $(this);
        var isWishlist = ths.data('wishlist');
        var type = (isWishlist == 1) ?  'PUT' : 'POST';        
        $.ajax({
            url: "<?php echo e(route('frontend.wishlist')); ?>",
            type: type,
            data : { branch_key: $(this).val()},
            success: function(result) {
                if(result.status == HTTP_SUCCESS) {
                    successNotify(result.message);                                        
                    if(isWishlist == 1){
                        ths.removeClass('added');
                        ths.data('wishlist',0);
                    } else {                        
                        ths.addClass('added');
                        ths.data('wishlist',1);
                    }
                }else{
                    var message = result.message;
                    errorNotify(result.message.replace(",","<br/>"));
                }
            }
        });
    });        
});
function changeUrl() {
    var action = $('#branch-search-form').attr('action');
    var urlPush = action+"?";
    var data = $('#branch-search-form').serializeArray();
    $(data).each(function(index, value ) {
        urlPush +=value['name']+"="+value['value']+"&";
    });
    window.history.pushState("object or string", "Title", urlPush);
}

</script>    
<?php $__env->stopSection(); ?>
  
<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>