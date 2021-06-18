(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('customerGroupDataService', customerGroupDataService);

	  customerGroupDataService.$inject = ['ajaxService', '$q'];

	  function customerGroupDataService(ajaxService, $q){
	  	return {
	  		all: all,
	  		add_customer_group : add_customer_group,
	  		delete_customer_group : delete_customer_group,
	  		edit_customer_group : edit_customer_group,		
	  	}

	  	function all(){
	  		var defered = $q.defer();
	  		ajaxService.get('customer_group/get_all', null)
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

	  	function add_customer_group(newcustomer_group){
	  		var defered = $q.defer();
	  		ajaxService.post('customer_group/add', newcustomer_group)
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

	  	function delete_customer_group(customer_group){
	  		var defered = $q.defer();
	  		ajaxService.post('customer_group/delete', customer_group)
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

	  	function edit_customer_group(customer_group){
	  		var defered = $q.defer();
	  		ajaxService.post('customer_group/edit', customer_group)
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