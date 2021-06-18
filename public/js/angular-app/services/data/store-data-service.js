(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('storeDataService', storeDataService);

	  storeDataService.$inject = ['ajaxService', '$q'];

	  function storeDataService(ajaxService, $q,languageService){
	  	return {
	  		get : get,
	  		get_by_id : get_by_id,
	  		edit : edit,
	  		get_all_industry : get_all_industry,
	  		get_all_languages : get_all_languages,
	  		dummy_request : dummy_request,
	  	}

	  	function get(){
	  		var defered = $q.defer();
	  		ajaxService.get('general/get')
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

	  	function get_by_id(store_id){
	  		var defered = $q.defer();
	  		ajaxService.get('general/get_by_id/'+store_id)
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

	  	function edit(store){
	  		var defered = $q.defer();
	  		ajaxService.post('general/confirm_edit',store)
	  			.then(function(response){
	  				if(!response.IsValid){
	  					$('#LoaderDiv').hide();
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

	  	function get_all_industry(){
	  		var defered = $q.defer();
	  		ajaxService.get('/get_all_industry', null)
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
	  	function get_all_languages(){
	  		var defered = $q.defer();
	  		ajaxService.get('/get_all_languages', null)
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

	  	function dummy_request(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dummy_request', null)
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