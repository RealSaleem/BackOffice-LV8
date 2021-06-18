(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('paymentReportDataService', paymentReportDataService);

	  paymentReportDataService.$inject = ['ajaxService', '$q'];

	  function paymentReportDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  		filterDate : filterDate,
	  	}

	  	function get_all(){
	  		var defered = $q.defer();
	  		ajaxService.get('paymentReport/get_all', null)
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

	  	function filterDate(data){
	  		var defered = $q.defer();
	  		ajaxService.get('paymentReport/filter', {start_date : data.start_date, end_date : data.end_date})
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