(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('retailDashboardDataService', retailDashboardDataService);

	  retailDashboardDataService.$inject = ['ajaxService', '$q'];

	  function retailDashboardDataService(ajaxService, $q){
	  	return {
	  		get_top_sale_people : get_top_sale_people,
	  		get_sold_product	: get_sold_product,
	  		get_num_product     : get_num_product,
	  	}

	  	function get_top_sale_people(){
	  		var defered = $q.defer();
	  		ajaxService.get('retail_dashboard/top_sale_people', null)
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

	  	function get_sold_product(){
	  		var defered = $q.defer();
	  		ajaxService.get('retail_dashboard/product_sold', null)
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

	  	function get_num_product(numOfProducts){
	  		var defered = $q.defer();
	  		ajaxService.get('retail_dashboard/num_product_sold', {num : numOfProducts})
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