(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('ledgerHeadDataService', ledgerHeadDataService);

	  ledgerHeadDataService.$inject = ['ajaxService', '$q'];

	  function ledgerHeadDataService(ajaxService, $q){
	  	return {
	  		all : all,
	  		add: add,
	  		edit: edit,
	  		delete_brand: delete_brand,
	  		get_record:get_record,
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

	  	function get_record (record){
	  		var defered = $q.defer();
	  		ajaxService.get('incomehead/get_record',record)
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