(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('paymentTypeDataService', paymentTypeDataService);

	  paymentTypeDataService.$inject = ['ajaxService', '$q'];

	  function paymentTypeDataService(ajaxService, $q){
	  	return {
	  		add : add,
	  		get_all : get_all,
	  		edit : edit,
	  		get : get,
	  		delete_paymentType : delete_paymentType,
	  	}

	  	function get_all(){
	  		var defered = $q.defer();
	  		ajaxService.get('paymentType/get_all', null)
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

	  	function add(paymentType){
	  		var defered = $q.defer();
	  		ajaxService.post('paymentType/confirm_add', paymentType)
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

	  	function edit(paymentType){
	  		var defered = $q.defer();
	  		ajaxService.post('paymentType/confirm_edit', paymentType)
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

	  	function get(id){
	  		var defered = $q.defer();
	  		ajaxService.get('paymentType/get', { id : id })
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

	  	function delete_paymentType(paymentType){
	  		var defered = $q.defer();
	  		ajaxService.post('paymentType/delete', paymentType)
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