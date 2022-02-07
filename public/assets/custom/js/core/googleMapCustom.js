var googleMapCustom = {

	geocoder: '',
	map: '',
	markers: [],
	inputLongitud: '',
	inputLatitud: '',
	inputDireccion: '',
	longitud: '-77.00403850525618',
	latitud: '-12.10094258730438',

	load: function (params = {}) {

		googleMapCustom.inputLongitud = params.inputLongitud;
		googleMapCustom.inputLatitud = params.inputLatitud;
		googleMapCustom.inputDireccion = params.inputDireccion;
		googleMapCustom.initialize(params);
	},

	setMapOnAll: function (map) {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	},

	clearMarkers: function () {
		googleMapCustom.setMapOnAll(null);
	},

	openInfoWindow: function (marker) {
		var markerLatLng = marker.getPosition();

		googleMapCustom.latitud = markerLatLng.lat();
		googleMapCustom.longitud = markerLatLng.lng();
		googleMapCustom.inputLatitud.val(markerLatLng.lat());
		googleMapCustom.inputLongitud.val(markerLatLng.lng());
		//alert(results[0].formatted_address);
	},

	initialize: function (params = {}) {

		var longitud = (typeof params.longitud !== "undefined") ? params.longitud : googleMapCustom.longitud;
		var latitud = (typeof params.latitud !== "undefined") ? params.latitud : googleMapCustom.latitud;

		googleMapCustom.geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(latitud, longitud);
		var mapOptions = {
			zoom: 20,
			center: latlng
		}
		googleMapCustom.map = new google.maps.Map(document.getElementById(params.idDivMap), mapOptions);

		// var input = document.getElementById('pac-input');
		// var searchBox = new google.maps.places.SearchBox(input);
		// map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

		google.maps.event.addListenerOnce(googleMapCustom.map, 'idle', function () {
			var currentCenter = googleMapCustom.map.getCenter();
			google.maps.event.trigger(googleMapCustom.map, 'resize');
			googleMapCustom.map.setCenter(currentCenter);
			googleMapCustom.map.setZoom(15);
			var marker = new google.maps.Marker({
				map: googleMapCustom.map,
				draggable: true,
				position: currentCenter
			});
			googleMapCustom.markers.push(marker);
			//var address = marker.formatted_address;	

			google.maps.event.addListener(marker, 'dragend', function () {
				googleMapCustom.openInfoWindow(marker);
				googleMapCustom.codeLatLng();
			});
			google.maps.event.addListener(marker, 'click', function () {
				googleMapCustom.openInfoWindow(marker);
				googleMapCustom.codeLatLng();
			});
		});
	},

	codeLatLng: function () {
		var latitud = googleMapCustom.inputLatitud.val();
		var longitud = googleMapCustom.inputLongitud.val();

		var lat = parseFloat(latitud);
		var lng = parseFloat(longitud);

		var latlng = new google.maps.LatLng(lat, lng);

		googleMapCustom.geocoder.geocode({
			'latLng': latlng
		}, function (results, status) {
			if (status == 'OK') {
				if (results[0]) {

					googleMapCustom.inputDireccion.val(results[0].formatted_address);

				} else {
					// alert('No results found');
				}
			} else {
				// alert('Geocoder failed due to: ' + status);
			}
		});
	},

	codeAddress: function () {
		var dep = $('#departamento option:selected').text();
		var pro = $('#provincia option:selected').text();
		var dis = $('#distrito option:selected').text();
		var address = document.getElementById('direccion').value;
		var dir = dep + '-' + pro + '-' + dis + '-' + address;
		googleMapCustom.geocoder.geocode({
			'address': dir
		}, function (results, status) {
			if (status == 'OK') {
				googleMapCustom.clearMarkers();
				googleMapCustom.map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: googleMapCustom.map,
					draggable: true,
					position: results[0].geometry.location
				});
				markers.push(marker);
				googleMapCustom.inputLatitud.val(results[0].geometry.location.lat());
				googleMapCustom.inputLongitud.val(results[0].geometry.location.lng());
				//var address = results[0].formatted_address;		
				google.maps.event.addListener(marker, 'dragend', function () {
					googleMapCustom.openInfoWindow(marker);
					googleMapCustom.codeLatLng();
				});
				google.maps.event.addListener(marker, 'click', function () {
					googleMapCustom.openInfoWindow(marker);
					googleMapCustom.codeLatLng();
				});


			} else {
				//alert('Geocode was not successful for the following reason: ' + status);
			}
		});
	}
}