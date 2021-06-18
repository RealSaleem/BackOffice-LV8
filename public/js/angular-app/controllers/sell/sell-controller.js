posApp.run(function ($templateCache, $http) {
    $http.get(asset(sell_templates('main.html')), { cache: $templateCache });
    $http.get(asset(sell_templates('summary.html')), { cache: $templateCache });
    $http.get(asset(sell_templates('pay-confirmation.html')), { cache: $templateCache });
    $http.get(asset(sell_templates('view-reciept.html')), { cache: $templateCache });
});
posApp.config(function($routeProvider) {
    $routeProvider
        // route for the home page
        .when('/main', {
            templateUrl : asset(sell_templates('main.html')),
            //controller  : 'mainController'
        })
        .when('/summary', {
            templateUrl : asset(sell_templates('summary.html')),
            //controller  : 'mainController'
        })
        .when('/confirmation', {
            templateUrl : asset(sell_templates('pay-confirmation.html')),
            //controller  : 'mainController'
        })
        .when('/view-reciept', {
            templateUrl : asset(sell_templates('view-reciept.html')),
        })
});
if (localStorage.getItem("isRegisterOpen") === null) {
    localStorage.setItem("isRegisterOpen", false);
}

if (localStorage.getItem("openRegister") === null) {
    localStorage.setItem("openRegister", 0);
}

posApp.controller('sellCtrl', function($scope
	, $location
	, productLocalDBService
    , productDataService
	, $mdDialog
	, cartService
	, orderDataService
	, categoryDataService
	, customerLocalDBService
    , customerDataService
    , languageDataService
    , registerDataService
    , customerGroupDataService
    , supplierDataService
    , storeDataService
    , variantDataService
	, $timeout
	, $rootScope
	, outletDataService
	, cityDataService
	, currencyDataService
	, registerClosureDataService){
	
	$scope.attrArray = [];
    $scope.featuredProducts = [];
    $scope.IsRegisterOpen = (localStorage.getItem("isRegisterOpen") === "true");

    $scope.Registers = [];
    $scope.Categories = [];
    $scope.SelectedRegister = localStorage.getItem("openRegister");
    $scope.OpeningBalance = 0;
    $scope.ReminderNotes = '';
    $scope.OpeningNotes = '';
    $scope.ParkedOrders = [];
    $scope.ReturnOrderId = 0;
    $scope.cart = {};
    $scope.printers = {};
    $scope.table_no = '';
	$scope.syncing = false;
	$scope.DineinOrders = {};
	$scope.countrySelected = true;
	$scope.receiptData = {};
	$scope.home_active = true;
	$scope.imageUrl = asset('public/img/logo_pin.png');

	$scope.cart.discount_percent = true;
	$scope.addCustomerPermission = false;
	// $scope.cart.reward_points = 0;
	// $scope.cart.reward_value = 0;
	$scope.print_plugin = false;
		//





    var productId;
    var paymentTypeArray = [];
    
    $scope.GetParameterByName = function (name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

	$scope.GetRegisters = function() {
	    if (!$scope.IsRegisterOpen) {
	        registerDataService.get_all($scope.SelectedOutlet).then(function (results) {
	            $scope.Registers = results;
	        });
	    }
	}

	$scope.GetOutlets = function() {
	    if (!$scope.IsRegisterOpen) {
	        outletDataService.get_user_outlets().then(function (results) {
	            $scope.Outlets = results;
	        });
	    }
	}
	function Current_User() {
	        storeDataService.get().then(function (results) {
	            $scope.Current_User = results.user;
	            // console.log($scope.Current_User);
	        });
	}	    

    function prepareSellScreen() {

        if (!($scope.IsRegisterOpen && $scope.SelectedRegister > 0)) {
            $scope.IsRegisterOpen = false;
            $scope.GetOutlets();
        }
            

    	$scope.cart.discounted_amount = 0;
    	$scope.cart.discount_percentage = 0;
    	$scope.cart.discount_amount = 0;
  //   	$scope.cart.reward_points = 0;
		// $scope.cart.reward_value = 0;
		// $scope.cart.applyReward = true;

    	if($scope.cart.customer_id != null && $scope.cart.customer_id > 0)
    	{
			let customer = {
				name : $scope.cart.name,
				id : $scope.cart.customer_id
			}
			$scope.selectedCustomer = customer;
    	}

   		$scope.getCurrency();
   		$scope.getDecimal();
   		languageDataService.get_all().then(function(response){
			$scope.lang = response;
		});

		customerLocalDBService.seed().then(function(){

		});


        let orders = orderDataService.getParkedOrders();
        
        if (orders != null && orders.length > 0) {
            $scope.ParkedOrders = orders;
        }

        let id = $scope.GetParameterByName('id');

        if(id != null && id > 0){

            $scope.ReturnOrderId = id;
            order = JSON.parse(sessionStorage.getItem("Order"));
        	sessionStorage.removeItem("Order");

            //$scope.discardCart();
            orderDataService.removeParkedCart($scope.cart);
		    $scope.ParkedOrders = [];
		    $scope.ParkedOrders = orderDataService.getParkedOrders();
	    	$scope.cart = cartService.initializeCart();

            $scope.cart.total = order.total;
            $scope.cart.notes = order.notes;
            $scope.cart.discount = order.discount;

        //     if(order.discount > order.order.discount){
        //         disExceeds = true;
        //         return;
        //     }
        //     if(disExceeds){
        //     toastr.error('Invalid discount');
        //     return;
        // }
            

            $scope.cart.sub_total = order.sub_total;
            $scope.cart.discount_percentage = 0;
            //$scope.cart.discount_product = 0;
            $scope.cart.payments = [];
            $scope.cart.status = 'Pending';
            $scope.cart.Time = new Date();
            $scope.cart.return_order_id = $scope.ReturnOrderId;

            if (order.orderitems != null && order.orderitems.length > 0) {
                $scope.cart.total_items = order.orderitems.length;
                order.orderitems.map((item) => {
                	variantDataService.get(item.variant_id).then((resp) => { 
                		item.variant = resp;
                		let cartItem = {
	                        variant: item.variant[0],
	                        product: null,
	                        quantity: -(item.quantity),
	                        price: item.variant[0].retail_price,
	                        discount_percentage: 0,
	                        discount_amount: 0,
	                        //discount_product: 0,
	                        discounted_amount: item.variant[0].discount,
	                        markup: 0,
	                        // custom_price: item.variant[0].retail_price,
	                        custom_price: item.total,
	                        markup_percentage: 0,
	                        notes: '',
	                        id: Math.random(),
	                        description: item.description
	                    };
	                    // productLocalDBService.findById(item.variant[0].product_id).then((response) => {
	                    //     let product = response;
	                        // console.log(response);
	                    //     cartItem.product = product[0];
	                    //     $scope.cart.items.push(cartItem);
	                    // });

	                    productDataService.get(item.variant[0].product_id).then((response) => {
	                        let product = response;
	                        cartItem.product = product;
	                        $scope.cart.items.push(cartItem);
	                    });
	                    return item;
                	} );
                   
                });

		    	if(order.customer != null){
		    		order.customer.mobile = '';
		    		$("#customer_autocomplete :input[type='search']").val(order.customer.name);
		    		setTimeout(()=>{
		    			$scope.selectedCustomer = order.customer;
		    			$scope.cart.customer_id = order.customer.id;
		    		},2000)
		    	}                
            } else {
                $scope.cart.total_items = 0;
                $scope.cart.items = [];
            }

        }
	                        

        customerGroupDataService.all().then(function(customerGroup){
				$scope.customerGroup = customerGroup;
			});

		supplierDataService.all().then(function(supplier){
				$scope.supplier = supplier;
			});
		
		orderDataService.getDineinOrders().then(function(orders){
			// console.log(orders);
			$scope.DineinOrders = orders;
		});

	    setTimeout(()=> {
		    $("#barcode :input[type='search']").val('').focus();
	    },10000)

	    // console.log($('#add-customer').val());
	    $scope.addCustomerPermission = ($('#add-customer').val() === 'true');
	    // console.log($scope.addCustomerPermission);

    }

	/*
	* Product attributes of select product
	*/
	var _productAttributs = [];

	$scope.cart = cartService.init();	


	/*
	* The value user selected of selected product's
	* attributes
	*/
	var _selectedAttributeValues = {};

	$scope.selectedProduct = null;

	$scope.init = function()
	{
		productLocalDBService.seed()
			.then(getCategories)
			.then(Current_User)
			.then(prepareSellScreen)
			.then($scope.getFeatured);


	}

	function getReceiptData(reg_id,outlet_id){
		registerClosureDataService.getReceiptData(reg_id, outlet_id).then(function(results){
			localStorage.setItem('receiptData',JSON.stringify(results));
			$scope.storeImage = results.store.store_logo;
		});
	}

    /*
	* seed database from server 
	* always when controller loads
	*/
	

	function getCategories(){
	    categoryDataService.all().then(function (results) {
	        $scope.Categories = results;
	        // console.log(results);
		});
	}
	
	/*
	* loads featured products
	*/
	$scope.getFeatured = function(category){
	   $scope.tables = [];

		$scope.Categories.forEach( cat => {
			cat.is_active = false;
		});
		$scope.home_active = false;
		if(category == null)
			$scope.home_active = true;
		else
			category.is_active = true;
	    productLocalDBService.getFeatured().then(function (results) {
	        $scope.featuredProducts = [];
			if(results[0].inhouse_reciept == true){
				 $scope.print_plugin = true;

				 for(i = 1; i <= 100; i++){
				 	if ($scope.tables.length < 100) {
                    	$scope.tables.push(i);
				 	}
                } 
			}
	        results.map((fp) => {
	        	if(category == null){	        		
	        		if(fp.is_featured == 1)
	        			$scope.featuredProducts.push(fp);	        		
		        }
		        else{
		        	if((fp.category_ids.includes(category.id) || fp.category_id == category.id ) && fp.is_featured == 1)
	        			$scope.featuredProducts.push(fp);
					}		        	
	        })
	        // console.log(results);
		})
	}

	$scope.searchText = '';
	$scope.querySearch = function(q){
		return productLocalDBService.find(q);
	}

	$scope.searchVariantText = '';
	$scope.searchVariant = function(qv){
		if($scope.IsRegisterOpen)
		{
			return productLocalDBService.find_variant(qv);
		}
	}

	/*
	* Initiates add product process
	* for selected product
	*/
	$scope.initAdd = function(product){
		$scope.selectedProduct = product;

		if(!product) return;
		var attributes = productLocalDBService.getAttributes(product);		
		//products with one variant and no attributes are
		// directly added to cart, no need to ask anything
		if(attributes.length == 0){
			productLocalDBService.getVariant(product.id)
				.then(function(v){
					addToCart(v, product);
				});
			return;
		}

		//if product has attributs then ask for values of attributes
		// before determining variant
		$scope.selectedProduct.attributes = attributes;
		//show modal for asking attribute values
		_productAttributs = angular.copy(attributes);
		setNextAttribute();
		showVariantSelectionModal();
	}

	// Barcode Scanner variants
	$scope.initAddVariants = function(product_v){
		// is it product or variant
		let p_variant = angular.copy(product_v);
		let product = {};		
		$scope.searchVariantText = '';

		// if is it variant
		if(p_variant != null && p_variant.hasOwnProperty('product_id') && p_variant.product_id > 0){
			productLocalDBService.findById(p_variant.product_id).
			then((response)=>{
				product = response[0];
				addToCart(product_v,product);
			});
		}else{
			$scope.initAdd(p_variant);
		}
	}


	$scope.setNameOrSku = function(product){
		let p_variant = angular.copy(product);
		if(p_variant != null && p_variant.hasOwnProperty('product_id') && p_variant.product_id > 0){
			return p_variant.sku;
		}else{
			return p_variant.name;
		}		
	}	

	/*
	* add product in cart array
	*/
	function addToCart(variant, product){
		// console.log(variant,product);
		productId = product.id;
		

		if(variant.allow_out_of_stock == 1 && variant.sell_out_of_stock == true){
			variant.quantity = 100000;
		}
		if (variant.quantity <= 0 ) {
			toastr.error('Product out of Stock');
		}
		else{
			// if(variant.sell_out_of_stock == true ){
			// 	variant.sell_out_of_stock = true
			// }
			cartService.addItem($scope.cart, variant, product);
		}


		$scope.searchVariantText = '';
		$scope.selectedProduct = {};

		$("#barcode :input[type='search']").val('').focus();
	}
	
	$scope.$watch('cart', function(newVal, oldVal){
		if(newVal !== oldVal){
			// newVal.total= $scope.cart.total - $scope.cart.reward_value
			// console.log(newVal);
			if(newVal.customer_id == 0){
				$scope.cart.reward_value = 0;
				$scope.cart.reward_points = 0;
				$scope.cart.applyReward = false;
			}
				// console.log('new reward_value',newVal.reward_value);
				// console.log('old reward_value',$scope.reward_value);

			if($scope.cart.reward_value >= $scope.cart.total){

				if(newVal.applyReward  == true && newVal.total > 0){

					newVal.reward_value = $scope.cart.total;
					toastr.error('Max reward must be less or equal to total order amount');

				}else{
					if(newVal.customer_id > 0){
						if(newVal.applyReward  == false ){
							// console.log('reward_value',$scope.cart.reward_value );
							$scope.cart.reward_value = $scope.reward_value;
						}
					}
				}
			}
			// var stock_cost;
			// productDataService.get_stock(productId).then(function(stock){
			// 	stock_cost = stock.cost_price;
				// console.log(stock_cost);
		 	//	});
				cartService.update(newVal, oldVal);
			}

			$scope.payment = cartService.getBalance(newVal);
			
			//goto confirmation page if status completed
			if($scope.cart.status == 'Complete'){
				window.location.hash = '/confirmation';
				$scope.cart.status = '';
				// $scope.cart = cartService.init();
			}

	}, true)

	/*
	* remove the i(item) from cart
	*/
	$scope.removeItem = function(i){
		//$event.stopPropagation();
		cartService.removeItem($scope.cart, i);
		$('#deleteCartItem').modal('hide')
	}

	$scope.openRemoveItemModal = function(i, $event){
		$event.stopPropagation();
        $scope.deleteItem = i;
        $('#deleteCartItem').modal('show');
    }

	$scope.setAttributeValue = function(value){
		_selectedAttributeValues[$scope.currentAttribute.name] = value;
		setNextAttribute();
	}

	/*
	* set next attribute options for selection
	*/
	function setNextAttribute(){
		$scope.currentAttribute = _productAttributs.shift();
		// if still attribute left, ask their values
		if($scope.currentAttribute){
			productLocalDBService.getOptions(_selectedAttributeValues,$scope.currentAttribute, $scope.selectedProduct)
				.then(function(options){
					// console.log(options);
					$scope.attributeOptions = options.filter((option) => {
						return option;
					})
				});
		}else{
		 	//now select product variant based upon the user's selection
		 	//of attribute values
		 	var p = productLocalDBService.getVariant($scope.selectedProduct.id, _selectedAttributeValues['attribute_value_1'], _selectedAttributeValues['attribute_value_2'], _selectedAttributeValues['attribute_value_3'])
		 		.then(function(v){
		 			if(v){
		 				addToCart(v, $scope.selectedProduct);
		 				_selectedAttributeValues = {};
		 				return;
		 			}

		 			//TODO: if v is null, means no product varaint is 
		 			// added with given attribute values, need to 
		 			// discuss this scenerio more
		 			toastr.error('No Variant found matching selected attributes');
		 		});
		 	$('#variantSelectionModal').modal('hide');
		}
	}

	$scope.resetSelectedAttributes = function(v)
	{	
		_selectedAttributeValues = {};
	}

	/*
	* show popup modal on screen for selection of product
	* attribute values
	*/
	function showVariantSelectionModal() {
		$('#variantSelectionModal').modal('show');
	};

	


	/*
	* toggle display of footer under item
	*/
	$scope.toggleItemDetails = function(item){
		$('.' + item).slideToggle(10, 'swing');
	}

	$scope.discardCart = function () {
		$("#customer_autocomplete :input[type='search']").val(null);
		$scope.selectedCustomer = null;
		$scope.ReturnOrderId = 0;
	    $scope.RemoveParkedCart();
	    $scope.cart = cartService.initializeCart();

	    $('#discardCart').modal('hide');
	}

	$scope.parkCart = function () {
		if($scope.cart.items.length == 0){
			toastr.error('No items in cart');
			return;
		}
		// console.log('cart', $scope.cart.print);
	    if ($scope.SelectedRegister != null) {
	        orderDataService.addParkedOrders($scope.cart,localStorage.getItem("outletId"));
	        $scope.ParkedOrders = [];
	        $scope.ParkedOrders = orderDataService.getParkedOrders();
	        $scope.cart = cartService.initializeCart();

	    } 
	    $('#myModalpark').modal('hide');
	    $("#customer_autocomplete :input[type='search']").val(null);
	    return false;
	}

	$scope.printInhouseReceipt1 = function(cartItems){

		let items = [...cartItems.filter(item => !item.print) ];
		items.map(item => {
			item.print  = true;
		});
		// console.log('itsms',items);
		$scope.table_no = $scope.cart.table_no;
		$scope.waiter = $scope.cart.waiter;
		$scope.printInhouseReceipt(items,$scope.table_no);


	}


	$scope.printInhouseReceipt = function(items, table_no){

		// let items = [...cartItems];
		// let items = [...cartItems.filters(item => !item.print) ];
		let printers = {};
		$scope.table_no = table_no;
		let categories = [];

		items.map(item => {
			categories.push(item.product.category_ids[0]);
		});

		let unique = [...new Set(categories)]; 

		// let table_no = '';
		items.map(item => {
			let category = item.product.category_ids[0];
			let selectedCategory = '';
			
			if(item.product.is_composite == 1){
				item.product.combo.map(itm => {
				    if(itm.category == ''){ 
				        itm.category = $scope.Categories.filter(item => item.id == category)[0].name;   
				    }
				    console.log(itm.category);
				    let selectedData = [];
				    console.log('itsms',itm);
					selectedCategory = itm.category;
					selectedData['quantity'] = itm.qty * item.quantity;
					selectedData['description'] = itm.product_variant;
					if(printers[selectedCategory] == null ||  printers[selectedCategory] == undefined){
						printers[selectedCategory] = [];
					}
					printers[selectedCategory].push(selectedData);
					
				})

			}else{
			    let selectedData1 = [];
				selectedCategory = $scope.Categories.filter(item => item.id == category)[0].name;
				 console.log('itsms',item);
				selectedData1['quantity'] = item.quantity;
				selectedData1['description'] = item.variant.attribute_value_1 +''+ item.variant.attribute_value_2 +''+ item.variant.attribute_value_3;
				// selectedData1['description'] = item.product_name;
				if(printers[selectedCategory] == null ||  printers[selectedCategory] == undefined){
					printers[selectedCategory] = [];
				}
				printers[selectedCategory].push(selectedData1);

			}
		});	
		$scope.printers = printers;
		console.log($scope.printers);	
		
		// console.log( Object.keys(printers)	);	
		if(items.length > 0){
			setTimeout(function() {
				$scope.inhouseReciept();
			}, 300);

		}
		// return;

	}
	// $scope.printInhouseReceipt = function(cartItems){
	// let items = [...cartItems];
	// setTimeout(function() {
	// $scope.printInhouseReceipt1(items);
	// }, 1000);
	// }

	$scope.inhouseReciept = function(){
       	var contents = document.getElementById("inContents").innerHTML;
        var frame1 = document.createElement('iframe');
        frame1.name = "frame1";
        frame1.style.position = "absolute";
        frame1.style.top = "-1000000px";
        document.body.appendChild(frame1);
        var frameDoc = (frame1.contentWindow) ? frame1.contentWindow : (frame1.contentDocument.document) ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Original Reciept</title>');
        frameDoc.document.write('</head><body>'); 
		frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            document.body.removeChild(frame1);
        }, 500);
        return false;
    }

	$scope.LoadParkedCart = function(cart)
	{
		if($scope.ParkedOrders.length == 0){
			toastr.error('No parked items');
			return;
		}
		$("#customer_autocomplete :input[type='search']").val(cart.name);
		$scope.cart = cart;
		// console.log("$scope.cart");
		// console.log($scope.cart);
		$scope.cart.status = 'Resumed';
		var cartAsArray = [];
		cartAsArray.push($scope.cart);

		let parkedOrders = JSON.parse(localStorage.getItem("parkedOrders"));
		if (parkedOrders != null) {
			// console.log(cart.park_identity);
			let orderIndex = parkedOrders.findIndex(o => o.park_identity === cart.park_identity);
			// console.log(orderIndex)
			if (orderIndex != -1 ) { // -1 is not found
				parkedOrders.splice(orderIndex, 1, cart);
				// console.log('old record deleted ');
			}
			else{
				// console.log("not_deleted");
			}
		}
		// console.log("cartAsArray");
		// console.log(cartAsArray);
		localStorage.setItem('parkedOrders', JSON.stringify(parkedOrders));
		
		orderDataService.sync_park_orders(cartAsArray);
	    $('#parkedOrders').modal('hide');
	}

	$scope.LoadDineinOrders =function(data){
		
		if($scope.DineinOrders.length == 0){
			toastr.error('No Dinein orders');
			return;
		}

		let order = $scope.DineinOrders.filter(order => {
			if(order.id == data.id){
				return order;
			}
		});

		let item = {...order[0]};

		$("#customer_autocomplete :input[type='search']").val(item.cart.name);
		$scope.cart = {...item.cart};

		// console.log($scope.cart);


		$scope.cart.status = 'Resumed';
		$scope.cart.is_dinein = 1;
		orderDataService.sync_park_orders([$scope.cart]);
		
	    $('#dineinOrders').modal('hide');
	}

	$scope.RemoveParkedCart = function()
	{
	    orderDataService.removeParkedCart($scope.cart);
	    $scope.selectedCustomer = null;
	    $scope.ParkedOrders = [];
	    $scope.ParkedOrders = orderDataService.getParkedOrders();
	}

	$scope.OpenRegister = function () {
	    let data = {
	        register_id: $scope.SelectedRegister,
	        opening_balance: $scope.OpeningBalance,
	        opening_notes: $scope.OpeningNotes
	    };

	    registerDataService.open(data).then(function (results) {
	        if (results > 0) {
	            localStorage.setItem("isRegisterOpen", true);
	            localStorage.setItem("outletId", $scope.SelectedOutlet);
	            localStorage.setItem("registerHistoryId", results);
	            localStorage.setItem("openRegister", $scope.SelectedRegister);
	            localStorage.setItem("user_id", $scope.Current_User.id);
	            localStorage.setItem("store_id", $scope.Current_User.store_id);
	            $scope.IsRegisterOpen = true;
	            toastr.success(results.Message, 'Success');
	            getReceiptData($scope.SelectedRegister, $scope.SelectedOutlet);
	            $scope.init();
	        } else {
	            toastr.error(results.Message, 'Success');
	        }
	    });

        $('#myModalCloser').modal('hide');
	    return false;
	}

	/*Add card ref number*/
	$scope.showCardModal = function(payment){
        $scope.cardPayment = payment;
        $('#modalCreditCard').modal('show')
    }
	/*add payment in cart*/
	$scope.addPayment = function (type, amount, ref_no) {
		if (ref_no != undefined || ref_no != '' ) {
			$('#modalCreditCard').modal('hide');
			$("div.modal-backdrop").remove();
		}
			// console.log(type);
		// if($scope.cart.park_identity){
		// 	orderDataService.resume_park_orders($scope.cart);
		// }
		
	    if ($scope.SelectedRegister != null) {
	      var balance = 0;

			if(amount >= $scope.cart.total){
	        	balance = cartService.addPayment($scope.cart, type, amount);
			}else{
			    toastr.error('Amount must be equal or greater to balance.');
			    return;
			}


	        if (amount < 0) {
	        	status = 'Returned';
	        }
	        else{
	        	status = 'Complete';
	        }
	        $scope.payment = balance;
	       

	        var totalPayment = $scope.cart.total + (balance*(-1));
	        /*payment completed*/
	        if (balance <= 0.9) {
	            let register_history_id = localStorage.getItem("registerHistoryId");
	            $scope.cart.date = new Date().toLocaleDateString();
	            $scope.cart.time = new Date().toLocaleTimeString();
	            orderDataService.add($scope.cart, totalPayment, balance,status, type, ref_no,$scope.SelectedRegister, register_history_id, $scope.ReturnOrderId, $scope.Current_User.id,$scope.Current_User.store_id)
                    .then(function (done) {
          
                         if (done === true) {
                            $scope.cart.status = 'Complete';
                            $scope.receiptData = JSON.parse( localStorage.getItem('receiptData') );
                            $scope.receiptData.cart = $scope.cart;
                            $scope.receiptData.cart.items.forEach( item => {
                            	var total_percent = (parseFloat(item.custom_price) + parseFloat(item.discounted_amount))*(parseFloat(item.discount_percentage)/100);
                            	item.subtotal = parseFloat(item.custom_price) + parseFloat(item.discounted_amount);                   
                            	item.total = parseFloat(item.custom_price) + parseFloat(item.discounted_amount) - parseFloat(total_percent);
                            	item.discount = (item.quantity * item.subtotal) - (item.quantity * item.total);
                            })
							localStorage.setItem('receiptData',JSON.stringify($scope.receiptData));

                        } else {
                            toastr.error('Couldnt commit sell, please try again')
                        }
                        $scope.RemoveParkedCart();
                    });
	        }
	    }
	}

	$scope.closeSell = function(){
		//$scope.printReceipt();
		$scope.discardCart();
		$scope.selectedCustomer = null;
		window.location.hash = '/main';
	};

	$scope.printReceipt = function(){
		//window.print();
		$scope.discardCart();
		getReceiptData();
		$scope.receiptData = JSON.parse( localStorage.getItem('receiptData') );
		window.location.hash = '/view-reciept';
	
	}
	

	$scope.sync = function () {
		$scope.syncing = true;
	     orderDataService.sync();
		$scope.syncing = false;
	}

	/*customer section start here*/
	$scope.customer = { loyalty : true };//binded to add new customer modal
	$scope.new_customer_id = 0;
	$scope.addCustomer = function (customer) {
	    customerDataService.add(customer).then(function (response) {
	        if (response) {
	            toastr.success(response.Message, 'Success');
	            $scope.selectedCusomterChanged(response);

	            customerLocalDBService.seed().then((data) => {
				});
				
			    $("#customer_autocomplete :input[type='search']").val($scope.customer.name).focus();

		        $scope.customer = {};
		        $('#addCustomerModal').modal('hide');
	        }
	        else {
	            toastr.error(response.Exception)
	        }
	    });
	}


	//customer autocomplete
	$scope.selectedCustomer = null;
	$scope.reward_points1 = 0;
	$scope.reward_points =0;
	// $scope.cap_amount = 0;
	$scope.redeem_rate = 0;
	$scope.selectedCusomterChanged = function(customer){
		
		if(customer == null){
			$scope.cart.customer_id = 0;
				$scope.reward_points = 0;
				// $scope.cap_amount = 0;
				$scope.redeem_rate = 0;
		}
		else{
			// $scope.cart.customer_id = customer.customer_id;
			$scope.cart.customer_id = customer.id;
			$scope.cart.name = customer.name;
			//$scope.cart.last_name = customer.last_name;
			$scope.cart.mobile = customer.mobile;
			$scope.cart.park_note = null;

				$scope.cart.customer   = customer
				$scope.redeem_rate  = customer.redeem_rate
				$scope.cart.cap_amount   = customer.cap_amount
			// if(customer.reward_points >= 0 ){
				// console.log('customer',customer.reward_points);
				$scope.reward_points 	 = customer.reward_points 
				$scope.cart.reward_points  = $scope.reward_points
				$scope.cart.reward_value  = $scope.redeem_rate * $scope.reward_points

				$scope.reward_value  = customer.reward_value
				$scope.cart.applyReward = false;
			// }

		}
	}
	$scope.customerSearchTxt = '';
	$scope.findCustomer = function(q){
		return customerLocalDBService.find(q);
	}

	$scope.findCustomerById = function(id){
		return customerLocalDBService.findById(id);
	}	

	$scope.checkQtyThenProceed = function(){
		$qtyErr = false;
		$scope.cart.items.forEach(function(itm){
			// console.log(itm);
			if(itm.quantity > itm.variant.quantity){
				toastr.error( itm.product.name + " quantity exceeds, Stock available: " + itm.variant.quantity);
				$qtyErr = true;
			}
		})
		if(!$qtyErr){
			window.location.hash = '/summary';
		}
			
	}

	$scope.discountTab = true;

	$scope.openTab = function(tab){
		// console.log(tab);
		if(tab == 'notes'){
			$scope.notesTab = true;
			$scope.discountTab = false;
			$('#discountTab').removeClass('active');
			$('#notesTab').addClass('active');
		}
		else{
			$scope.discountTab = true;
			$scope.notesTab = false;
			$('#notesTab').removeClass('active');
			$('#discountTab').addClass('active');			
		} 
			
	}

	$scope.openCustomerModal = function(){
		currencyDataService.get_all().then(function(countries){
                $scope.countries = countries;
            });
		cityDataService.get_all_city().then(function(cities){
            $scope.allCities = cities;
        });
        setTimeout(()=> {
		    $('#addCustomerModal').modal('show');
	    },1000)
		
	}



	$scope.getCities = function(country){
		$scope.countrySelected = false;
		$scope.countries.forEach( ctr => { 
			if(ctr.country == country){
				$scope.selectedCities = $scope.allCities.filter( city => city.country_id == ctr.id );
				return;	
			}
		});
	
        
    }

    $scope.printReciept = function(){
       var contents = document.getElementById("dvContents").innerHTML;
        var frame1 = document.createElement('iframe');
        frame1.name = "frame1";
        frame1.style.position = "absolute";
        frame1.style.top = "-1000000px";
        document.body.appendChild(frame1);
        var frameDoc = (frame1.contentWindow) ? frame1.contentWindow : (frame1.contentDocument.document) ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Original Reciept</title>');
        frameDoc.document.write('</head><body>');
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            document.body.removeChild(frame1);
        }, 500);
        return false;
    }

    $scope.printA4Reciept = function(){
       var contents = document.getElementById("dvA4Contents").innerHTML;
        var frame1 = document.createElement('iframe');
        frame1.name = "frame1";
        frame1.style.position = "absolute";
        frame1.style.top = "-1000000px";
        document.body.appendChild(frame1);
        var frameDoc = (frame1.contentWindow) ? frame1.contentWindow : (frame1.contentDocument.document) ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Original Reciept</title>');
        frameDoc.document.write('</head><body>');
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            document.body.removeChild(frame1);
        }, 500);
        return false;
    }

    $scope.openParkModal = function(){
    	if ($scope.cart.items.length == 0)
    		toastr.error('No items in cart');
    	else{
    		$('#myModalpark').modal('show');
    	}
    }

    $scope.opendiscardCartModal = function(){
    	if ($scope.cart.items.length == 0){
    		$("#customer_autocomplete :input[type='search']").val(null);
    		$scope.RemoveParkedCart();
    		toastr.error('No items in cart');
    	}
    	else{
    		$('#discardCart').modal('show');
    	}
    }


	// angular.element(document).ready(function () {
 //        $('#amount').select();
	// });
});

$(document).ready(function(){
	//window.location.hash = "#main";
});