(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('registerDataService', registerDataService);

	  registerDataService.$inject = ['ajaxService', '$q'];

	  function registerDataService(ajaxService, $q){
	  	return {
	  		edit : edit,
	  		get : get,
	  		get_all : get_all,
	  		add : add,
	  		delete_register : delete_register,
	  	}

	  	
	  	function get(id){
	  		var defered = $q.defer();
	  		ajaxService.get('register/get', {id : id})
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

	  	function add(register){
	  		var defered = $q.defer();
	  		ajaxService.post('register/confirm_add', register)
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

	  	function delete_register(register){
	  		var defered = $q.defer();
	  		ajaxService.post('register/delete', register)
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

	  	function edit(register){
	  		var defered = $q.defer();
	  		ajaxService.post('register/confirm_edit',register)
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

	  	function get_all(){
	  		var defered = $q.defer();
	  		ajaxService.get('register/get_all', null)
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