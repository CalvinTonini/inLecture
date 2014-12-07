/**
 * scripts.js
 *
 * local javascript for maps version
 *
 * based on from CS50 problem set 8
 */

// Google Map
var map;

// markers for map
var markers = [];

// info window
var info = new google.maps.InfoWindow();

// execute when the DOM is fully loaded
$(function() {

    // styles for map
    // https://developers.google.com/maps/documentation/javascript/styling
    var styles = [

        // hide Google's labels
        {
            featureType: "all",
            elementType: "labels",
            stylers: [
                {visibility: "off"}
            ]
        },

        // hide roads
        {
            featureType: "road",
            elementType: "geometry",
            stylers: [
                {visibility: "off"}
            ]
        }

    ];

    // options for map
    // https://developers.google.com/maps/documentation/javascript/reference#MapOptions
    var options = {
        center: {lat: 42.3770, lng: -71.1256}, // Cambridge, Massachusetts
        disableDefaultUI: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        maxZoom: 18,
        panControl: true,
        styles: styles,
        zoom: 15,
        zoomControl: true
    };

    // get DOM node in which map will be instantiated
    var canvas = $("#map-canvas").get(0);

    // instantiate map
    map = new google.maps.Map(canvas, options);

    // attempt to geolocate, if yes, re-center at that position
    // https://developers.google.com/maps/documentation/javascript/examples/map-
    // geolocation
    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = new google.maps.LatLng(position.coords.latitude,
                                            position.coords.longitude);
            map.setCenter(pos);
        });
    }

    // configure UI once Google Map is idle (i.e., loaded)
    google.maps.event.addListenerOnce(map, "idle", configure);

});

/**
 * Adds marker for place to map.
 */
function addMarker(place)
{
    // where it is
    var latLng = new google.maps.LatLng(place.lat, place.lng);
    // drop the marker
    var image = 'http://maps.google.com/mapfiles/kml/pal2/icon28.png';
    var marker = new MarkerWithLabel(
    {
        position: latLng,
        draggable: false,
        icon: image,
        map: map,
        // name the marker
        labelContent: place.name,
        labelAnchor: new google.maps.Point(22, 0),
        labelClass: "label",
    });
    // add to markers array
    markers.push(marker);

    // get articles matching marker (asynchronously)
    var content = {
        id: place.name
    };
    $.getJSON("json/query.php", content)
    .done(function(data, textStatus, jqXHR)
    {
        // if one clicks the button
        google.maps.event.addListener(marker, "click", function() {
            // if there are no results
            if (data.length == 0)
            {
                showInfo(marker, "<p>No lectures in session</p>");
            }
            // if there are results
            else
            {
                // populate list
                var content = "<ul>";
                for (var i = 0; i < data.length; i++)
                {
                    content += "<li>"+data[i].fieldnumber+" "+data[i].title
                    +", "+data[i].faculty+", Room "+data[i].room+"</li>";
                }
                content += "</ul>";
                // send to the box
                showInfo(marker, content);
            }
        });
    })
    // if something goes wrong
    .fail(function(jqXHR, textStatus, errorThrown)
    {
        // log error to browser's console
        console.log(errorThrown.toString());
    });
}

/**
 * Configures application.
 */
function configure()
{
    // update UI after map has been dragged
    google.maps.event.addListener(map, "dragend", function() {
        update();
    });

    // update UI after zoom level changes
    google.maps.event.addListener(map, "zoom_changed", function() {
        update();
    });

    // remove markers whilst dragging
    google.maps.event.addListener(map, "dragstart", function() {
        removeMarkers();
    });

    // re-enable ctrl- and right-clicking (and thus Inspect Element) on Google Map
    // https://chrome.google.com/webstore/detail/allow-right-click/hompjdfbfmmmgflfjdlnkohcplmboaeo?hl=en
    document.addEventListener("contextmenu", function(event) {
        event.returnValue = true;
        event.stopPropagation && event.stopPropagation();
        event.cancelBubble && event.cancelBubble();
    }, true);

    // update UI
    update();

}

/**
 * Removes markers from map.
 */
function removeMarkers()
{
    // for every marker on the map
    for (var i = 0; i < markers.length; i++)
    {
        // get rid of it
        markers[i].setMap(null);
    }
    // clear the array
    markers.length = 0;
}

/**
 * Shows info window at marker with content.
 */
function showInfo(marker, content)
{
    // start div
    var div = "<div id='info'>";
    if (typeof(content) === "undefined")
    {
        div +="Loading!";
    }
    else
    {
        div += content;
    }

    // end div
    div += "</div>";

    // set info window's content
    info.setContent(div);

    // open info window (if not already open)
    info.open(map, marker);
}

/**
 * Updates UI's markers.
 */
function update()
{
    // get map's bounds
    var bounds = map.getBounds();
    var ne = bounds.getNorthEast();
    var sw = bounds.getSouthWest();

    // get places within bounds (asynchronously)
    var parameters = {
        ne: ne.lat() + "," + ne.lng(),
        sw: sw.lat() + "," + sw.lng()
    };
    $.getJSON("json/update.php", parameters)
    .done(function(data, textStatus, jqXHR) {

        // remove old markers from map
        removeMarkers();

        // add new markers to map
        for (var i = 0; i < data.length; i++)
        {
            addMarker(data[i]);
        }
     })
     .fail(function(jqXHR, textStatus, errorThrown) {

         // log error to browser's console
         console.log(errorThrown.toString());
     });
};
