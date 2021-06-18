/**
 * This is inspired by/ripped off from various bits of LoveField demo code:
 * https://github.com/googlesamples/io2015-codelabs/blob/master/lovefield/src/final/lovefield_service.js
 * https://github.com/google/lovefield/blob/master/demos/olympia_db/angular/demo.js
 * Thank you!
 */

(function () {
    'use strict';

    angular
      .module('posApp')
      .factory('dbService', dbService);

    dbService.$inject = ['$http', '$log', '$q', '$rootScope'];

    function dbService($http, $log, $q, $rootScope) {
      var db_ = null;
      var noteTable_ = null;
      var isConnecting_ = false;
      var schemaBuilder_ = null;
      var productTable_
          , productVariantTable_
          , orderTable_
          , customerTable_
          , customerGroupTable_
          , orderItemsTable_ = null;

      var service = {
        db_: db_,

        /*tables*/
        productTable : productTable_,
        productVariantTable : productVariantTable_,
        orderTable : orderTable_,
        orderItemsTable : orderItemsTable_,
        customerTable : customerTable_,
        customerGroupTable : customerGroupTable_,
        
        /*methods*/
        connect: connect,
        deleteAll : deleteAll,
        initDatabase: initDatabase
      };

      return service;

      ////////////


      /**
      * Initializes a database connection.
      * @return {!angular.$q.Promise} - promise is resolved when the db_ and noteTable_ properties have had values assigned to them 
      * 
      * NOTE: 2015/10/10
      *       When no connection options are set LoveField does some feature detection to determine which backing store to use.
      *       If the browser supports IndexedDb it uses that, then falls back to WebSql and finally to an "in memory" data store.
      *       I found when debugging on actual devices that LoveField chose the in memory (lf.schema.DataStoreType.MEMORY) store 
      *       on iOS which didn't work so well so here we ensure WebSql is used for iOS and Android devices. 
      *       IndexedDb seemed to work well on desktop browsers. 
      *       https://github.com/google/lovefield/blob/master/docs/spec/03_life_of_db.md#311-connect-options
      *       https://cordova.apache.org/docs/en/5.1.1/cordova/storage/storage.html 
      *       http://caniuse.com/#feat=indexeddb
      *       https://github.com/google/lovefield/blob/master/docs/dd/02_data_store.md#25-websql-store   
      */
      function connect() {
        var deferred = $q.defer();  
        // console.info('connect() isConnecting_', isConnecting_);
        if (isConnecting_ === false) {
          var connectionOptions = { storeType: lf.schema.DataStoreType.INDEXED_DB };
          // if (ionic.Platform.isIOS() || ionic.Platform.isAndroid()) {
          //   connectionOptions = { storeType: lf.schema.DataStoreType.WEB_SQL };
          // }
          
          if (service.db_ === null) {
            isConnecting_ = true;
            buildSchema()
              .connect(connectionOptions)
              .then((
                function(database) {
                  isConnecting_ = false;
                  service.db_ = database;
                  service.productTable = database.getSchema().table('products');
                  service.productVariantTable = database.getSchema().table('product_variants');
                  service.orderTable = database.getSchema().table('orders');
                  service.orderItemsTable = database.getSchema().table('order_items');
                  service.customerTable = database.getSchema().table('customers');
                  service.customerGroupTable = database.getSchema().table('customer_groups');

                  deferred.resolve();
                }));
          } else {
            deferred.resolve();
          }
        } else {
          deferred.reject('Still connecting to the database');
        }
        
        return deferred.promise; 
      }
      
    
      /**
      * Checks if any data exists in the DB.
      * @return {!angular.$q.Promise.<!boolean>}
      */
      function checkForExistingData() {
        var deferred = $q.defer(); 
        
        deferred.resolve(true);
        return deferred;
        service.db_.select().from('products').exec().then(
          function(rows) {
            deferred.resolve(rows.length > 0);
          }
        );
      }

      /**
      * Builds the database schema.
      * @return {!lf.schema.Builder}
      * @private
      * TODO: this is where you would define your database tables
      * https://github.com/google/lovefield/blob/master/docs/spec/01_schema.md
      */
      function buildSchema() {
        var schemaBuilder = lf.schema.create('pos-db', 1);
        schemaBuilder.createTable('products')
            .addColumn('id', lf.Type.INTEGER)
            //.addColumn('description', lf.Type.STRING)
            .addColumn('name', lf.Type.STRING)
            .addColumn('category', lf.Type.STRING)
            .addColumn('category_id', lf.Type.INTEGER)
            .addColumn('is_composite', lf.Type.BOOLEAN)
            .addColumn('brand', lf.Type.STRING)
            .addColumn('image', lf.Type.STRING)
            .addColumn('supplier', lf.Type.STRING)
            .addColumn('attribute_1', lf.Type.STRING)
            .addColumn('attribute_2', lf.Type.STRING)
            .addColumn('attribute_3', lf.Type.STRING)
            .addColumn('is_featured', lf.Type.BOOLEAN)
            .addColumn('inhouse_reciept', lf.Type.STRING)            
            .addPrimaryKey(['id']);

        schemaBuilder.createTable('product_variants')
              .addColumn('id', lf.Type.INTEGER)
              .addColumn('product_id', lf.Type.INTEGER)
              .addColumn('supplier_price', lf.Type.NUMBER)
              .addColumn('attribute_value_1', lf.Type.STRING )
              .addColumn('attribute_value_2', lf.Type.STRING )
              .addColumn('attribute_value_3', lf.Type.STRING )
              .addColumn('retail_price', lf.Type.NUMBER)
              .addColumn('markup', lf.Type.STRING)
              .addColumn('sku', lf.Type.STRING)
              .addColumn('image', lf.Type.STRING)
              .addColumn('product_name', lf.Type.STRING)
              .addPrimaryKey(['id'])
              .addForeignKey('fk_product_product_variants', {
                local: 'product_id',
                ref: 'products.id',
                action: lf.ConstraintAction.CASCADE
              });
        
        schemaBuilder.createTable('orders')
              .addColumn('id', lf.Type.INTEGER)
              .addColumn('identity', lf.Type.STRING)//to uniquely identify this row
              .addColumn('customer_id', lf.Type.INTEGER)
              .addColumn('status', lf.Type.STRING )//PARKED, COMPLETED
              .addColumn('sub_total', lf.Type.NUMBER )
              .addColumn('discount', lf.Type.NUMBER )
              .addColumn('total', lf.Type.NUMBER)
              .addColumn('notes', lf.Type.STRING)
              .addColumn('order_date', lf.Type.DATE)
              .addColumn('order_time', lf.Type.TIME)
              .addColumn('created_at', lf.Type.DATE_TIME)
              .addColumn('synced', lf.Type.BOOLEAN)
              .addColumn('synced_at', lf.Type.DATE_TIME)
              .addColumn('balance', lf.Type.NUMBER)  //extra columns
              .addColumn('payment', lf.Type.NUMBER)              
              .addColumn('register_history_id', lf.Type.INTEGER)
              .addColumn('register_id', lf.Type.INTEGER)
              .addColumn('return_order_id', lf.Type.INTEGER)
              .addColumn('payment_method', lf.Type.STRING)
              .addColumn('payment_ref', lf.Type.INTEGER)
              .addColumn('applyReward', lf.Type.INTEGER)
              .addColumn('user_id', lf.Type.INTEGER)
              .addColumn('store_id', lf.Type.INTEGER)
              .addColumn('reward_value', lf.Type.INTEGER) // end
              .addNullable(['customer_id','balance','payment_ref'])
              .addPrimaryKey(['id'], true);

        schemaBuilder.createTable('order_items')
              .addColumn('id', lf.Type.INTEGER)
              .addColumn('order_id', lf.Type.INTEGER)
              .addColumn('variant_id', lf.Type.INTEGER)
              .addColumn('quantity', lf.Type.INTEGER )
              .addColumn('supplier_price',lf.Type.INTEGER)
              .addColumn('notes', lf.Type.STRING) 
              .addColumn('description', lf.Type.STRING) // extra column
              .addColumn('discount', lf.Type.NUMBER)
              .addColumn('subtotal', lf.Type.NUMBER)
              .addColumn('total', lf.Type.NUMBER) //end
              .addPrimaryKey(['id'], true)
              .addForeignKey('fk_order_order_items', {
                local: 'order_id',
                ref: 'orders.id',
                action: lf.ConstraintAction.CASCADE
              });

        schemaBuilder.createTable('customer_groups')
              .addColumn('id', lf.Type.INTEGER)
              .addColumn('name', lf.Type.INTEGER)
              .addPrimaryKey(['id'], true);

        var nullableCols = ['customer_group_id','id','synced','synced_at','updated_at','created_at','email','country','state','postcode','company','phone', 'mobile', 'fax', 'website', 'twitter', 'street', 'city'];
        schemaBuilder.createTable('customers')
              .addColumn('customer_id', lf.Type.INTEGER)
              .addColumn('id', lf.Type.INTEGER)
              .addColumn('customer_group_id', lf.Type.INTEGER)
              .addColumn('name', lf.Type.STRING)
              //.addColumn('last_name', lf.Type.STRING)
              .addColumn('company', lf.Type.STRING )
              .addColumn('loyalty', lf.Type.BOOLEAN )
              .addColumn('phone', lf.Type.STRING )
              .addColumn('mobile', lf.Type.STRING )
              .addColumn('fax', lf.Type.STRING )
              .addColumn('website', lf.Type.STRING )
              .addColumn('twitter', lf.Type.STRING)
              .addColumn('street', lf.Type.STRING)
              .addColumn('city', lf.Type.STRING)
              .addColumn('postcode', lf.Type.STRING)
              .addColumn('state', lf.Type.STRING)
              .addColumn('country', lf.Type.STRING)
              .addColumn('email', lf.Type.STRING)
              .addColumn('created_at', lf.Type.STRING)
              .addColumn('updated_at', lf.Type.STRING)
              .addColumn('synced', lf.Type.BOOLEAN)
              .addColumn('synced_at', lf.Type.DATE_TIME)
              .addNullable(nullableCols)
               
              .addPrimaryKey(['customer_id'], true);

        return schemaBuilder;
      }
      
      
      /**
      * Connects to and seeds the database with some dummy data if no data exists.
      * @private
      */
      function initDatabase() {
        $log.debug('Attempt to connect to and seed the database');  
        connect().then(function() {
        });        
      }
      
      /*
      * delete all rows from from tables
      * tables => required
      */
      function deleteAll(tables){
        var deferred = $q.defer();
        var promises = [];
        
        angular.forEach(tables, function(t){
          promises.push(service.db_.delete().from(t).exec());
        })

        $q.all(promises).then(function(){
          deferred.resolve();
        })

        return deferred.promise;
      }
    }
})();