<script type="text/javascript">
    function myMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: 24.953426668512783,
                lng: 67.00400814243413
            },
            zoom: 13
        });

        var input = document.getElementById('mymap');
        autocomplete = new google.maps.places.Autocomplete(input);
        searchBox = new google.maps.places.SearchBox(input);

        autocomplete.setFields(['address_components']);
        autocomplete.addListener('places_changed', function() {
            var place = autocomplete.getPlace();
            let result = place.address_components.map(a => a.long_name);
        });

        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();
            // console.log(places[0].geometry.location);
            console.log(places[0].geometry.location.lat());
            $('#longitude').val(places[0].geometry.location.lng().toFixed(6));
            $('#latitude').val(places[0].geometry.location.lat().toFixed(6));

            if (places.length == 0) {
                return;
            }

            var myCenter = new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng());
            var mapOptions = {
                center: myCenter,
                zoom: 15
            };

            var mapCanvas = document.getElementById("map");
            var map2 = new google.maps.Map(mapCanvas, mapOptions);
            var marker = new google.maps.Marker({
                position: myCenter
            });
            marker.setMap(map2);

            $(document).ready(function() {
                // click on map and set you marker to that position
                google.maps.event.addListener(map2, 'click', function(event) {
                    marker.setPosition(event.latLng);
                    getAddress(event.latLng.lat(), event.latLng.lng());
                    $('#longitude').val(event.latLng.lng().toFixed(6)).trigger("change");
                    $('#latitude').val(event.latLng.lat().toFixed(6)).trigger("change");
                    // alert( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() );
                });
            });

        });
    }

    function getAddress(lat, long) {
        $.ajax({
            url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + long + '&sensor=false&key=AIzaSyANRj-jLIgydGCb1M2dG7WjMsVVpC8xjjs',
            success: function(data) {
                $('#mymap').val(data.results[0].formatted_address);
            }
        });
    }

    function getLocation() {
        // console.log(navigator.geolocation);
        navigator.geolocation.getCurrentPosition(function(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

            getAddress(latitude, longitude);

            var myCenter = new google.maps.LatLng(latitude, longitude);
            var mapOptions = {
                center: myCenter,
                zoom: 13
            };

            var mapCanvas = document.getElementById("map");
            var map = new google.maps.Map(mapCanvas, mapOptions);
            var marker = new google.maps.Marker({
                position: myCenter
            });

            marker.setMap(map);

            $(document).ready(function() {
                // click on map and set you marker to that position
                google.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
                    getAddress(event.latLng.lat(), event.latLng.lng());
                    /*
                    $('#longitude').val(event.latLng.lng().toFixed(6)).trigger("change");
                    $('#latitude').val(event.latLng.lat().toFixed(6)).trigger("change");
                    */
                });
            });
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        getAddress(position.coords.latitude, position.coords.longitude);
    }

    getLocation();

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANRj-jLIgydGCb1M2dG7WjMsVVpC8xjjs&callback=myMap&libraries=places"></script>
