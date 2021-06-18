(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('registerClosureDataService', registerClosureDataService);

	  registerClosureDataService.$inject = ['ajaxService', '$q'];

	  function registerClosureDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  		get : get,
	  		get_transaction : get_transaction,
	  		get_ledger : get_ledger,
	  		get_ledger_by_invoice : get_ledger_by_invoice,
	  		list : list,
	  		get_filters : get_filters,
	  		getReceiptData : getReceiptData,
	  	}


	  	function get_all(params){
	  		var defered = $q.defer();
	  		ajaxService.get('register_closure/get_all', params)
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
			ajaxService.get('register_closure/list',  filters)
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

	  	function get(ids){
	  		var defered = $q.defer();
	  		ajaxService.get('register_closure/get_summary', {id : ids.id,history_id:ids.history_id})
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

	  	function get_filters(params){
	  		console.log(params);
	  		var defered = $q.defer();
	  		ajaxService.get('register_closure/get_filters', {id : params.id,history_id:params.history_id,
	  			customer_id:params.customer_id,user_id:params.user_id,identity:params.identity,
	  			sub_total:params.sub_total})
	  			.then(function(response){
					if(!response.IsValid){
						defered.reject();
						return;
					};
					defered.resolve(response.Payload);
				})	
			return defered.promise;
	  	}

	  	function get_transaction(ids){
	  		var defered = $q.defer();
	  		ajaxService.get('register_closure/get_transaction', {id : ids.id,history_id:ids.history_id})
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

	  	function get_ledger(ids){
	  		var defered = $q.defer();
	  		ajaxService.get('register_closure/get_ledger',{id: ids.id,history_id:ids.history_id,order_id:ids.order_id})
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

	  	function get_ledger_by_invoice(invoice_id){
	  		var defered = $q.defer();
	  		ajaxService.get('register_closure/get_ledger',{invoice_id: invoice_id})
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

	  	function getReceiptData(reg_id,outlet_id){
	  		var defered = $q.defer();
	  		ajaxService.get('register_closure/getDataForReceipt',{register_id: reg_id, outlet_id: outlet_id})
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