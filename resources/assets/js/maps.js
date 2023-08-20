var map,
    serbia = {lat: 44.818611, lng: 20.468056},
    markers = [],
    marker,
    geocoder,
    initialLocation,
    initialZoom;

function getLocations(){
    var locations = 0,
        bounds = new google.maps.LatLngBounds();
    clearMarkers();
    markers = [];

    $(".servis").each(function(i, item){
        locations++;
        $(".partner_number", $(this)).html(i+1);
        var googleLatLng = new google.maps.LatLng(
            parseFloat($(this).data('latitude'),10),
            parseFloat($(this).data('longitude'),10)
        );

        bounds.extend(googleLatLng);

        markers.push(new google.maps.Marker({
            position: googleLatLng,
            map: map,
            icon: urlTo('img/pin.png'),
            partner_id: 'p'+$(this).data('id')
        }));

        google.maps.event.addListener(markers[i], 'click', function(){
        	console.log(this);
            $('body,html').stop().animate({scrollTop: $("#"+this.partner_id).offset().top-10}, 800);
        });

        var infowindow = new google.maps.InfoWindow({
            maxWidth: 200,
            disableAutoPan: true
        });

        infowindow.setContent('<strong>'+$(".set_partner", $(this)).data("recipient")+'</strong><br />'+
            $(".set_partner", $(this)).data("address")+'<br />'+
            $(".set_partner", $(this)).data("postal_code")+' '+$(".set_partner", $(this)).data("city_name")+'<br />' +
            'Telefon: '+$(".set_partner", $(this)).data("phone"));

        google.maps.event.addListener(markers[i], "mouseover", function() {
            infowindow.open(map, this);
        });

        google.maps.event.addListener(markers[i], 'mouseout', function() {
            infowindow.close();
        });
    });

    if (locations > 1) {
        map.fitBounds(bounds);
        map.panToBounds(bounds);
    } else if (locations == 1) {
        map.setCenter(bounds.getCenter());
        map.setZoom(15);

    }
}

function clearMarkers(){
    for (var i = 0; i < markers.length; i++) {
        markers[i].setVisible(false);
        markers[i].setMap(null);
    }
}

function mapInitialize() {
    var mapOptions = {
        zoom: this.zoom || 7,
        center: this.center || serbia,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        visualRefresh: true,
        styles: [
            {
                featureType: 'all',
                stylers: [
                    { saturation: -20 }
                ]
            },{
                featureType: 'road.arterial',
                elementType: 'geometry',
                stylers: [
                    { hue: '#00ffee' },
                    { saturation: 50 }
                ]
            },{
                featureType: 'poi.business',
                elementType: 'labels',
                stylers: [
                    { visibility: 'off' }
                ]
            }
        ]
    };
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
}

function mapInitializeWithGeocoding(){
    geocoder = new google.maps.Geocoder();
    mapInitialize();
    if (initialZoom === undefined){
        initialZoom = 7;
    }

    if ((initialLocation.lat == 0) && (initialLocation.lng == 0)) {
        initialLocation = undefined;
    }

    if (initialLocation === undefined) {
        setMarker(serbia, initialZoom);
    }else {
        setMarker(initialLocation, initialZoom);
    }

}

function getLookupAddress(){

    var result='Srbija';

    if ($('#city_id').find('option:selected').text().length>0){
        result = result +', '+$('#city_id').find('option:selected').text();
    }

    if ($('#address').val().length>0){
        result = result +', '+$('#address').val();
    }

    return result;

}

function getLocation(location){
    var tmp = [
        parseFloat(location.lat(), 10),
        parseFloat(location.lng(), 10)
    ];

    $('#latitude').val(tmp[0]);
    $('#longitude').val(tmp[1]);
    return tmp;
}

function setMarker(pos, zoom){
    marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: pos,
        icon: urlTo('img/pin.png')
    });

    map.setZoom(zoom);

    map.setCenter(marker.getPosition());

    google.maps.event.addListener(marker, 'dragend', function(){        
        getLocation(this.getPosition());
    });
}


$('#city_id').on({
    'change': function(){
        var city_name = $(this).find('option:selected').text();
        if(city_name.length > 1){
            setTimeout( function(){ geocodeAddress(geocoder, map, 15)}, 500);
        }
    },
    'blur': function(){
        if($(this).val().length > 1){
            setTimeout( function(){ geocodeAddress(geocoder, map, 15)}, 500);
        }
    }
});

$('#address').on({
    'keyup': function(){
        if($(this).val().length > 2){
            setTimeout( function(){ geocodeAddress(geocoder, map, 15)}, 500);
        }
    },
    'blur': function(){
        if($(this).val().length > 2){
            setTimeout( function(){ geocodeAddress(geocoder, map, 15)}, 500);
        }
    }
});


function geocodeAddress(geocoder, resultsMap, zoom) {
    var address = getLookupAddress();
    geocoder.geocode({address : address}, function(results, status) {

        if (status === 'OK') {

            var pos = results[0].geometry.location;

            resultsMap.setCenter(pos);

            if(typeof marker != 'undefined'){
                marker.setPosition(pos);
                map.setZoom(zoom);
                map.panTo(marker.getPosition());
            }
            else{
                setMarker(pos, zoom);
            }

            getLocation(pos);

        } else {
            console.warn('Geocode was not successful for the following reason: ' + status);
        }
    });
}