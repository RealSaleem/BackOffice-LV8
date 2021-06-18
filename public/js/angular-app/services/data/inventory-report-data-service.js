(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('inventoryReportDataService', inventoryReportDataService);

	  inventoryReportDataService.$inject = ['ajaxService', '$q'];

	  function inventoryReportDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  		get_low_stock : get_low_stock,
	  	}

	  	function get_all(data){
	  		var defered = $q.defer();
	  		ajaxService.get('inventoryReport/get_all', data)
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

	  	function get_low_stock(data){
	  		var defered = $q.defer();
	  		ajaxService.get('inventoryReport/get_low_stock', {data : data.type})
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