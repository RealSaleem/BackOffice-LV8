(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('customerLocalDBService', customerLocalDBService);

	  customerLocalDBService.$inject = ['dbService', '$q','customerDataService'];

	  function customerLocalDBService(dbService, $q, customerDataService){
	  	return {
	  		add  : add,
	  		sync : sync,
	  		seed : seed,
	  		find : find,
	  		findById : findById
	  	}
	  	
	  	function sync(){

	  	}

	  	function add(customer){
	  		var row = dbService.customerTable.createRow(customer);
	  		return dbService.db_.insert().into(dbService.customerTable).values([row]).exec();
	  	}

	  	function seed(){
	  	    var defered = $q.defer();
			customerDataService.getData()
				.then(function(data){
					insertData(data);
					defered.resolve();
				})
				.catch(function(){
					console.log('couldnt get customer seed data')
					defered.reject();
				});
	  		return defered.promise;
	  	}

	  	function insertData(data){
	  		dbService.connect().then(function(){
	  			dbService.deleteAll([dbService.customerTable, dbService.customerGroupTable])
	  				.then(function(){
	  					var customers = data.customers.map(function(r){
	  						return dbService.customerTable.createRow(r);
	  					});

	  					var customerGroups = data.customer_groups.map(function(r){
	  						return dbService.customerGroupTable.createRow(r);
	  					});

	  					dbService.db_.insert().into(dbService.customerTable).values(customers).exec();
	  					dbService.db_.insert().into(dbService.customerGroupTable).values(customerGroups).exec();
	  				});
	  		});
	  	}

	  	function find(q){
	  		var deffered = $q.defer();
	  		dbService.connect().then(function(){
	  			var search = new RegExp("^"+q+".*$", 'i');
		  		dbService.db_.select()
		  				.from(dbService.customerTable)
		  				.where(dbService.customerTable.name.match(search))
		  				.exec()
		  				.then(function(r){
		  					deffered.resolve(r);
		  				});
	  		})
	  		return deffered.promise;
	  	}

	  	function findById(id){
	  		var deffered = $q.defer();
	  		dbService.connect().then(function(){
		  		dbService.db_.select()
		  				.from(dbService.customerTable)
		  				.where(dbService.customerTable.id.match(id))
		  				.exec()
		  				.then(function(r){
		  					deffered.resolve(r);
		  				});
	  		})
	  		return deffered.promise;
	  	}	  	
	  }
})()