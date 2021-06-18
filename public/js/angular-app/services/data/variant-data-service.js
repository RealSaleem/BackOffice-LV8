(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('variantDataService', variantDataService);

	  variantDataService.$inject = ['ajaxService', '$q'];

	  function variantDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  		get : get,
	  		get_variant_edit : get_variant_edit,
	  		update : update,
	  	}

	  	function get_all(){
	  		var defered = $q.defer();
	  		ajaxService.get('products/sku', null)
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
	  		ajaxService.get('get_variant_by_id', {id : id})
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

	  	function get_variant_edit(id){
	  		var defered = $q.defer();
	  		ajaxService.get('catalogue/get_edit_data', {id : id})
	  			.then(function(response){
	  				if(!response.IsValid){
	  					defered.reject();
	  					return;
	  				};

	  				defered.resolve(response.data);
	  			})
	  			.catch(function(){
	  				defered.reject();
	  			});
	  			
	  		return defered.promise;
	  	}
	  	function update(product){
	  		var defered = $q.defer();
	  		let id = new URL(window.location.href).pathname.split('/').pop();
	  		ajaxService.post('catalogue/variant/update/'+id, product)
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