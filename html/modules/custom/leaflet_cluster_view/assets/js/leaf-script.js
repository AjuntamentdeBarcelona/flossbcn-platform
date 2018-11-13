Drupal.behaviors.leaflet_cluster_view = {
  attach: function (context, settings) {
    if(jQuery('#map', context).length > 0) {
      leaflet_cluster_view_generate_map(drupalSettings);
    }
    
	jQuery('body').on('click', 'a.click-map', function() {
		var elemento = jQuery(this).attr('data');
		
	});
  }
};


function leaflet_cluster_view_generate_map(drupalSettings) {
  var baseUrl = drupalSettings['path'].baseUrl;
  // Declarar parametros.
  var parametertypeview = '';
  var name = '';
  
  // Parametros
  parametertypeview = leaflet_cluster_getParameterByName('typeview');
  name = leaflet_cluster_getParameterByName('name');
  ambits = leaflet_cluster_getParameterByName('ambits');
  
  var url1 = baseUrl + drupalSettings['leaflet_cluster_view'].img1;
  var url2 = baseUrl + drupalSettings['leaflet_cluster_view'].img2;

  var pathModulo = baseUrl + drupalSettings['leaflet_cluster_view'].path_module;
  
  var pathAjax = baseUrl + 'leaflet_cluster_view/test';
  
  /* console.log(parametertypeview);
  if(parametertypeview != null) {
	 if(parametertypeview == 'orgs') {
		pathAjax = baseUrl + 'leaflet_cluster_view/orgs'; 
	 } else if (parametertypeview == 'projs') {
		 pathAjax = baseUrl + 'leaflet_cluster_view/proj';	 
	 } else if (parametertypeview == 'event') {
		 pathAjax = baseUrl + 'leaflet_cluster_view/events';	 
	 } 
  } */ 
  
  var pathElement = baseUrl + 'leaflet_cluster_view/route';
  var map = L.map( 'map', {
    center: [41.3828939, 2.1774322],
    minZoom: 2,
    zoom: 11,
    scrollWheelZoom: false
  });
  
  map.once('focus', function() { map.scrollWheelZoom.enable(); });
  
  map.on('click', function() {
  if (map.scrollWheelZoom.enabled()) {
    map.scrollWheelZoom.disable();
    }
    else {
    map.scrollWheelZoom.enable();
    }
  });



  L.tileLayer( 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
   attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
   subdomains: ['a','b','c']
  }).addTo( map );


  var myIcon = L.icon({
    iconUrl: url1,
    iconRetinaUrl: url2,
    iconSize: [29, 24],
    iconAnchor: [9, 21],
    popupAnchor: [0, -14]
  });

  var markerClusters = L.markerClusterGroup();
  
  jQuery.ajax({
      type: "GET",
      url:pathAjax + "?type=" + parametertypeview + '&name=' + name + '&ambits=' + ambits,
      success: function(data){
        var markers = data;
        console.log(markers);
        for ( var i = 0; i < markers.length; ++i )
        {
	        var type = 'Event';
	        
	        if(markers[i].type == 'entities') {
		        type = 'Entity';
	        } else if (markers[i].type == 'public_group') {
		        type = 'Project';
	        } 
	        var label = '';
	        if(markers[i].label) {
		        label = markers[i].label;
	        } else if(markers[i].title) {
		        label = markers[i].title;
	        }
        	var popup =  '<a class="click-map" href="' + pathElement + '/' + markers[i].type + '-' + markers[i].entity_id + '">' + label + '</a><br/><p>' + type + '</p>';
			var m = L.marker( [markers[i].field_geolocation_lat, markers[i].field_geolocation_lng], {icon: myIcon} )
                          .bindPopup( popup ); 
            /* var m = L.marker( [markers[i].field_geolocation_lat, markers[i].field_geolocation_lng], {icon: myIcon, alt:markers[i].entity_id + "-" + markers[i].type}).on('click', onClickMarkerCustom);
				*/
			markerClusters.addLayer( m ); 
        }
        map.addLayer( markerClusters );
     }
  });
  
  function onClickMarkerCustom(e) {
	  console.log(e.target.options.alt);
  }
}


function leaflet_cluster_getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
