(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('outletDataService', outletDataService);

	  outletDataService.$inject = ['ajaxService', '$q'];

	  function outletDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  		add : add,
	  		get_with_registers : get_with_registers,
	  		get : get,
	  		edit : edit,
	  		delete_outlet : delete_outlet,
	  		get_user_outlets : get_user_outlets
	  	}

	  	function get_user_outlets(){
	  		var defered = $q.defer();
	  		ajaxService.get('outlet/get_user_outlets', null)
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
	  		ajaxService.get('outlet/get_all', null)
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
	  		ajaxService.get('outlet/get', {id : id})
	  			.then(function(response){
	  				if(!response.IsValid){
	  					defered.reject();
	  					window.location = site_url('outlet');
	  					return;
	  				};
	  				defered.resolve(response.Payload);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  			
	  		return defered.promise;
	  	}

	  	function add(outlet){
	  		var defered = $q.defer();
	  		ajaxService.post('outlet/confirm_add',outlet)
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

	  	function get_with_registers(id){
	  		var defered = $q.defer();

	  		ajaxService.get('outlet/get_with_registers', { id : id})
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

	  	function edit(outlet){
	  		var defered = $q.defer();
	  		ajaxService.post('outlet/confirm_edit',outlet)
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

	  	function delete_outlet(outlet){
	  		var defered = $q.defer();
	  		ajaxService.post('outlet/delete', outlet)
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