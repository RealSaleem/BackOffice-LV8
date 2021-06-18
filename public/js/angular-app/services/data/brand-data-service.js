(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('brandDataService', brandDataService);

	  brandDataService.$inject = ['ajaxService', '$q'];

	  function brandDataService(ajaxService, $q){
	  	return {
	  		all : all,
	  		add: add,
	  		add_new : add_new,
	  		edit: edit,
	  		delete_brand: delete_brand,
	  	}

	  	function all(){
	  		var defered = $q.defer();
	  		ajaxService.get('brand/all', null)
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

	  	function add_new(brand){
	  		var defered = $q.defer();
	  		ajaxService.post('catalogue/brand',brand)
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

	  	function add(brand){
	  		var defered = $q.defer();
	  		ajaxService.post('brand/confirm_add',brand)
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

	  	function edit (brand){
	  		var defered = $q.defer();
	  		ajaxService.post('brand/edit',brand)
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

	  	function delete_brand(brand){
	  		var defered = $q.defer();
	  		ajaxService.post('brand/delete',brand)
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