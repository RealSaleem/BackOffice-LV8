/*
* This service will manage order data
*/

(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('orderDataService', orderDataService);

	  orderDataService.$inject = ['dbService', 'ajaxService', '$q', '$filter', 'languageService'];

	  function orderDataService(dbService, ajaxService, $q, $filter, languageService) {

	      var parkedOrders = [];

	  	return {
	  		add 	: add,
	  		sync: sync,
	  		addParkedOrders: addParkedOrders,
	  		getParkedOrders: getParkedOrders,
	  		removeParkedCart: removeParkedCart,
	  		get_filters: get_filters,
	  		get_orders: get_orders,
	  		get_order: get_order,
	  		get_last_order : get_last_order,
			resume_park_orders:resume_park_orders,
			sync_park_orders: sync_park_orders,
			getDineinOrders: getDineinOrders,
	  	};

	  	/*
	  	* generate and return random alpha numeric string
	  	* for order identity
	  	*/
	  	function _generateIdentity(){
			var text = "";
			var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
			for( var i=0; i < 6; i++ )
				text += possible.charAt(Math.floor(Math.random() * possible.length));
			return text;
	  	}

	  	/*
	  	* Create and return order db object from cart object
	  	*/
	  	function _createOrderObj(order, totalPayment, balance, status, payment_method, ref_no,register_id, register_history_id, return_order_id,user_id,store_id) {
	
	  		return dbService.orderTable.createRow({
	  			identity : order.identity,
	  			customer_id : order.customer_id,
	  			status : status,
	  			sub_total : order.sub_total,
	  			balance : balance,
	  			payment : totalPayment,
	  			discount : order.discounted_amount,
	  			total : order.total,
	  			notes : order.notes,
	  			order_date : order.date,
	  			order_time : order.time,
	  			created_at : new Date(),
	  			synced : false,
	  			synced_at: new Date(),
	  			register_id: register_id,
	  			user_id: user_id,
	  			store_id: store_id,
	  			register_history_id: register_history_id,
	  			return_order_id: return_order_id,
	  			payment_method: payment_method,
	  			payment_ref: ref_no,
	  			applyReward: order.applyReward,
	  			reward_value: order.reward_value,
	  		});
	  	}

	  	/*
	  	* Create and return array of order_item db object from cart.items object
	  	*/
	  	function _createOrderItemObjs(items, order_id) {
	  		// console.log(parseFloat(items.variant.supplier_pirce));

	  		return items.map(function(i){
	  			var total_percent = (parseFloat(i.custom_price) + parseFloat(i.discounted_amount))*(parseFloat(i.discount_percentage)/100);
	  			
	  			return dbService.orderItemsTable.createRow({
	  				order_id : order_id,
	  				variant_id : i.variant.id,
	  				quantity : i.quantity,
	  				notes: i.notes,
	  				supplier_price : parseInt(i.variant.supplier_price),
	  				subtotal: parseFloat(i.custom_price) + parseFloat(i.discounted_amount),
	  				discount: parseFloat(i.discount_percentage),
	  				total: parseFloat(i.custom_price) + parseFloat(i.discounted_amount) - parseFloat(total_percent),
	  				description: i.description,
	  			})
	  		})
	  	}

	  	/* 
	  	* add order and order details rows in
	  	* local db, and call the sync service to sync 
	  	* changes with server
	  	*/
	  	function add(order, totalPayment, balance, status,payment_method, ref_no,register_id, register_history_id, return_order_id,user_id,store_id) {
	  		var defered = $q.defer();
	  		if (ref_no == undefined) {
	  			ref_no = null;
	  		}
	  		if (balance == undefined) {
	  			balance = null;
	  		}

	  // 		if (ref_no != null) {
			// 	$('#modalCreditCard').modal('hide')
			// }
	  		var order_row = _createOrderObj(order, totalPayment, balance, status,payment_method, ref_no, register_id,register_history_id,return_order_id,user_id,store_id);
	  		var transaction = dbService.db_.createTransaction();
	  		console.log(order_row);
			$('#LoaderDiv').show();
	  		var insertQuery = transaction.begin([dbService.orderTable, dbService.orderItemsTable])
	  				.then(function(){
				  		var insert_order =  dbService.db_.insert().into(dbService.orderTable).values([order_row]);
						return transaction.attach(insert_order);
	  				}).catch(function(){
	  					//error while begining transaction
	  				});

	  		var itemsQuery = insertQuery.then(function(order_db){
	  		// console.log('order_db');
	  		// console.log(order_db);

	  			//now insert detail rows here
	  		    var order_items = _createOrderItemObjs(order.items, order_db[0].id);
	  		    // console.log('order_items');
	  		    // console.log(order_items);

		  		var q = dbService.db_
		  						.insert()
								.into(dbService.orderItemsTable)
								.values(order_items);
				return transaction.attach(q);
	  		}).catch(function(r){
	  			//error while inserting order
	  			defered.resolve(false);
	  		});
			// console.log('itemsQuery');
			// console.log(itemsQuery);

			var commit = itemsQuery.then(function(){
				return transaction.commit();
			}).catch(function(r){
				//error while inserting items
				defered.resolve(false);
			});

			commit.then(function(){
				sync();
				defered.resolve(true);
				
			}).catch(function(){
				//error on committing changes
				defered.resolve(false);
			});

			return defered.promise;
	  	}

	  	/* 
	  	* read all unsynced orders from local db
	  	* and post them to server.
	  	*/
	  	function sync(){
		// console.log('wwwwwwww');

	  		dbService.connect().then(function(){
	  			var orderQuery = dbService.db_.select()
			  					.from(dbService.orderTable)
			  					.where(dbService.orderTable.synced.eq(false))
			  					.exec();

		  		var orders = [];
		  		var itemsQuery = orderQuery.then(function(_orders){
		  			orders = _orders;
		  			var orderIds = orders.map(function(r){
		  				return r.id;
		  			});

		  			return dbService.db_
		  					.select()
		  					.from(dbService.orderItemsTable)
		  					.where(dbService.orderItemsTable.order_id.in(orderIds))
		  					.exec();
		  		});


	  			itemsQuery.then(function(order_items){
			  		//nothing to post
			  		if(orders.length == 0){
			  			toastr.Warning(order_items.Message,'Warning');
			  			return;
			  		};

			  		angular.forEach(orders, function(o){
			  			o.items = [];
			  			angular.forEach(order_items, function(i){
			  				if(i.order_id == o.id)
			  					o.items.push(i);
			  			});
			  		});

	  				var data = {
	  					Orders : orders,
	  				};
	  				console.log(data);

	  				setTimeout(() => {
	  					$('#LoaderDiv').hide();
					}, 2000);
	  				ajaxService.post('order/sync', data, true)
	  					.then(function (response) {
	  					    if (response.IsValid) {
	  					        /*toastr.success('Order have been synced successfully','Orders Synced');
	  					        let lang = languageService.get('Order Synced');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
	  							updateSyncTime(orders);
	  						}
	  					});
	  			});
	  		});
	  	}

	  	/*
	  	* Update SyncTime and set Synced to true for all provided 
	  	* order rows
	  	*/
	  	function updateSyncTime(orders){
	  		var ids = orders.map(function(o){
	  			return o.id;
	  		});
	  		dbService.db_.update(dbService.orderTable)
	  					.set(dbService.orderTable.synced, true)
	  					.set(dbService.orderTable.synced_at, new Date())
	  					.where(dbService.orderTable.id.in(ids))
	  					.exec()
	  					.then(function(){
	  						
	  					});
	  	}

	  	function addParkedOrders(cart,outlet_id){			 
	  		let parkedOrders = JSON.parse(localStorage.getItem("parkedOrders"));

		    if (parkedOrders == null || parkedOrders == 'null') {
	  	        cart.park_identity = _generateIdentity();
				cart.Time = new Date().toLocaleString();
				cart.status = 'Parked';
				cart.is_dinein	= 0;
	  	        localStorage.setItem('parkedOrders', JSON.stringify([cart]));
	  	        sync_park_orders([cart],outlet_id);
	  	    } else {
	  	    	let orderIndex = parkedOrders.findIndex(o => o.park_identity == cart.park_identity);
	  	    	if(orderIndex < 0){
	  	    		cart.park_identity = _generateIdentity();
					cart.Time = new Date().toLocaleString();
					cart.status = 'Parked';
					cart.is_dinein	= 0;
	  	    		parkedOrders.push(cart);
	  	    		
	  	    	}else{
	  	    		parkedOrders.splice(orderIndex,1,cart);
	  	    	}
	  	        localStorage.setItem('parkedOrders', JSON.stringify(parkedOrders));
	  	        sync_park_orders(parkedOrders,outlet_id);
	  	    }	
  	        
	  	}

	  	function sync_park_orders(cart,outlet_id){
	  		if(outlet_id === undefined){
	  			var outlet_id = localStorage.getItem("outletId");
	  		}	  		
	  		ajaxService.post('order/add_parked_orders', {cart:cart,outlet_id:outlet_id}, true)
				.then(function (response) {
				    if (response.IsValid) {
				        toastr.success(response.Message,'Success');
					}
			});
	  	}

	  	function resume_park_orders(cart){
	  		console.log(cart);
	  		ajaxService.post('order/parked_orders_status', {cart:cart}, true)
				.then(function (response) {
				    if (response.IsValid) {
				        toastr.success(response.Message,'Success');
					}
			});
	  	}

	  	function getParkedOrders() {
	  	    let ordersString = localStorage.getItem("parkedOrders");
	  	    let parkedOrders = JSON.parse(ordersString);
	  	    return parkedOrders;
	  	}

	  	function removeParkedCart(cart)
	  	{
	  	    let ordersString = localStorage.getItem("parkedOrders");
	  	    let parkedOrders = JSON.parse(ordersString);

	  	    //let index = parkedOrders.indexOf(cart);

	  	    if (parkedOrders != null && parkedOrders.length > 0) {
	  	        let filteredOrders = parkedOrders.filter((item) => item.Time != cart.Time);
	  	        localStorage.setItem('parkedOrders', JSON.stringify(filteredOrders));
	  	    }
	  	}

	  	function get_filters() {
	  	    var defered = $q.defer();
	  	    ajaxService.get('get_filters', null)
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

	  	function get_orders(params) {
	  	    var defered = $q.defer();
	  	    ajaxService.get('get_orders', params)
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

	  	function get_last_order() {
	  	    var defered = $q.defer();
	  	    ajaxService.get('get_last_order', null)
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

	  	function get_order(params) {
	  	    var defered = $q.defer();
	  	    ajaxService.get('get_order', params)
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

	  	function getDineinOrders(){
	  		var defered = $q.defer();
	  	    ajaxService.get('order/get_dinein_orders')
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