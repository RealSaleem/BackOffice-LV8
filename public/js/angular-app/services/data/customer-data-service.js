(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('customerDataService', customerDataService);

	  customerDataService.$inject = ['ajaxService', '$q'];

	  function customerDataService(ajaxService, $q){
	  	return {
	  		add : add,
	  		getData : getData,
	  		get : get,
	  		edit : edit,
	  		get_all : get_all,
	  		list : list,
	  		getDetail : getDetail,
	  		delete_customer : delete_customer,
	  	}

	  	function add(customer){
	  		var defered = $q.defer();
	  		ajaxService.post('customer/confirm_add', customer)
	  			.then(function(response){
	  				if(!response.IsValid){
	  					$('#LoaderDiv').hide();
	  					//defered.reject();
	  					return;
	  				};
	  				defered.resolve(response.Payload);
	  			})
	  			.catch(function(){
	  				//defered.reject();
	  			});
	  			
	  		return defered.promise;
	  	}

	  	function delete_customer(customer){
	  		var defered = $q.defer();
	  		ajaxService.post('customer/delete',customer)
	  		.then(function(response){
	  			if(!response.IsValid){
	  				defered.reject();
	  				return;
	  			}
	  			defered.resolve(response.Payload);
	  		});

	  		return defered.promise;
	  	}

	  	function edit(customer){
	  		var defered = $q.defer();
	  		ajaxService.post('customer/confirm_edit',customer)
	  		.then(function(response){
	  			if (!response.IsValid) {
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
			ajaxService.get('customer/list',  filters)
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

	  	function get_all(){
	  		var defered = $q.defer();
	  		ajaxService.get('customer/get_all')
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
	  		ajaxService.get('customer/get', { id : id })
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

	  	function getDetail(id){
	  		var defered = $q.defer();
	  		ajaxService.get('customer/getDetail', { id : id })
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

	  	function getData(){
	  	    var defered = $q.defer();
	  		ajaxService.get('customer/get_data', {})
	  			.then(function(response){
	  				if(!response.IsValid){
	  					defered.reject();
	  					//return;
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