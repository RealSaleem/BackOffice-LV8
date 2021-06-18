(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('categoryDataService', categoryDataService);

	  categoryDataService.$inject = ['ajaxService', '$q'];

	  function categoryDataService(ajaxService, $q){
	  	return {
	  		add_customer : add_customer,
	  		all : all,  
	  		add: add,	
	  		addNew: addNew,			
	  		delete_category: delete_category,
	  		edit: edit,
	  	}

	  	function add_customer(newCustomer){
	  		var defered = $q.defer();
	  		ajaxService.post('sell/add_customer', newCustomer)
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

	  	function add(category){
	  		var defered = $q.defer();
	  		ajaxService.post('category/confirm_add', category)
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
	  	function addNew(category){
	  		var defered = $q.defer();
	  		ajaxService.post('catalogue/category', category)
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

	  	function edit (category){
	  		var defered = $q.defer();
	  		ajaxService.post('category/edit',category)
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

	  	function delete_category(category){
	  		var defered = $q.defer();
	  		ajaxService.post('category/delete',category)
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

	  	function all(){
	  		var defered = $q.defer();
	  		ajaxService.get('category/all', null)
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