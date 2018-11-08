(function (win, doc) {
  // Latitud y longitud por defecto.
  var long = 0;
  var lat = 0;
  var valoresDefecto = true;

  // Modificar la longitud y latitud si está seteada.
  if(jQuery('input[name="field_geolocation[0][lat]"]').length) {
    if(jQuery('input[name="field_geolocation[0][lat]"]').val()) {
      valoresDefecto = false;
      lat = jQuery('input[name="field_geolocation[0][lat]"]').val();
    }
  }

  if(jQuery('input[name="field_geolocation[0][lng]"]').length) {
    if(jQuery('input[name="field_geolocation[0][lng]"]').val()) {
      valoresDefecto = false;
      long = jQuery('input[name="field_geolocation[0][lng]"]').val();
    }
  }

  // Convertir a parseFloat.
  lat = parseFloat(lat);
  long = parseFloat(long);

  // Definir un zom inicial
  var zoomint = parseInt(4);

  // Si latitud y longitud no son por defecto aumentar zoom
  if(valoresDefecto == false) {
      zoomint = parseInt(16);
  }

  // Instanciar el mapa.
  var map = new ol.Map({
    target: 'map',
    layers: [
        new ol.layer.Tile({
          source: new ol.source.OSM()
        })
    ],
    view: new ol.View({
      center: ol.proj.fromLonLat([long, lat]),
      zoom: zoomint
    })
  });

  // Preparar marker.
  var markerOrigin = new ol.Overlay({
      position: ol.proj.fromLonLat([long, lat]),
      positioning: 'center-center',
      element: document.getElementById('icon-img'),
      stopEvent: false
  });
  map.addOverlay(markerOrigin);


  map.on('singleclick', function (evt) {
    markerOrigin.setPosition(evt.coordinate);    
    
    var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
    var long = lonlat[0];
    var lat = lonlat[1];
    jQuery.ajax({
      type: "GET",
      url: 'http://photon.komoot.de/reverse?lon=' + long + '&lat=' + lat + '&distance_sort=true',
      success: function(data){	      
	      if(data.features[0].properties) {
		      // control_nominatim_save_segmented_return(data.features[0].properties);
	      }      
      }
  	}); 	
    control_nominatim_asign_coords(evt);
  });

  var placeholder = '';

  // placeholder = jQuery('#edit-field-direccio-complerta-0-value').val();
  // Instanciar geocoder (buscador de direcciones)
  var geocoder = new Geocoder('nominatim', {
    provider: 'photon',
    targetType: 'text-input',
    lang: 'es',
    placeholder: placeholder,
    limit: 5,
    keepOpen: false
  });

  // Añadir geocoder.
  map.addControl(geocoder);
  // Evento, despues de seleccionar una dirección
  geocoder.on('addresschosen', function (evt) {
    // jQuery('#edit-field-city-0-value').val(evt.address.details.name);
    //control_nominatim_save_segmented_return(evt.address.details);    
    markerOrigin.setPosition(evt.coordinate);
    control_nominatim_asign_coords(evt);
  });

  // Setear la latitud y longitud del evento actual.
  function control_nominatim_asign_coords(evt) {
    var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
    var long = lonlat[0];
    var lat = lonlat[1];
    jQuery('input[name="field_geolocation[0][lat]"]').val(lat);
    jQuery('input[name="field_geolocation[0][lng]"]').val(long);
    
    
  }  
  
  function control_nominatim_save_segmented_return(detail) {	  	
	  jQuery('#edit-field-direccio-complerta-0-value').val(jQuery('#gcd-input-query').val());	
	  jQuery('#edit-field-carrer-0-value').val(detail.name);
	  jQuery('#edit-field-codi-postal-0-value').val(detail.postcode);
	  jQuery('#edit-field-city-0-value').val(detail.city);
	  jQuery('#edit-field-zona-0-value').val(detail.state);
	  jQuery('#edit-field-pais-0-value').val(detail.country);	  
  }
})(window, document);
