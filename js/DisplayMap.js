//handle the google map system

var map;
var myCenter=new google.maps.LatLng(48.856579,2.346525);
var marker;
var adresse;
var geocoder;


//initialize the map
function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:5,
  mapTypeId:google.maps.MapTypeId.HYBRID
  };

  map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
	geocoder = new google.maps.Geocoder();
  google.maps.event.addListener(map, 'click', function(event) {
	if(marker){marker.setMap(null)}
    placeMarker(event.latLng);
  });
}
//put the marker on the map
function placeMarker(location) {
  marker = new google.maps.Marker({
    position: location,
    map: map,
  });
  var infowindow = new google.maps.InfoWindow({
    content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
  });
  infowindow.open(map,marker);
  document.getElementById('lat').value = location.lat(); // set the coords of the marker in the inputs of the form
  document.getElementById('lng').value = location.lng();
   var latlng = new google.maps.LatLng(location.lat(), location.lng());
   

    geocoder.geocode( { 'location': latlng }, function(results, status) {
		 
		    if (status == google.maps.GeocoderStatus.OK) { 
	
	if(results[0]){
	 var elt = results[0].address_components;
          for(i in elt){
      
            if(elt[i].types[0] == 'country'){
            document.getElementById('pays').value = elt[i].long_name; //display the country name in the input of the form
			
			}
          }
		  }	
	
    } else {
     document.getElementById('pays').value = 'Pays introuvable';
    }

  }); 
  
}

function TrouverAdresse() {
  // get the adress entered in the form
  adresse = document.getElementById('adresse').value;
  
  geocoder.geocode( { 'address': adresse}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
    
	
    	if(marker){marker.setMap(null)}		  
	
	  
	placeMarker(results[0].geometry.location);
	
	 map.setCenter(results[0].geometry.location);
	 
	 
	  	
    } else {
      alert('Adresse introuvable: ' + status);
    }

  });

}



google.maps.event.addDomListener(window, 'load', initialize);