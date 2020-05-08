

<script>
var drawingManager;
var all_overlays = [];
var selectedShape;
var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082'];
var selectedColor;
var colorButtons = {};

function clearSelection() {
  if (selectedShape) {
    selectedShape.setEditable(false);
    selectedShape = null;
  }
}

function setSelection(shape) {
  clearSelection();
  selectedShape = shape;
  shape.setEditable(true);
  selectColor(shape.get('fillColor') || shape.get('strokeColor'));
}

function deleteSelectedShape() {
  if (selectedShape) {
    selectedShape.setMap(null);
  }
}

function deleteAllShape() {

    /* console.log('deleteAllShape'); */
    for (var i = 0; i < all_overlays.length; i++) {
        all_overlays[i].setMap(null);
    }
    all_overlays = [];
}

function selectColor(color) {
    return true;
  selectedColor = color;
  for (var i = 0; i < colors.length; ++i) {
    var currColor = colors[i];
    colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
  }

  // Retrieves the current options from the drawing manager and replaces the
  // stroke or fill color as appropriate.
  var polylineOptions = drawingManager.get('polylineOptions');
  polylineOptions.strokeColor = color;
  drawingManager.set('polylineOptions', polylineOptions);

  var rectangleOptions = drawingManager.get('rectangleOptions');
  rectangleOptions.fillColor = color;
  drawingManager.set('rectangleOptions', rectangleOptions);

  var circleOptions = drawingManager.get('circleOptions');
  circleOptions.fillColor = color;
  drawingManager.set('circleOptions', circleOptions);

  var polygonOptions = drawingManager.get('polygonOptions');
  polygonOptions.fillColor = color;
  drawingManager.set('polygonOptions', polygonOptions);
}

function setSelectedShapeColor(color) {
  if (selectedShape) {
    if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) {
      selectedShape.set('strokeColor', color);
    } else {
      selectedShape.set('fillColor', color);
    }
  }
}

function makeColorButton(color) {
  var button = document.createElement('span');
  button.className = 'color-button';
  button.style.backgroundColor = color;
  google.maps.event.addDomListener(button, 'click', function() {
    selectColor(color);
    setSelectedShapeColor(color);
  });

  return button;
}

function buildColorPalette() {
    return true;
  var colorPalette = document.getElementById('color-palette');
  for (var i = 0; i < colors.length; ++i) {
    var currColor = colors[i];
    var colorButton = makeColorButton(currColor);
    colorPalette.appendChild(colorButton);
    colorButtons[currColor] = colorButton;
  }
  selectColor(colors[0]);
}

function initMap() {

    var latitude = $('.area_latitude').val();
    var longitude = $('.area_longitude').val();
    if(latitude != '' && longitude != ''){
        var latlng = new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
    }else{
        var latlng = new google.maps.LatLng(<?php echo e(config('webconfig.app_latitude')); ?>, <?php echo e(config('webconfig.app_longitude')); ?>);
    }

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.RAODMAP,
        disableDefaultUI: true,
        zoomControl: true
    });

    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
    });

    // Create the search box and link it to the UI element.
    var input = document.getElementById('places_autocomplete');    
    
    var options = {
        componentRestrictions: {country: "bh"}
    };
    var autocomplete = new google.maps.places.Autocomplete(input,options);

    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();    
        /* console.log(place.geometry.location);  */
        marker.setPosition(place.geometry.location);
        map.setCenter(place.geometry.location);
        deleteAllShape();
    });    

    var polyOptions = {
        strokeWeight: 5,
        fillOpacity: 0.45,
        editable: true,
        fillColor: '#39b54a',
        strokeColor: '#2d9e3d',
    };
    var circleOptions = {
        fillColor: '#39b54a',
        fillOpacity: 0.45,
        strokeWeight: 5,
        strokeColor: '#2d9e3d',
        clickable: false, 
        editable: true,                
        zIndex: 1,          
    };
    
    // Creates a drawing manager attached to the map that allows the user to draw
    // markers, lines, and shapes.
    drawingManager = new google.maps.drawing.DrawingManager({

    drawingMode: zone_type,
    drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            /* drawingModes: ['circle']  */
            drawingModes: ['circle', 'polygon']
        },
        markerOptions: {
            draggable: true
        },
        polylineOptions: {
            editable: true
        },
        rectangleOptions: polyOptions,
        circleOptions: circleOptions,
        polygonOptions: polyOptions,
        map: map
    });
    var existsCircle;
    if( $('#zone_type').val() == '') {
        var zone_type =  google.maps.drawing.OverlayType.CIRCLE
    }else{
        if($('#zone_type').val() == <?php echo e(DELIVERY_AREA_ZONE_CIRCLE); ?>) {            
            var zone_type = google.maps.drawing.OverlayType.CIRCLE
            if($('#circle_latitude').val() != '' && $('#circle_longitude').val() && $('#zone_radius').val() != ""){
               var center = {lat: parseFloat($('#circle_latitude').val()), lng: parseFloat($('#circle_longitude').val()) }
                var existsCircleOption = {
                    fillColor: '#39b54a',
                    fillOpacity: 0.45, 
                    strokeWeight: 5,
                    strokeColor: '#2d9e3d',
                    clickable: false, 
                    editable: true,                
                    zIndex: 1,   
                    fillOpacity: 0.35,
                    map: map,
                    center: center,
                    radius: parseFloat($('#zone_radius').val())
                };                
                existsCircle = new google.maps.Circle(existsCircleOption); 
                drawingManager.setDrawingMode(null);
                all_overlays.push(existsCircle);
                bindShapeEvent('circle', existsCircle);                
            }
        } else {
            var zone_type = google.maps.drawing.OverlayType.POLYGON    

            <?php if($model->exists): ?>
            console.log('<?php echo $model->zone_latlng; ?>');
            $('#zone_latlng').val('<?php echo $model->zone_latlng; ?>');
            
            if($('#zone_latlng').val() != '') {

                var existsPolygon;
                var zontLatLng = JSON.parse($('#zone_latlng').val());
                var coords = [];

                $.each(zontLatLng, function(key, value) {
                    cvalue = value.split(' ');
                    coords[key] = { "lat" : parseFloat(cvalue[0]), "lng" : parseFloat(cvalue[1]) };
                });

                var existsPolygonOptions = {
                    paths: coords,
                    strokeColor: '#2d9e3d',
                    strokeOpacity: 0.8,
                    strokeWeight: 5,
                    fillColor: '#39b54a',
                    fillOpacity: 0.45
                };
                existsPolygon  =  new google.maps.Polygon(existsPolygonOptions);
                drawingManager.setDrawingMode(null);
                existsPolygon.setMap(map);
                all_overlays.push(existsPolygon);
                bindShapeEvent('polygon', existsPolygon);
            }
            <?php endif; ?>
        }
    }     

    
    var polygonArray = [];

    google.maps.event.addListener(drawingManager, 'radius_changed', function(e) {

        /* console.log(e); */
    })


    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        
        if (e.type != google.maps.drawing.OverlayType.MARKER) {
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);

            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.            
            var newShape = e.overlay;
            newShape.type = e.type;                        
            
            all_overlays.push(e.overlay);
            bindShapeEvent(e.type, newShape);
        }
    });    


    // Clear the current selection when the drawing mode is changed, or when the
    // map is clicked.  
    //google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', function()
    {            
        if(drawingManager.drawingMode == 'circle'){
            $('#zone_type').val(1);            
            deleteAllShape();
        }else if(drawingManager.drawingMode == 'polygon'){
            $('#zone_type').val(2);
            deleteAllShape();
        }
    });
  google.maps.event.addListener(map, 'click', clearSelection);
  // google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
  // google.maps.event.addDomListener(document.getElementById('delete-all-button'), 'click', deleteAllShape);

  buildColorPalette();
}
function getCircleData(circle)
{
    /* console.log(circle); */
    $('#zone_type').val(<?php echo e(DELIVERY_AREA_ZONE_CIRCLE); ?>); 
    $('#circle_latitude').val(circle.center.lat());
    $('#circle_longitude').val(circle.center.lng());
    $('#zone_radius').val( circle.radius );

    /* console.log(circle.radius); */
}
function getPolygonData(polygon)
{
    $('#zone_type').val(<?php echo e(DELIVERY_AREA_ZONE_POLYGON); ?>);         
    var coordinates = [];              
    for (var i = 0; i < polygon.getPath().getLength(); i++) {                     
        coordinates[i] = polygon.getPath().getAt(i).toUrlValue(6).split(',');
    }
    $('#zone_latlng').val(JSON.stringify(coordinates));
    /* console.log(coordinates); */
}

function bindShapeEvent(type, newShape) {

    /* console.log('bindShapeEvent', arguments); */
    if(type == 'circle'){
        google.maps.event.addListener(newShape, 'radius_changed', function (circle) {
            getCircleData(newShape);
        });
        google.maps.event.addListener(newShape, 'center_changed', function (circle) {
            getCircleData(newShape);
        });
        getCircleData(newShape); // On Create
    } else if(type == 'polygon'){                
        google.maps.event.addListener(newShape.getPath(), 'set_at', function() {
            getPolygonData(newShape);                        
        });
        google.maps.event.addListener(newShape.getPath(), 'insert_at', function() {
            getPolygonData(newShape);
        });
        getPolygonData(newShape);            
    }
    google.maps.event.addListener(newShape, 'click', function() {
        setSelection(newShape);
    });    
    setSelection(newShape);
}
</script>