(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('purchaseDataService', purchaseDataService);

	  purchaseDataService.$inject = ['ajaxService', '$q'];

	  function purchaseDataService(ajaxService, $q){

	  	return {
	  		add : add,
	  		find : find,
	  		list : list,
	  		updateStatus : updateStatus,
	  		updateWebDisplayStatus : updateWebDisplayStatus,
	  		updateVariantStatus : updateVariantStatus,
	  		get : get,
	  		edit: edit,
	  		add_stock: add_stock,
            remove_stock : remove_stock,
            getByStoreId : getByStoreId,
            get_stock : get_stock,
	  	}
	  	
	  	function edit(product){
	  		var defered = $q.defer();
	  		ajaxService.post('products/confirm_edit', product)
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
	  		ajaxService.post('products/get_details', { id : id } )
	  			.then(function(response){
	  				defered.resolve(response.Payload);
	  			});
	  		return defered.promise;
	  	}

	  	function get_stock(id){
	  		var defered = $q.defer();
	  		ajaxService.post('products/get_stock', { id : id } )
	  			.then(function(response){
	  				defered.resolve(response.Payload);
	  			});
	  		return defered.promise;
	  	}

	  	function updateStatus(product_id, status){
	  		var defered = $q.defer();
	  		ajaxService.post('products/update_status', { id : product_id, active : status } )
	  			.then(function(response){
	  				defered.resolve(response.IsValid);
	  			});
	  		return defered.promise;
	  	}

	  	function updateWebDisplayStatus(product_id, status){
	  		var defered = $q.defer();
	  		ajaxService.post('products/update_web_display_status', { id : product_id, web_display : status } )
	  			.then(function(response){
	  				defered.resolve(response.IsValid);
	  			});
	  		return defered.promise;
	  	}
	  	function updateDineInDisplayStatus(product_id, status){
	  		var defered = $q.defer();
	  		ajaxService.post('products/update_dinein_display_status', { id : product_id, dinein_display : status } )
	  			.then(function(response){
	  				defered.resolve(response.IsValid);
	  			});
	  		return defered.promise;
	  	}	  	

	  	function updateVariantStatus(variant_id, status){
	  		var defered = $q.defer();
	  		ajaxService.post('products/update_variant_status', { id : variant_id, is_active : status } )
	  			.then(function(response){
	  				defered.resolve(response.IsValid);
	  			});
	  		return defered.promise;
	  	}

		function list(filters){
			var defered = $q.defer();
			ajaxService.get('purchase/list',  filters)
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
	  	function find(q){
	  		var defered = $q.defer();
	  		ajaxService.get('products/find', {q : q})
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

	  	function add(product){
	  		var defered = $q.defer();
	  		ajaxService.post('products/add', product)
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

	  	function add_stock(stock) {
	  		console.log(stock);
	  	    var defered = $q.defer();
	  	    ajaxService.post('products/add_stock', stock)
	  			.then(function (response) {
	  			    if (!response.IsValid) {
	  			        defered.reject();
	  			        return;
	  			    };
	  			    defered.resolve(response.Payload);
	  			})
	  			.catch(function () {
	  			    defered.reject();
	  			});

	  	    return defered.promise;
	  	}

	  	function getByStoreId(){
	  		var defered = $q.defer();
	  		ajaxService.get('store_products')
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

	  	function remove_stock(stock) {
	  	    var defered = $q.defer();
	  	    ajaxService.post('products/remove_stock', stock)
	  			.then(function (response) {
	  			    if (!response.IsValid) {
	  			        defered.reject();
	  			        return;
	  			    };
	  			    defered.resolve(response.Payload);
	  			})
	  			.catch(function () {
	  			    defered.reject();
	  			});

	  	    return defered.promise;
	  	}
	  }
})()