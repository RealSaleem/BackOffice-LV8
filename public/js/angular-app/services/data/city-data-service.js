(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('cityDataService', cityDataService);

	  cityDataService.$inject = ['ajaxService', '$q'];

	  function cityDataService(ajaxService, $q){
	  	return {
	  		get_all_city : get_all_city,
	  		get_selected_cities : get_selected_cities,
	  	}

	  	function get_all_city(){
	  		var defered = $q.defer();
	  		ajaxService.get('/get_all_city', null)
	  			.then(function(response){
	  				if(!response.IsValid){
	  					defered.reject();
	  					return;
	  				};
	  				defered.resolve(response.Payload);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  			
	  		return defered.promise;
	  	}
	  	function get_selected_cities(country){
	  		var defered = $q.defer();
	  		ajaxService.get('/get_selected_city', {country:country})
	  			.then(function(response){
	  				if(!response.IsValid){
	  					defered.reject();
	  					return;
	  				};
	  				defered.resolve(response.Payload);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  			
	  		return defered.promise;
	  	}
	
	  }
})()