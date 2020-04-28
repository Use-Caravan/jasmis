@extends('frontend.layouts.layout')
@section('content')
<section class="padd-20">
    <div class="container">
        <!-- breadcums -->
        <div class="breadcums mb-0 wow fadeInUp">
            <ul class="reset">
                <li><a href="{{route('frontend.index')}}">{{__('Home')}}</a></li>
                <li><span>{{__('My Orders')}}</span></li>
            </ul>
        </div>
        <!-- breadcums -->
    </div>
</section>
<section class="myaccount-page">
    @if ($message = Session::get('error'))
        <div class="container"> 
            <div class="flash-message">
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>	    
                    {{ $message }}
                </div>
            </div> 
        </div>
    @endif
    <div class="container">
        @include('frontend.layouts.partials._profile-section')
            <div class="border-boxed">
                <div class="full_row">
                    @include('frontend.layouts.partials._profile_sidemenu')
                    <div class="account-content">
                        

    <h2 class="account-title wow fadeInUp">{{__('My Orders')}}</h2>

                        <ul class="reset my_order_list wow fadeInUp">
                            <!-- my order li -->
                            @foreach($orderDetails as $key => $value)
                            <li> 
                                <!-- box -->
                                <div class="box full_row wow fadeInUp">
                                    <!-- box top-->
                                    <div class="box-top full_row">
                                        <h4>{{$value->branch_name}}</h4>
                                        @if($value->claim_corporate_offer_booking === 1)
                                            <p class="price">{{ Common::currency($value->corder_total - $value->csub_total)  }}</p>
                                        @else
                                            <p class="price">{{$value->order_total}}</p>
                                        @endif
                                        <div class="status">{{__('Status')}} : <span class="completed">{{$value->status}}</span></div>
                                    </div>
                                    <!-- box top-->
                                    <!-- box-bottom -->
                                    <div class="box-bottom full_row">
                                        <p class="id">{{__('Order Id')}} : {{$value->order_number}}</p>
                                        {{--<p>01-04-2019 1.30 PM</p> --}}
                                        <p>{{$value->order_datetime}}</p>
                                        <div class="or-btns">
                                            <a href="javascript:" data-action="{{ route('frontend.show-myorder',[$value->order_key]) }}" class = "vieworder" data-toggle="modal" data-target="#view_order"><i data-toggle="tooltip" title="{{ __('View Order') }}" class="icon-eye"></i></a>
                                            <a href="javascript:" data-action="{{ route('frontend.reorder',['order_key' => $value->order_key]) }}" data-orderkey="{{$value->order_key}}" class="reorder"><i data-toggle="tooltip"  title="{{ __('Reorder') }}" class="icon-reload"></i></a>
                                            @if($value->order_type === ORDER_TYPE_DELIVERY)
                                                @if($value->order_status_value === ORDER_ONTHEWAY)
                                                    <a href="#track_order" class = "trackorder" data-order="{{$value->order_key}}" user-latitude="{{$value->user_latitude}}" user-longitude="{{$value->user_longitude}}" data-toggle="modal" data-target="#track_order"><i data-toggle="tooltip" title="{{ __('Track order') }}" class="icon-map"></i></a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <!-- box-bottom -->
                                </div>
                                <!-- box -->
                            </li>
                            @endforeach
                            <!-- my order li -->
                            <!-- my order li -->
                           {{-- <li>
                                <!-- box -->
                                <div class="box full_row wow fadeInUp">
                                    <!-- box top-->
                                    <div class="box-top full_row">
                                        <h4>Dajajio</h4>
                                        <p class="price">BD 12.700</p>
                                        <div class="status">Status : <span class="accepted">Accepted</span></div>
                                    </div>
                                    <!-- box top-->
                                    <!-- box-bottom -->
                                    <div class="box-bottom full_row">
                                        <p class="id">Order Id : #OTB000145</p>
                                        <p>01-04-2019 1.30 PM</p>
                                        <div class="or-btns">
                                            <a href="#view_order" data-toggle="modal" data-target="#view_order"><i data-toggle="tooltip" title="View Order" class="icon-eye"></i></a>
                                            <a href="#"><i data-toggle="tooltip" title="Reorder" class="icon-reload"></i></a>
                                            <a href="#track_order" data-toggle="modal" data-target="#track_order"><i data-toggle="tooltip" title="Track order" class="icon-map"></i></a>
                                        </div>
                                    </div>
                                    <!-- box-bottom -->
                                </div>
                                <!-- box -->
                            </li>
                            <!-- my order li -->
                            <!-- my order li -->
                            <li>
                                <!-- box -->
                                <div class="box full_row wow fadeInUp">
                                    <!-- box top-->
                                    <div class="box-top full_row">
                                        <h4>Marash</h4>
                                        <p class="price">BD 12.700</p>
                                        <div class="status">Status : <span class="pending">Pending</span></div>
                                    </div>
                                    <!-- box top-->
                                    <!-- box-bottom -->
                                    <div class="box-bottom full_row">
                                        <p class="id">Order Id : #OTB000145</p>
                                        <p>01-04-2019 1.30 PM</p>
                                        <div class="or-btns">
                                            <a href="#view_order" data-toggle="modal" data-target="#view_order"><i data-toggle="tooltip" title="View Order" class="icon-eye"></i></a>
                                            <a href="#"><i data-toggle="tooltip" title="Reorder" class="icon-reload"></i></a>
                                            <a href="#track_order" data-toggle="modal" data-target="#track_order"><i data-toggle="tooltip" title="Track order" class="icon-map"></i></a>
                                        </div>
                                    </div>+orderKey
                                    <!-- box-bottom -->
                                </div>
                                <!-- box -->
                            </li>
                            <!-- my order li -->
                            <!-- my order li -->
                            <li>
                                <!-- box -->
                                <div class="box full_row wow fadeInUp">
                                    <!-- box top-->
                                    <div class="box-top full_row">
                                        <h4>Jasmi's Coffee</h4>
                                        <p class="price">BD 12.700</p>
                                        <div class="status">Status : <span class="cancelled">Cancelled</span></div>
                                    </div>
                                    <!-- box top-->
                                    <!-- box-bottom -->
                                    <div class="box-bottom full_row">
                                        <p class="id">Order Id : #OTB000145</p>
                                        <p>01-04-2019 1.30 PM</p>
                                        <div class="or-btns">
                                            <a href="#view_order" data-toggle="modal" data-target="#view_order"><i data-toggle="tooltip" title="View Order" class="icon-eye"></i></a>
                                            <a href="#"><i data-toggle="tooltip" title="Reorder" class="icon-reload"></i></a>
                                            <a href="#track_order" data-toggle="modal" data-target="#track_order"><i data-toggle="tooltip" title="Track order" class="icon-map"></i></a>
                                        </div>
                                    </div>
                                    <!-- box-bottom -->
                                </div>
                                <!-- box -->
                            </li> --}}
                            <!-- my order li -->
                        </ul>
    <!-- View Order modal -->

    <div class="modal view_order_ui fade" id="view_order">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                    <h4 class="modal-title">{{__('Order Info')}}</h4>
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
            <h3> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{__('Alert!')}}</h3>
            </div>
            <div class="modal-body">
            <p>{{__('Do You Want to reorder?')}}</p>
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
                    <h4 class="modal-title">{{__('Track Order')}}</h4>
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

{{--<script>
    
    function myMap() {
        var mapCanvas = document.getElementById("map");
        var myCenter = new google.maps.LatLng(13.3460049, 74.7571555);
        var mapOptions = {
            center: myCenter,
            zoom: 5
        };
        var map = new google.maps.Map(mapCanvas, mapOptions);
        var marker = new google.maps.Marker({
            position: myCenter,
            icon: "{{ asset(FRONT_END_BASE_PATH.'img/icon-home.png')}}",
            animation: google.maps.Animation.BOUNCE
        });
        marker.setMap(map);
    }

    $("#track_order").on("shown.bs.modal", function() {
        myMap();
    });
</script>

<script src="https://maps.googleapis.com/maps/api/js?callback=myMap"></script> --}}

<script src="{{ url('resources/assets/socket.io.js') }}"></script>
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
     
    var latlng = new google.maps.LatLng(parseFloat({{config('webconfig.app_latitude')}}),parseFloat({{config('webconfig.app_longitude')}}));

    var markers = [];

    var image = {
            url: '{{ url("resources/assets/general/delivery-pin.png") }}',
            // This marker is 20 pixels wide by 32 pixels high.
            size: new google.maps.Size(40, 50),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
        };

        var homeIcon = {
            url: '{{ url("resources/assets/general/pin-home.png") }}',
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
var URL = "{{ config('webconfig.tracking_url') }}";
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




socket.emit('driver_location', { order_key : orderkey, company_id: '{{ config("webconfig.company_id")}}'});
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
{{-- <script src="{{ url('resources/assets/socket.io.js') }}"></script>
<script>    
function initMap() {
    
    var latlng = new google.maps.LatLng(parseFloat({{config('webconfig.app_latitude')}}),parseFloat({{config('webconfig.app_longitude')}}));

    var markers = [];

    var driverimage = {
            url: '{{ url("resources/assets/general/delivery-pin.png") }}',            
            // This marker is 20 pixels wide by 32 pixels high.
            size: new google.maps.Size(80, 80),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
        };
    var customerimage = {            
            url: '{{ url("resources/assets/general/pin-home.png") }}',
            // This marker is 20 pixels wide by 32 pixels high.
            size: new google.maps.Size(80, 80),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
        };

    var map = new google.maps.Map(document.getElementById('track-map'), {
        center: latlng,
        zoom: 15,
        mapTypeId: 'roadmap'
    });   

    var customerMarker = new google.maps.Marker({
            position: {lat: {{$userAddress->latitude}}, lng: {{$userAddress->longitude}}},
            map: map,
            icon: customerimage,
            //title: data.driver_name,
        });
    customerMarker.setMap(map);

    var driverMarker = new google.maps.Marker({
        position: {lat: {{$userAddress->latitude}}, lng: {{$userAddress->longitude}}},
        map: map,
        icon: driverimage,
    });
    driverMarker.setMap(map);

    content = '<div id="content">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<h2 id="firstHeading" class="firstHeading">Driver Info</h2>'+
        '<div id="bodyContent">'+
        '<p><b>Driver Name : </b> <span id="driver_name"></span> </p>'+
        '<p><b>Driver Email : </b> <span id="driver_email"></span> </p>'+
        '<p><b>Driver Phone Number : </b> <span id="driver_phone"></span> </p>'+
        '</div>'+
        '</div>';
        var infowindow = new google.maps.InfoWindow()      
        infowindow.close();
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


var driver_name = "";
// Create SocketIO instance, connect
var URL = "{{ config('webconfig.tracking_url') }}";
const socket = io(URL);    

socket.on('connect',function() {
    console.log("Socket has connected");
});
socket.emit('driver_location', { order_key : '{{$order_key}}', company_id: '{{ config("webconfig.company_id")}}'});
    // Add a connect listener
    socket.on('driver_location',function(data) {
        
        console.log('Client has connected to the server!',data); 
        driver_name = data.driver_name;
        var latlng = new google.maps.LatLng(data.latitude, data.longitude);
        driverMarker.setPosition(latlng);        
        $('#driver_name').html(data.driver_name);
        $('#driver_email').html(data.driver_email);
        //$('#driver_name').html(data.driver_name );
    });
}
</script> --}}

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('webconfig.map_key') }}&callback=initMap&libraries=drawing,places&sensor=false"></script>
@endsection