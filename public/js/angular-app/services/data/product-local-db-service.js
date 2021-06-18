/*
* This service will manage seed and find functionalities
* on product
*/

(function(){
	'use strict';
	angular
	  .module('posApp')
	  .factory('productLocalDBService', productDataService);

	  productDataService.$inject = ['dbService', 'ajaxService', '$q', '$filter'];

	  function productDataService(dbService, ajaxService, $q, $filter){
	  	return {
	  		seed 			: seed,
	  		find 			: find,
	  		find_variant    : find_variant,
	  		getVariant 		: getVariant,
	  		getAttributes	: getAttributes,
	  		getFeatured		: getFeatured,
	  		getOptions      : getOptions,
	  		findById        : findById
	  	}
	  	
	  	/*
	  	* returns featured products list
	  	* TODO : incomplete, need to have a feature product
	  	* defination before compeleting this method, currently returning
	  	* all products from db
	  	*/
	  	function getFeatured(){
	  		var defered = $q.defer();
	  		dbService.connect().then(function(){
	  			dbService.db_.select()
	  					.from(dbService.productTable)
	  					// .where(dbService.productTable.category_id.eq(category_id))
		  				.exec()
		  				.then(function(r){
		  					defered.resolve(r)
		  				});

	  		});

	  		return defered.promise;
	  	}

	  	/*
	  	* returns a promise containing 
	  	* server request for fetching products and variants
	  	*/
	  	function fetchData(){
	  		let outlet_id = localStorage.getItem("outletId");
	  		var url = 'store/products?outlet_id='+outlet_id;
	  		return ajaxService.get(url);
	  	}

	  	/*
	  	* inserts product and products variants into local database
	  	*/
	  	function insertSeedData(data){
	  		var defered = $q.defer();
	  		//insert data into database
	  		var products = data.products.map(function(r){
	  			return dbService.productTable.createRow(r);
	  		});

	  		var product_variants = data.product_variants.map(function(r){
	  			return dbService.productVariantTable.createRow(r);
	  		});

	  		/*adding products*/
	  		var p1 = dbService.db_.insert().into(dbService.productTable).values(products).exec();
	  		p1.then(function(){
	  			//console.log('products have been added');
	  			var p2 =dbService.db_.insert().into(dbService.productVariantTable).values(product_variants).exec();
	  			p2.then(function(){
	  				//console.log('product variants have been added');
	  				defered.resolve();
	  			})
	  		})
	  		return defered.promise;
	  	}

	  	/*Seed product and product variant table
	  	* from server
	  	*/
	  	function seed(){
	  		var defered = $q.defer();
	  		//fetch data from server
	  		fetchData().then(function(response){
	  			if(!response.IsValid){
	  				defered.reject();
	  				//console.log('something went wrong while fetching products from server.')
	  				return;
	  			}
	  			
	  			//connect to database before any action
		  		dbService.connect().then(function(){
		  			//delete older data before inserting new
		  			dbService.deleteAll([dbService.productTable, dbService.productVariantTable,dbService.orderTable, dbService.orderItemsTable])
		  				.then(function(){

		  					//insert data into db
			  				insertSeedData(response.Payload)
			  					.then(function(){
			  						//seed completed successfully
			  						// console.log('Refreshed');
			  						defered.resolve()
			  					}).catch(function(){
			  						//console.log('coulnt insert product data into local db');
			  						defered.reject();
			  					});

		  				}).catch(function(){
		  					//console.log('coulnt delete products from db');
		  					defered.reject();
		  				})

		  		}).catch(function(){
		  			//console.log('coulnt connect to db');
		  			defered.reject();
		  		})

	  		}).catch(function(){
	  			//console.log('something went wrong while fetching products from server.');
	  			defered.reject();
	  		})

	  		return defered.promise;
	  	}

	  	/* 
	  	* This will perform query on product table 
	  	* and returns all products with name starting
	  	* with q
	  	*/
	  	function find(q){

	  		var deffered = $q.defer();
	  		dbService.connect().then(function(){
	  			//var search = new RegExp("^"+q+".*$", 'i');
	  			var search = new RegExp(""+q+"", 'i');

	  			//console.log(search);
		  		dbService.db_.select()
		  				.from(dbService.productTable)
		  				.where(dbService.productTable.name.match(search))
		  				.exec()
		  				.then(function(r){
		  					deffered.resolve(r);
		  				});
	  		})
	  		return deffered.promise;
	  	}

	  	// Find Variants
	  	function find_variant(q){

	  		var deffered = $q.defer();
	  		dbService.connect().then(function(){
	  			var search = new RegExp(""+q+"", 'i');

	  			//console.log(search);
		  		dbService.db_.select()
		  				.from(dbService.productVariantTable)
		  				.where(lf.op.or(
					        dbService.productVariantTable.sku.match(search),
					        dbService.productVariantTable.product_name.match(search),
					        dbService.productVariantTable.attribute_value_1.match(search),
					        dbService.productVariantTable.attribute_value_2.match(search),
					        dbService.productVariantTable.attribute_value_3.match(search)
						))
		  				.exec()
		  				.then(function(r){
		  					deffered.resolve(r);
		  				});
	  		})
	  		return deffered.promise;
	  	}

	  	function findById(id) {
	  		//console.log(id);
	  	    var deffered = $q.defer();
	  	    dbService.connect().then(function () {
	  	        dbService.db_.select()
		  				.from(dbService.productTable)
		  				.where(dbService.productTable.id.eq(id))
		  				.exec()
		  				.then(function (r) {
		  				    deffered.resolve(r);
		  				});
	  	    });
	  	    //console.log(deffered.promise);
	  	    return deffered.promise;
	  	}

	  	/*
	  	* Returns single variant that matches the given values 
	  	* or null
	  	* product_id => required
	  	* attr1, attr2 and attr3 => optional
	  	*/
	  	function getVariant(product_id, attr1, attr2, attr3){
	  		// console.log(getVariant);
	  		var defered = $q.defer();
	  		var q = dbService.db_
	  					.select()
	  					.from(dbService.productVariantTable)
	  					.where(dbService.productVariantTable.product_id.eq(product_id))
	  					.exec();

		  	

			q.then(function(variants){
				if(attr1)
			  		variants = $filter('filter')(variants, {'attribute_value_1' : attr1});

				if(attr2);
					variants = $filter('filter')(variants, {'attribute_value_2' : attr2});

				if(attr3)
					variants = $filter('filter')(variants, {'attribute_value_3' : attr3});

				var variant = null;

				if(variants.length > 0) 
					variant = variants[0];

				
				
				defered.resolve(variant);
			});

			return defered.promise;
	  	}
	  	
	  	function getDistinct(table, column){

	  		var q = dbService.db_
		  				.select(lf.fn.distinct(dbService.productVariantTable.attribute_value_1))
		  				.from(dbService.productVariantTable)
		  				.where(dbService.productVariantTable.product_id.eq(product.id))
		  				.groupBy(dbService.productVariantTable.attribute_value_1)
		  				.exec();

	  	}

	  	/*
	  	* Returns array of product attributes
	  	*/
	  	function getAttributes(p){
	  		

	  		// dbService.connect().then(function(){
	  		// 	dbService.db_.select()
	  		// 		.from(dbService.productVariantTable)
	  		// 		.where(dbService.productVariantTable.product_id.eq(p.id))
	  		// 		.exec()
	  		// 		.then(function(variants){
	  		// 			console.log(variants.length);
	  		// 		});
	  		// });

	  		var attributes = [];
			if(p.attribute_1.replace(/\s/g, '').length){
				attributes.push({name : 'attribute_1', value : p.attribute_1})
			}

			if(p.attribute_2.replace(/\s/g, '').length){
				attributes.push({name : 'attribute_2', value : p.attribute_2})
			}
			
			if(p.attribute_3.replace(/\s/g, '').length){
				attributes.push({name : 'attribute_3', value : p.attribute_3})
			}

			return attributes;
	  	}

	  	/*
	  	* returns all posible values of an attribute of product
	  	*/
	  	function getOptions(selectedAttribute,attribute, product){
	  		let defered = $q.defer();
	  		attribute.name = attribute.name.replace('attribute_', 'attribute_value_');
	  		
	  		dbService.connect().then(function(){
	  			dbService.db_.select()
	  				.from(dbService.productVariantTable)
	  				.where(dbService.productVariantTable.product_id.eq(product.id))
	  				.exec()
	  				.then(function(variants){

	  					let variantsFiltered = [];

  						if(selectedAttribute.attribute_value_2){
  							variantsFiltered = variants.filter(variant => variant.attribute_value_2 == selectedAttribute.attribute_value_2 && variant.attribute_value_1 == selectedAttribute.attribute_value_1);
  						} else if(selectedAttribute.attribute_value_1){
  							variantsFiltered = variants.filter(variant => variant.attribute_value_1 == selectedAttribute.attribute_value_1);
  						} else{
  							variantsFiltered = variants;
  						}
						// console.log('-----------------');
						// console.log(variantsFiltered.length);
	  			// 		console.log('-----------------');
	  					let names = [];
	  					variantsFiltered.map(variant => {
	  						names.push(variant[attribute.name]);
	  					});
	  					let uniqueArray = [...new Set(names)];
	  					defered.resolve(uniqueArray);
	  				});
	  		})

	  		return defered.promise;
	  	}
	  }
})()