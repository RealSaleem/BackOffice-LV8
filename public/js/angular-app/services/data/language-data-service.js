(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('languageDataService', languageDataService);

	  languageDataService.$inject = ['ajaxService', '$q'];

	  function languageDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  	}

	  	function get_all(){
	  		var defered = $q.defer();
	  		ajaxService.get('users/get_language_array', null)
	  			.then(function(response){
	  				// if(!response.IsValid){
	  				// 	defered.reject();
	  				// 	return;
	  				// };
	  				// defered.resolve(response.Payload);
	  				defered.resolve(response);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  			
	  		return defered.promise;
	  	}

	  }
})()