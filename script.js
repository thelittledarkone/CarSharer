var map;
var directionsDisplay;
// Set Map Options
var londonLatLng = {lat:51.5, lng:-0.1}
var mapOptions = {
    center: londonLatLng,
    zoom: 10,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};

//Autocomplete
var options = {
    types: ['(cities)']
}

var depInput = document.getElementById("departure");
var desInput = document.getElementById("destination");
//var depEdit = document.getElementById("editDeparture");
//var desEdit = document.getElementById("editDestination");

var autocomplete1 = new google.maps.places.Autocomplete(depInput, options);
var autocomplete2 = new google.maps.places.Autocomplete(desInput, options);
//var autocomplete3 = new google.maps.places.Autocomplete(depEdit, options);
//var autocomplete4 = new google.maps.places.Autocomplete(desEdit, options);

//create a DirectionsService
var directionsService = new google.maps.DirectionsService();

//On Load
google.maps.event.addDomListener(window, 'load', initialize);

//Initialize Funcion: Draws map
function initialize(){
//    Create Directions Renderer to display route
    directionsDisplay = new google.maps.DirectionsRenderer();
    //Create Map
    map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);
//    Bind Directions Renderer to map
    directionsDisplay.setMap(map);
}

//Calculate the route when selecting autocomplete
google.maps.event.addListener(autocomplete1, 'place_changed', calcRoute);
google.maps.event.addListener(autocomplete2, 'place_changed', calcRoute);

//Calculate Route
function calcRoute(){
    var start = $('#departure').val();
    var end = $('#destination').val();
    var request = {
        origin: start,
        destination: end,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    if(start && end){
        directionsService.route(request, function(response, status){
            if(status == google.maps.DirectionsStatus.OK){
                directionsDisplay.setDirections(response);
            }
        });
    }else{
        initialize();
    }
}
