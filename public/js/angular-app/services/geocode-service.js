(function() {
	'use strict';

	angular
		.module('restaurant.common')
		.factory('geocode', geocode);

	geocode.$inject = ['$http','$q', 'ENV', '$ionicPopup'];

	/* @ngInject */
	function geocode($http, $q, ENV, $ionicPopup) {
		return {
			get : getSuggestions,
			getCurrentPosition  : getCurrentPosition,
			generateMapLink : generateMapLink
		};
		
		function generateMapLink(latLng){
			if(ionic.Platform.isIOS())
				return 'maps://?q=' + latLng;
			if(ionic.Platform.isAndroid())
				return 'geo:0,0?q=' + latLng;
			return 'https://www.google.com/maps/@'+latLng+',14.5z';
		}

		function getCurrentPosition(func){
			navigator.geolocation.getCurrentPosition(function(pos){
				func({
					latitude  : pos.coords.latitude, 
					longitude : pos.coords.longitude
				});
			}, function(){ 
				func({ latitude: '21.451892',longitude: '39.260199' });
				$ionicPopup.alert({
			     	title: 'Can\'t find current location',
			     	template: 'Make sure your device location service is enabled.'
		   		});
			}, {timeout:5000})
		}

		function getSuggestions(address){
			var deferred = $q.defer();
			var request = {
				key : ENV.googleApiConfig.mapApiKey,
				address : address
			}
			var url = 'https://maps.googleapis.com/maps/api/geocode/json';
			var promise = $http.get(url, { params: request });
			promise.then(function successCallback(response) {
					console.log('Geocode : fetched response')
					deferred.resolve(response.data);
				    // this callback will be called asynchronously
				    // when the response is available
				  }, function errorCallback(response) {
				  	console.log('Geocode : Faild to fetched response')
				  	deferred.reject(response);
				    // called asynchronously if an error occurs
				    // or server returns response with an error status.
				  });

			return deferred.promise;
		}
	}
})();