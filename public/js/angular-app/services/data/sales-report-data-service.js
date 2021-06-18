(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('salesReportDataService', salesReportDataService);

	  salesReportDataService.$inject = ['ajaxService', '$q'];

	  function salesReportDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  		filterByReport : filterByReport,
	  	}

	  	function get_all(data){
	  		var defered = $q.defer();
	  		ajaxService.get('salesReport/get_all', data)
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

	  	function filterByReport(data){
	  		console.log(data);
	  		var defered = $q.defer();
	  		ajaxService.get('salesReport/report_filter', data)
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