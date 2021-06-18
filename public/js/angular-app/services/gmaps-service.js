(function() {
	'use strict';

	angular
		.module('restaurant.common')
		.factory('gmaps', gmaps);

	gmaps.$inject = [];

	/* @ngInject */
	function gmaps() {
		return {
			openInfoWindow : openInfoWindow
		};

		var infowindow;
		function openInfoWindow(contentString, loc, map){
			if(map == null) return;

			map.setCenter(loc);
			map.setZoom(14);

			//close already opened window
			if(infowindow)
				infowindow.close();

			infowindow = new google.maps.InfoWindow({
				disableAutoPan: true
			});

			infowindow.setContent(contentString);
			infowindow.setPosition(loc);
			infowindow.open(map);
			google.maps.event.addListener(infowindow, 'closeclick', function() {
				infowindow.close();
			});
		}
	}
})();