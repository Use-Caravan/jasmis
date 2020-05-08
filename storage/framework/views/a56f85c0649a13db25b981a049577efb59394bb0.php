<?php $__env->startSection('content'); ?>
<section class="padd-20">
    <div class="container">
        <!-- breadcums -->
        <div class="breadcums mb-0 wow fadeInUp">
            <ul class="reset">
                <li><a href="<?php echo e(route('frontend.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                <li><span><?php echo e(__('My Orders')); ?></span></li>
            </ul>
        </div>
        <!-- breadcums -->
    </div>
</section>
<section class="myaccount-page">
    <?php if($message = Session::get('error')): ?>
        <div class="container"> 
            <div class="flash-message">
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>	    
                    <?php echo e($message); ?>

                </div>
            </div> 
        </div>
    <?php endif; ?>
    <div class="container">
        <?php echo $__env->make('frontend.layouts.partials._profile-section', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <div class="border-boxed">
                <div class="full_row">
                    <?php echo $__env->make('frontend.layouts.partials._profile_sidemenu', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <div class="account-content">
                        

    <h2 class="account-title wow fadeInUp"><?php echo e(__('My Orders')); ?></h2>

                        <ul class="reset my_order_list wow fadeInUp">
                            <!-- my order li -->
                            <?php $__currentLoopData = $orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li> 
                                <!-- box -->
                                <div class="box full_row wow fadeInUp">
                                    <!-- box top-->
                                    <div class="box-top full_row">
                                        <h4><?php echo e($value->branch_name); ?></h4>
                                        <?php if($value->claim_corporate_offer_booking === 1): ?>
                                            <p class="price"><?php echo e(Common::currency($value->corder_total - $value->csub_total)); ?></p>
                                        <?php else: ?>
                                            <p class="price"><?php echo e($value->order_total); ?></p>
                                        <?php endif; ?>
                                        <div class="status"><?php echo e(__('Status')); ?> : <span class="completed"><?php echo e($value->status); ?></span></div>
                                    </div>
                                    <!-- box top-->
                                    <!-- box-bottom -->
                                    <div class="box-bottom full_row">
                                        <p class="id"><?php echo e(__('Order Id')); ?> : <?php echo e($value->order_number); ?></p>
                                        
                                        <p><?php echo e($value->order_datetime); ?></p>
                                        <div class="or-btns">
                                            <a href="javascript:" data-action="<?php echo e(route('frontend.show-myorder',[$value->order_key])); ?>" class = "vieworder" data-toggle="modal" data-target="#view_order"><i data-toggle="tooltip" title="<?php echo e(__('View Order')); ?>" class="icon-eye"></i></a>
                                            <a href="javascript:" data-action="<?php echo e(route('frontend.reorder',['order_key' => $value->order_key])); ?>" data-orderkey="<?php echo e($value->order_key); ?>" class="reorder"><i data-toggle="tooltip"  title="<?php echo e(__('Reorder')); ?>" class="icon-reload"></i></a>
                                            <?php if($value->order_type === ORDER_TYPE_DELIVERY): ?>
                                                <?php if($value->order_status_value === ORDER_ONTHEWAY): ?>
                                                    <a href="#track_order" class = "trackorder" data-order="<?php echo e($value->order_key); ?>" user-latitude="<?php echo e($value->user_latitude); ?>" user-longitude="<?php echo e($value->user_longitude); ?>" data-toggle="modal" data-target="#track_order"><i data-toggle="tooltip" title="<?php echo e(__('Track order')); ?>" class="icon-map"></i></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- box-bottom -->
                                </div>
                                <!-- box -->
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <!-- my order li -->
                            <!-- my order li -->
                           
                            <!-- my order li -->
                        </ul>
    <!-- View Order modal -->

    <div class="modal view_order_ui fade" id="view_order">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h4 class="modal-title"><?php echo e(__('Order Info')); ?></h4>
                </div>
                <div class="modal-body" id="order_view">


                </div>
            </div>
        </div>
    </div> 

    <!-- View Order modal -->

    <!-- Confirm Re Order model-->
    <div class="modal fade reorder_modal" id="reorder_conform" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">   
            <h3> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> <?php echo e(__('Alert!')); ?></h3>
            </div>
            <div class="modal-body">
            <p><?php echo e(__('Do You Want to reorder?')); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="" class="btn btn-primary" id="confirm_reorder" >OK</a> 
            </div>
            </div>
        </div>
    </div>
    <!-- Confirm Re Order model-->

   <!-- Track Order modal -->

    <div class="modal track_order_ui fade" id="track_order">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h4 class="modal-title"><?php echo e(__('Track Order')); ?></h4>
                </div>
                <div class="modal-body">

                    <div class="map_full" id="track-map"></div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</section>

    <!-- Track Order modal -->



<script src="<?php echo e(url('resources/assets/socket.io.js')); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.min.js"></script>
<script>
$('.reorder').on('click',function() {
    var url = $(this).attr('data-action');
    $('#reorder_conform').modal('show');
    $('#confirm_reorder').attr('href',url);
    
})    
$('.trackorder').on('click', function() {
     var orderkey = $(this).attr('data-order');
     var userLatitude = $(this).attr('user-latitude');
     var userLongitude = $(this).attr('user-longitude');
     initMap(orderkey,userLatitude,userLongitude)
       
    });
function initMap(orderkey,userLatitude,userLongitude) {
     
    var latlng = new google.maps.LatLng(parseFloat(<?php echo e(config('webconfig.app_latitude')); ?>),parseFloat(<?php echo e(config('webconfig.app_longitude')); ?>));

    var markers = [];

    var image = {
            url: '<?php echo e(url("resources/assets/general/delivery-pin.png")); ?>',
            // This marker is 20 pixels wide by 32 pixels high.
            size: new google.maps.Size(40, 50),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
        };

        var homeIcon = {
            url: '<?php echo e(url("resources/assets/general/pin-home.png")); ?>',
            // This marker is 20 pixels wide by 32 pixels high.
            size: new google.maps.Size(40, 50),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
        };

    var map = new google.maps.Map(document.getElementById('track-map'), {
        center: latlng,
        zoom: 02,
        mapTypeId: 'roadmap'
    });

// Create SocketIO instance, connect
var URL = "<?php echo e(config('webconfig.tracking_url')); ?>";
console.log(URL);
const socket = io(URL);    
var currentMarkers = [];

var speed = 60;
var delay = 100;
var prevPosition = [];
var log=[];

 log.difference = true;
        log.data = true;
        window.fitBounds = true;

socket.on('connect',function() {
    window.driver_marker = [];      
    window.drivers = [];  
    window.timeOuts = []; 
    console.log(window.drivers,'test');
    /* console.log("Socket has connected"); */
});

var homeIcon = homeIcon.url;
var myLatLng =new google.maps.LatLng(parseFloat(userLatitude), parseFloat(userLongitude));
 
        var map = new google.maps.Map(document.getElementById('track-map'), {
          zoom: 4,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'user',
          icon:homeIcon
        });




socket.emit('driver_location', { order_key : orderkey, company_id: '<?php echo e(config("webconfig.company_id")); ?>'});
    // Add a connect listener
    
    socket.on('driver_location',function(data) {
        
        /* console.log(data) */
        data = [data];
        var difference = [];

        if(log.data){
            console.log(window.drivers);
            // console.log(data)
          }
        
          difference = _.differenceBy(window.drivers,data, 'driver_id');
          
          if(log.difference){
            // console.log(difference);
          }
          
          if(difference.length > 0) {
            if(log.difference){
              console.log(difference.length);
            }
            difference.map(function(marker) {
              if(log.difference){
                console.log(marker);
              }
              window.driver_marker[marker.driver_id].setMap(null);
              delete window.driver_marker[marker.driver_id];
              _.remove(window.drivers, function(n) {
                  return n.driver_id === marker.driver_id;
              });
            });
          }
        
          var driverLatLng = [];
          var lengthData = 0;
          if( data && data.length > 0) {
            var bounds = new google.maps.LatLngBounds();
            data.map(function(driver) {
              // if(driver.driver_id == 'BdFsI8PoLbXM4vdo'){
              //   console.log(driver);//ramesh driver
              // }
              driverLatLng[lengthData] = [driver.latitude, driver.longitude];
              lengthData++;
              position = new google.maps.LatLng(driver.latitude, driver.longitude);
              if(window.driver_marker[driver.driver_id]) {
                bounds.extend(position);
                  
                  var distance = calcDistance(
                    prevPosition[driver.driver_id][0], 
                    prevPosition[driver.driver_id][1],
                    driver.latitude,
                    driver.longitude
                  );
                  // console.log(distance);
                  if ( prevPosition[driver.driver_id][0] != driver.latitude && prevPosition[driver.driver_id][1] != driver.longitude ) {
                    
                    animateMarker(
                        driver.driver_id,
                        window.driver_marker[driver.driver_id],
                        [ prevPosition[driver.driver_id], [driver.latitude, driver.longitude]],
                        speed
                    );
                    prevPosition[driver.driver_id] = [driver.latitude, driver.longitude];
                  }
              
              } else {
                prevPosition[driver.driver_id] = [driver.latitude, driver.longitude];
                if(window.fitBounds){
                  bounds.extend(position);
                  map.fitBounds(bounds);
                } 
                window.drivers.push(driver);
                var icon = image.url;// (parseInt(driver.count) > 0 ) ? iconBusy : iconNormal;
                  markerOption = {
                    position: position,
                    map: map,
                    title: driver.driver_name,
                    icon: icon
                  }; 
                window.driver_marker[driver.driver_id] = new google.maps.Marker(markerOption);

                let content = '<div id="content">'+
                            '<div id="siteNotice">'+
                            '</div>'+
                            '<h2 id="firstHeading" class="firstHeading">Driver Info</h2>'+
                            '<div id="bodyContent">'+
                            '<p><b>Driver Name : </b> <span id="driver_name">'+driver.driver_name+'</span> </p>'+
                            '<p><b>Driver Email : </b> <span id="driver_email">'+driver.driver_email+'</span> </p>'+
                            '<p><b>Driver Phone Number : </b> <span id="driver_phone">'+driver.driver_phone+'</span> </p>'+
                            '</div>'+
                            '</div>';
                let driverMarker = window.driver_marker[driver.driver_id];
                var infowindow = new google.maps.InfoWindow()      
                
                google.maps.event.addListener(driverMarker,'mouseover', (function(driverMarker,content,infowindow){
                    return function() {
                        infowindow.setContent(content);
                        infowindow.open(map,driverMarker);
                    };
                })(driverMarker,content,infowindow));  
                google.maps.event.addListener(driverMarker,'mouseout', (function(driverMarker,content,infowindow){
                    return function() {                    
                        infowindow.close(map,driverMarker);
                    };
                })(driverMarker,content,infowindow));
              }
            });
            window.fitBounds=false;
          }
    });  
         
}  
        
function animateMarker(driver_id, marker, coords, km_h) 
{   
    var target = 0;
    var km_h = km_h || 70;

    function goToPoint()
    {
        // if(typeof(window.timeOuts[driver_id]) == 'undefined'){
        //   window.timeOuts[driver_id] =[];
        // }
        // if(typeof(window.timeOuts[driver_id].dest) != 'undefined'){
        //   clearTimeout(window.timeOuts[driver_id].timer);
        //   // console.log(window.timeOuts[driver_id].dest);
        //   marker.setPosition(new google.maps.LatLng(window.timeOuts[driver_id]['dest'][0],window.timeOuts[driver_id]['dest'][1]));
        // }
        // window.timeOuts[driver_id].dest1 = new google.maps.LatLng([coords[1][0], coords[1][1]]);;
        // window.timeOuts[driver_id].dest = [coords[1][0], coords[1][1]];

        var lat = marker.position.lat();
        var lng = marker.position.lng();
        var step = (km_h * 1000 * delay) / 3600000; // in meters
        var dest = new google.maps.LatLng(coords[target][0], coords[target][1]);
        var distance = google.maps.geometry.spherical.computeDistanceBetween(dest, marker.position); // in meters
        var numStep = distance / step;
        var i = 0;
        var deltaLat = (coords[target][0] - lat) / numStep;
        var deltaLng = (coords[target][1] - lng) / numStep;
        function moveMarker()
        {
        lat += deltaLat;
        lng += deltaLng;
        i += step;

        if (i < distance) {
            var lastPosn = marker.getPosition();
            marker.setPosition(new google.maps.LatLng(lat, lng));
            var heading = google.maps.geometry.spherical.computeHeading(lastPosn, new google.maps.LatLng(lat, lng));
            marker.icon.rotation = heading;
            marker.setIcon(marker.icon);
            // setTimeout(moveMarker, delay);
            if(typeof(window.timeOuts[driver_id]) == 'undefined'){
            window.timeOuts[driver_id] =[];
            }
            if(typeof(window.timeOuts[driver_id].timer) != 'undefined'){
            clearTimeout(window.timeOuts[driver_id].timer);
            }
            window.timeOuts[driver_id]['timer']= setTimeout(moveMarker, delay);
        }
        }
        moveMarker();
    }
    goToPoint();
}

function calcDistance(lat1, lon1, lat2, lon2)  {
    var R = 6371; // km
    var dLat = toRad(lat2-lat1);
    var dLon = toRad(lon2-lon1);
    var lat1 = toRad(lat1);
    var lat2 = toRad(lat2);

    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    var d = R * c;
    return d;
}
function toRad(Value) 
{
    return Value * Math.PI / 180;
}      
</script>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('webconfig.map_key')); ?>&callback=initMap&libraries=drawing,places&sensor=false"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>