<!-- add address modal -->

    <div class="modal address_popup fade" id="modal_address">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">

                    <!-- header -->
                    <div class="address-header">

                        <button class="clos" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>

                        <div class="text-center ad-textbox">
                            <div class="form-group">
                                <label class="icon"><i class="fa fa-map-marker" aria-hidden="true"></i></label>
                                <input type="text" class="form-control" placeholder=<?php echo app('translator')->getFromJson("Enter Address"); ?> id="place_library">
                            </div>
                        </div>

                        <div id="map" class="address-map"></div>                        
                    </div>
                    <!-- header -->
                    <div class="address-body">
                        <?php echo e(Form::open(['route' => 'address.store', 'id' => 'address-form', 'class' => 'form-horizontal', 'method' => 'POST' ,"createAction" => route('address.store')])); ?>

                        <?php echo e(Form::hidden("latitude",'' ,["class" => "area_latitude"])); ?>

                        <?php echo e(Form::hidden("longitude",'' ,["class" => "area_longitude"])); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::select('address_type_id',$addressTypes, [], ['class' => 'form-control','placeholder' => __('Address Type'),'id' => 'address_type'] )); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::text("address_line_one",'' ,['class' => 'form-control','placeholder' => __('Address Line 1')])); ?>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::text("address_line_two",'' ,['class' => 'form-control','placeholder' => __('Address Line 2')])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::text("company",'' ,['class' => 'form-control','placeholder' => __('Company')])); ?>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo e(Form::text("landmark",'' ,['class' => 'form-control','placeholder' => __('Landmark')])); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Html::decode( Form::button('<span class="shape">'.__('Save Address').'</span>', ['type'=>'submit', 'class' => 'shape-btn loader shape1']) ); ?>

                                </div>
                            </div>
                        </div>
                        <?php echo e(Form::close()); ?>

                        <?php echo JsValidator::formRequest('App\Http\Requests\Frontend\AddressRequest', '#address-form'); ?>                   
                       </div>

                </div>
            </div>
        </div>
    </div>

    <!-- add address modal -->

<script>

function initMap() {

    var latitude = $('.area_latitude').val();
    var longitude = $('.area_longitude').val();
    if(latitude != '' && longitude != ''){
        var latlng = new google.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
    }else{
        var latlng = new google.maps.LatLng(<?php echo e(config('webconfig.app_latitude')); ?>, <?php echo e(config('webconfig.app_longitude')); ?>);
    }        
    var map = new google.maps.Map(document.getElementById('map'), {
        center: latlng,
        zoom: 13,
        mapTypeId: 'roadmap'
    });
    var image = {
            url: '<?php echo e(url("resources/assets/general/map_pin.png")); ?>',
            // This marker is 20 pixels wide by 32 pixels high.
            size: new google.maps.Size(80, 80),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
        };    
    var marker = new google.maps.Marker({
        position: latlng,        
        url: image,
        icon: image,
        map: map,            
        draggable: true,
        animation: google.maps.Animation.DROP    
    });
    google.maps.event.addListener(marker, 'dragend', function(event) 
    {   
        $('.area_latitude').val(event.latLng.lat());
        $('.area_longitude').val(event.latLng.lng());
        var currentLatitude =  event.latLng.lat();
        var currentLongitude = event.latLng.lng();  
        var latLng =  new google.maps.LatLng( currentLatitude, currentLongitude ); 
        var google_maps_geocoder = new google.maps.Geocoder();
            google_maps_geocoder.geocode(
            { 'latLng': latLng },
                function( results, status ) {
                    if ( status == google.maps.GeocoderStatus.OK && results[0] ) {
                        $('#place_library').val(results[0].formatted_address)
                    }
                }
            );

    });  



    // Create the search box and link it to the UI element.
    var input = document.getElementById('place_library');    
    var options = {
        componentRestrictions: {country: "bh"}
    };
    var autocomplete = new google.maps.places.Autocomplete(input,options);
    autocomplete.addListener('place_changed', function() {        
        var place = autocomplete.getPlace();
        marker.setPosition(place.geometry.location);
        map.setCenter(place.geometry.location);        
        $('.area_latitude').val(place.geometry.location.lat());
        $('.area_longitude').val(place.geometry.location.lng());
    });
    google.maps.event.addListener(map, 'click', function(event) {        
        marker.setPosition(event.latLng);
        $('.area_latitude').val(event.latLng.lat());
        $('.area_longitude').val(event.latLng.lng());
    });
}

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo e(config('webconfig.map_key')); ?>&callback=initMap&libraries=places&sensor=false"></script>