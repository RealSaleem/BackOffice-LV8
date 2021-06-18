(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('dashboardDataService', dashboardDataService);

	  dashboardDataService.$inject = ['ajaxService', '$q'];

	  function dashboardDataService(ajaxService, $q){
	  	return {
	  		get_all : get_all,
	  		get_graph_days : get_graph_days,
	  		get_all_product : get_all_product,
	  		get_product_graph_days : get_product_graph_days,

	  		get_sell_by_year : get_sell_by_year,
	  		get_sell_by_month : get_sell_by_month,
	  		get_sell_by_week : get_sell_by_week,
	  		get_sell_by_hour : get_sell_by_hour,

	  		get_product_by_year : get_product_by_year,
	  		get_product_by_month : get_product_by_month,
	  		get_product_by_week : get_product_by_week,
	  		get_product_by_hour : get_product_by_hour,

	  		get_sell_by_year_selected : get_sell_by_year_selected,
	  		get_sell_by_month_selected : get_sell_by_month_selected,
	  		get_sell_by_week_selected : get_sell_by_week_selected,
	  		get_sell_by_hour_selected : get_sell_by_hour_selected,

	  		get_product_by_year_selected : get_product_by_year_selected,
	  		get_product_by_month_selected : get_product_by_month_selected,
	  		get_product_by_week_selected : get_product_by_week_selected,
	  		get_product_by_hour_selected : get_product_by_hour_selected,

	  		get_sell_by_payment_method : get_sell_by_payment_method,
	  		payment_method_selected_month : payment_method_selected_month,
	  	}

	  	function get_all(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sales', null)
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

	  	function get_all_product(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/products', null)
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

	  	function get_graph_days(day){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sales_days', {day : day})
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

	  	function get_product_graph_days(day){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/product_days', {day : day})
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

	  	function get_sell_by_year(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sell_by_year', null)
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

	  	function get_product_by_year(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/product_by_year', null)
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

	  	function get_sell_by_month(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sell_by_month', null)
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

	  	function get_product_by_month(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/product_by_month', null)
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

	  	function get_sell_by_week(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sell_by_week', null)
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

	  	function get_product_by_week(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/product_by_week', null)
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

	  	function get_sell_by_hour(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sell_by_hour', null)
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

	  	function get_product_by_hour(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/product_by_hour', null)
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

	  	function get_sell_by_payment_method(){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sell_by_payment_method', null)
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

	  	function get_sell_by_year_selected(year){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sell_by_year_selected', {year : year})
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

	  	function get_product_by_year_selected(year){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/product_by_year_selected', {year : year})
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

	  	function get_sell_by_month_selected(months){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sell_by_month_selected', {months : months})
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

	  	function get_product_by_month_selected(months){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/product_by_month_selected', {months : months})
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

	  	function get_sell_by_week_selected(week){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/sell_by_week_selected', {week : week})
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

	  	function get_product_by_week_selected(week){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/product_by_week_selected', {week : week})
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

	  	function get_sell_by_hour_selected(hour){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/get_sell_by_hour_selected', {hour : hour})
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

	  	function get_product_by_hour_selected(hour){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/product_by_hour_selected', {hour : hour})
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

	  	function payment_method_selected_month(months){
	  		var defered = $q.defer();
	  		ajaxService.get('/dashboard/payment_method_month_selected', {months : months})
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