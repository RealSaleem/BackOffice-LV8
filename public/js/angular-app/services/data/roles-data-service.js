(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('rolesDataService', rolesDataService);

	  rolesDataService.$inject = ['ajaxService', '$q'];

	  function rolesDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  	}

	  	function get_all(){
	  		var defered = $q.defer();
	  		ajaxService.get('roles/get_all', null)
	  			.then(function(response){
	  				console.log(response);
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