@extends('admin.layouts.layout')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">                
                        <h3 class="box-title">@lang('admincommon.Delivery boys')</h3>
                    </div>
                    <div class="box-body">
                        <div id="track-map" style="height:500px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script src="{{ url('resources/assets/socket.io.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.min.js"></script>
<script>    
function initMap() {
    
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

    var map = new google.maps.Map(document.getElementById('track-map'), {
        center: latlng,
        zoom: 02,
        mapTypeId: 'roadmap'
    });

// Create SocketIO instance, connect
var URL = "{{ config('webconfig.tracking_url') }}";
const socket = io(URL);    
var currentMarkers = [];

var speed = 60;
var delay = 100;
var prevPosition = [];
var log=[];

 log.difference = false;
        log.data = false;
        window.fitBounds = true;

socket.on('connect',function() {
    window.driver_marker = [];      
    window.drivers = [];  
    window.timeOuts = []; 
    /* console.log("Socket has connected"); */
});
socket.emit('company_drivers', {'company_id':'{{ config("webconfig.company_id")}}'});

    // Add a connect listener
    socket.on('company_drivers',function(data) {
        var difference = [];
          difference = _.differenceBy(window.drivers,data, 'driver_id');
          if(log.data){
            console.log(data);
          }
          if(log.difference){
            console.log(difference);
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
@endsection