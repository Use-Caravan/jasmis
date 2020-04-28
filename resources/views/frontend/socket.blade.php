<script src="{{ url('resources/assets/socket.io.js') }}"></script>
<script>
    // Create SocketIO instance, connect    

    var URL = 'https://nodeboxfood.duceapps.com';
    var URL = 'http://192.168.1.113:8031';

    const socket = io(URL);

    socket.on('connect',function() {
        console.log("Socket has connected");
    });

    socket.emit('company_drivers', {'company_id':'5c776d5b836c325ee40ed1b3'});
    // Add a connect listener
    socket.on('company_drivers',function(data) {
        console.log('Client has connected to the server!',data);
    });    
</script>

{{--
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCDKBu1aPoiFQX0tCZUJJ2I8_JRW7f_vmU"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.min.js"></script>

<script>   

window.is_map_init = false;

function initialize() {
    var driver_location;
    var count = < ?php echo $count; ?>;
    var locations = JSON.parse('< ?php echo $locations ;?>');
    console.log(locations);
    window.icon = {
        //url: < ?php //Com::res('img/deliveryboy_green.png', false) ?>, 
        scaledSize: new google.maps.Size(45, 45), // scaled size
        origin: new google.maps.Point(0,    0), // origin
        anchor: new google.maps.Point(22, 45) // anchor
    };

      window.bounds = new google.maps.LatLngBounds();
      var mapProp = {  
          center: new google.maps.LatLng(locations[0][1],locations[0][2]),
          zoom:10,
          mapTypeId:google.maps.MapTypeId.ROADMAP
      };
      window.map=new google.maps.Map(document.getElementById("googleMap"), mapProp);
    
     window.is_map_init = true;
}


function attachDetails(marker, details) 
{
    var infowindow = new google.maps.InfoWindow({
        content: details
    });

    marker.addListener('click', function() {
        infowindow.open(marker.get('map'), marker);
    });

    google.maps.event.addListener(infowindow, 'domready', function() {
        
    });
}
   runOnLoad(function() { 
    // Create SocketIO instance, connect
    var sockerURL = '< ?php echo $socketUrl; ?>';
    const socket = io(sockerURL);
    console.log('socket', socket);
    
    // Add a connect listener
    socket.on('connect',function() {
        console.log('connected');
      window.driver_marker = [];      
      window.drivers = [];  
     // console.log('Client has connected to the server!');
    });
   
    socket.on('all_drivers',function(data) {
        console.log('all_drivers', data);
      var difference = [];
      difference = _.differenceBy(window.drivers,data, 'delivery_boy_key');
      if(difference.length > 0){
        difference.map(function(marker) {
          window.driver_marker[marker.deliveryboy_key].setMap(null);
                    
          delete window.driver_marker[marker.deliveryboy_key];
          _.remove(window.drivers, function(n) {
              return n.deliveryboy_key === marker.deliveryboy_key;
          });
        });
      }

      if(is_map_init && data && data.length > 0) {
        data.map(function(driver) {
          var position = new google.maps.LatLng(driver.latitude, driver.longitude);
          if(window.driver_marker[driver.deliveryboy_key]) {
            bounds.extend(position);
            window.driver_marker[driver.deliveryboy_key].setPosition(
              position  
            );
          } else {
            markerOption = {
              position: position,
              map: map,
              title: driver.deliveryboy_name,
              icon: icon
            }; 
            < ?php if(\Yii::$app->getRequest()->get('marker') === 'false'): ?>
                console.log('markerOption', markerOption); 
                    delete markerOption.icon;
            < ?php endif; ?>
            window.driver_marker[driver.deliveryboy_key] = new google.maps.Marker(markerOption);
            bounds.extend(position);
            map.fitBounds(bounds);
            window.drivers.push(driver);
          }
        });
      }
        
    });
});
</script>    --}}