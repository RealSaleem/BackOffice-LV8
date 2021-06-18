(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('supplierDataService', supplierDataService);

	  supplierDataService.$inject = ['ajaxService', '$q'];

	  function supplierDataService(ajaxService, $q){
	  	return {
	  		all : all,
	  		get : get,
	  		add: add,
	  		delete_supplier: delete_supplier,
	  		edit : edit,
	  		list : list
	  	}

	  	function all(){
	  		var defered = $q.defer();
	  		ajaxService.get('supplier/all', null)
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
	  		ajaxService.post('supplier/confirm_edit',category)
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
	  	
	  	function delete_supplier(supplier){
	  		var defered = $q.defer();
	  		ajaxService.post('supplier/delete',supplier)
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

	  	function add(supplier){
	  		//console.log(supplier);
	  		var defered = $q.defer();
	  		ajaxService.post('supplier/confirm_add', supplier)
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

	  	function get(id){
	  		var defered = $q.defer();
	  		ajaxService.get('supplier/get', { id : id })
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

	  	function list(filters){
	  		console.log(filters);
			var defered = $q.defer();
			ajaxService.get('supplier/list',  filters)
				.then(function(response){
					console.log(response);
					if(!response.IsValid){
						defered.reject();
						return;
					};
					defered.resolve(response.Payload);
				})
				
			return defered.promise;
		}

	  	// function edit(supplier){
	  		
	  	// }
	  }
})()