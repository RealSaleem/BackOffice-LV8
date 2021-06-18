(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('AccountsService', AccountsService);

	  AccountsService.$inject = ['ajaxService', '$q'];

	  function AccountsService(ajaxService, $q){
	  	return {
	  		all : all,
	  		add: add,
	  		edit: edit,
	  		delete_brand: delete_brand,
	  		filter_record:filter_record,
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

	  	function add(brand){
	  		var defered = $q.defer();
	  		ajaxService.post('expenseledger/add_expense',brand)
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

	  	function filter_record (record){
	  		var defered = $q.defer();
	  		ajaxService.get('accounts/filter_record',record)
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