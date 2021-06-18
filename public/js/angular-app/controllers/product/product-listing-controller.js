posApp.controller('productListingCtrl',[
	'$scope','$sce','categoryDataService','storeDataService',
	'supplierDataService','brandDataService',
	'productDataService','urlService', 'outletDataService',
	function($scope, $sce,categoryDataService, storeDataService,supplierDataService, brandDataService, productDataService,urlService, outletDataService){
	$scope.products = [];
	$scope.brands = [];
	$scope.categories = [];
	$scope.suppliers = [];
	$scope.filters = { status: 1 };
	$scope.Next = null;
	$scope.Previous = null;
	$scope.Links = null;
	$scope.button = {};
	$scope.button.disabled = false;
	
	$scope.fetchingProducts = true;
	setTimeout(() => {  $('.select2').select2();  },500);
	/*
	* load page data i.e dropdowns data from server
	*/
	$scope.loadData = function(){
// alert('asdasd');
		brandDataService.all().then(function(brands){
				$scope.brands = brands;
				$scope.filters
			});

		supplierDataService.all().then(function(suppliers){
				$scope.suppliers = suppliers;
			});


		categoryDataService.all().then(function(categories){
			$scope.categories = categories;
		});

		let store_id =  $('#store_id').val();

		if(store_id != null && store_id>0 && store_id != ''){
	     storeDataService.get(store_id)
	 		.then(function(store){
	 			if (store.default_currency != null) {
	 				$scope.currency_disabled = true;
	 			}
	 			$scope.store = parseInt(store.stock_threshold);
	 		});
		}

		$scope.Init();

		if (urlService.getUrlPrams().name != '' || urlService.getUrlPrams().name != null) {
			$scope.filters.name = urlService.getUrlPrams().name;
		}
		getProudcts($scope.filters);
	}

	$scope.getHTML = function(string){
		return $sce.trustAsHtml(string);
		}

	$scope.printBarcode = function(barcode_num){
       var contents = document.getElementById("barcode_section").innerHTML;
        var frame1 = document.createElement('iframe');
        frame1.name = "frame1";
        frame1.style.position = "absolute";
        frame1.style.top = "-1000000px";
        document.body.appendChild(frame1);
        var frameDoc = (frame1.contentWindow) ? frame1.contentWindow : (frame1.contentDocument.document) ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Barcode</title>');
        frameDoc.document.write('</head><body>');

        frameDoc.document.write('<div style="width:50%; float:left">');
	        for (let i = 1; i <= barcode_num/2; i++) {
	        	frameDoc.document.write(contents);
		        frameDoc.document.write('<br>');
	        }	
        frameDoc.document.write('</div>');

        frameDoc.document.write('<div style="width:50%; float:left">');
        	for (let i = 1; i <= barcode_num/2; i++) {
	        	frameDoc.document.write(contents);
		        frameDoc.document.write('<br>');
	        }
        frameDoc.document.write('</div>');

        frameDoc.document.write('</body></html>');

        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            document.body.removeChild(frame1);
        }, 500);
        return false;
    }

	$scope.Init = function(){
		if(sessionStorage.category_id){
			$scope.filters.category_id = sessionStorage.category_id;
			sessionStorage.removeItem('category_id');
		}

		if(sessionStorage.brand_id){
			$scope.filters.brand_id = sessionStorage.brand_id;
			sessionStorage.removeItem('brand_id');
		}	

		if(sessionStorage.supplier_id){
			$scope.filters.supplier_id = sessionStorage.supplier_id;
			sessionStorage.removeItem('supplier_id');
		}	
	}
	$scope.showDeleteModal = function(p){
		console.log(p)
		$scope.selectedProduct = p;
		$('#deleteModal').modal('show')
	}
	$scope.deleteProduct = function(p){
		productDataService.delete_product(p).then(function (response) {
            if (response!=null) {
               /* toastr.success('Supplier has been deleted successfully', 'Supplier Deleted');
                let lang = languageService.get('Supplier Deleted');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'product';},2000);
                //window.location.href = document.referrer;
            } else {
                toastr.error(response.Exception)
            }
        });
	}

	/*
	* This function is called on clicking product status checkbox
	* this will toggle current value of active and update it with 
	* server.
	*/
	$scope.updateStatus = function($event, p){
		$event.stopPropagation();
		//toggle status
		p.active = !p.active;
		p.process = true;
		productDataService.updateStatus(p.id, p.active)
			.then(function(success){
				p.process = false;
				if(success){
					if($scope.filters.status !== undefined) 
						remove(p);
				}else{
					p.active = !p.active;
				}
			});
	}

	$scope.showOnWeb = function($event, p){
		$event.stopPropagation();
		//toggle status
		p.web_display = !p.web_display;
		p.process = true;
		productDataService.updateWebDisplayStatus(p.id, p.web_display)
			.then(function(success){
				p.process = false;
				if(success){
					if($scope.filters.status !== undefined) 
						remove(p);
					// console.log(p);
						// window.location.reload();
				}else{
					p.web_display = !p.web_display;
				}
			});
	}	
	$scope.showOnDineIn = function($event, p){
		$event.stopPropagation();
		//toggle status
		p.dinein_display = !p.dinein_display;
		p.process = true;
		productDataService.updateDineInDisplayStatus(p.id, p.dinein_display)
			.then(function(success){
				p.process = false;
				if(success){
					if($scope.filters.status !== undefined) 
						remove(p);
		// console.log(p);

						// window.location.reload();
				}else{
					p.dinein_display = !p.dinein_display;
				}
			});
	}



	function remove(p){
		var index = $scope.products.findIndex(function(product){
		    return (p.id == product.id);
		});

		$scope.products.splice(index, 1);
	}

	$scope.applyFilters = function(filters){
		getProudcts(filters);
	}

	$scope.reset = function(){
		$scope.filters = { status: 1 };
		setTimeout(() => { $("#C_reset").select2().trigger("change");  },100);
		setTimeout(() => { $("#B_reset").select2().trigger("change");  },100);
		setTimeout(() => { $("#SU_reset").select2().trigger("change");  },100);
		getProudcts($scope.filters);
	}


	$scope.changeTab = function(status){
		$scope.filters.status = status;
		getProudcts($scope.filters);
	}

	$scope.changeWebDisplayTab = function(web){
		$scope.filters.web = web;
		getProudcts($scope.filters);
	}	
	$scope.changeDineInDisplayTab = function(dinein){
		$scope.filters.dinein = dinein;
		getProudcts($scope.filters);
	}
	$scope.getUnique = function (array){
		let result = [];
		 return array.reduce(function(res, value) {
			if (!res[value.outlet_id]) {
				res[value.outlet_id] = { outlet_id: value.outlet_id, quantity: 0,name:value.name,variant_id:value.variant_id };
				result.push(res[value.outlet_id])
			}
			res[value.outlet_id].quantity += parseFloat(value.quantity);
			return res;
		}, {});
		return result;
	}

// 	name: "asdda"
// outlet_id: 33
// quantity: "40"
// variant_id: 1796

	function getProudcts(filters){
		$scope.fetchingProducts = true;
		productDataService.list(filters).then(function(response){
		    $scope.products = response.data.map((product) => {
		        product.show_variant = false;
				let stock = [];
				product.product_variants.map(variant=>{
					stock = $scope.getUnique(variant.product_stock);
					variant.product_stock =  stock;
				})

		        return product;
		    });
		    console.log($scope.products);
		    $scope.Next = response.next_page_url;
		    $scope.Previous = response.prev_page_url;
		    
			$scope.fetchingProducts = false;
		}).catch(function(){
			$scope.fetchingProducts = false;
		})
	}

	$scope.Go = function (link) {
	    if (link != null) {
	        let params = link.split('?');

	        let data = params[1].split('&');
	        let queryparams = {};

	        if (data.length > 0) {
	            data.map((item) => {
	                let keyvalue = item.split('=');
	                queryparams[keyvalue[0]] = keyvalue[1];
	            });
	        }
	        getProudcts(queryparams);
	    }
	}

	$scope.toggleVariantView = function(product){
		product.variant_shown = !product.variant_shown
		product.variants = [{},{}];
	}

	$scope.Stock = {
	    invoice_date: new Date(),
	    invoice_number: '',
	    purchase_order: '',
	    cost_price: 0.00,
	    sale_price: 0.00,
	    margin: 0.00,
	    notes: '',
	    product_id: 0,
	    variant_id: 0,
	    quantity: 0
	};

	$scope.Description = null;

	$scope.ResetStock = function(){
	    $scope.Stock = {
	        invoice_date: new Date(),
	        invoice_number: '',
	        purchase_order: '',
	        cost_price: 0.00,
	        sale_price: 0.00,
	        margin: 0.00,
	        notes: '',
	        product_id:0,
	        variant_id: 0,
	        quantity:0
	    };
	}

	$scope.IncreaseQuantity = function (productName,variant) {
	    
	    $scope.Stock.product_id = variant.product_id;
	    $scope.Stock.variant_id = variant.id;
	    outletDataService.get_all().then(function(response){
			$scope.outlets = response;
		});
	    $scope.SetDescription(productName, variant);
	    return true;
	}

	$scope.IncreaseQuantityComposite = function (productName,product) {
	    outletDataService.get_all().then(function(response){
			$scope.outlets = response;
		});
	    $scope.Stock.product_id = product.id;
	    $scope.Description = productName;
	    //$scope.Stock.variant_id = variant.id;
	    //$scope.SetDescription(productName, variant);
	    return true;
	}

	$scope.DecreaseQuantity = function (productName, variant) {

	    outletDataService.get_all().then(function(response){
			$scope.outlets = response;
		});
	    $scope.Stock.product_id = variant.product_id;
	    $scope.Stock.variant_id = variant.id;
	    $scope.SetDescription(productName, variant);
	    return true;
	}

	$scope.DecreaseQuantityComposite = function (productName,product) {
	    outletDataService.get_all().then(function(response){
			$scope.outlets = response;
		});
	    $scope.Stock.product_id = product.id;
	    $scope.Description = productName;
	    //$scope.Stock.variant_id = variant.id;
	    //$scope.SetDescription(productName, variant);
	    return true;
	}

	$scope.SetDescription = function (productName,variant) {
	    $scope.Description = productName + ' / ' + variant.attribute_value_1;
	    if (variant.attribute_value_2 != "") {
	        $scope.Description += ' / ' + variant.attribute_value_2;
	    }

	    if (variant.attribute_value_3 != "") {
	        $scope.Description += ' / ' + variant.attribute_value_3;
	    }
	}

	$scope.AddStock = function(){
    	$scope.button.disabled = true;

		// $scope.Stock.margin = (($scope.Stock.sale_price - $scope.Stock.cost_price)/$scope.Stock.sale_price)*100;

	    if ($('#stock-add-form')[0].checkValidity()) {
	    	$('#LoaderDiv').show();
	        productDataService.add_stock($scope.Stock).then((response) => {
	        	$('#LoaderDiv').hide();
	            toastr.success(response.Message, 'Success');
	            setTimeout(function(){window.location.href = BASE_URL + 'product';},2000);
	            $scope.ResetStock();
	            getProudcts($scope.filters);
	            $('#addStockModal').modal('hide');
	        }).catch(function () {
	            $scope.fetchingProducts = false;
	        });
	    }

	    return false;
	}

	$scope.RemoveStock = function () {

	    if ($('#stock-remove-form')[0].checkValidity()) {
	    	$scope.button.disabled = true;
	    	$('#LoaderDiv').show();
	        productDataService.remove_stock($scope.Stock).then((response) => {
	    		$('#LoaderDiv').hide();
	           toastr.success(response.Message, 'Success');
	
	            $scope.ResetStock();
	            getProudcts($scope.filters);
	            $('#addReturnStockModal').modal('hide');
	        }).catch(function () {
	            $scope.fetchingProducts = false;
	        });
	    }

	    return false;
	}

	$scope.getDateFormat = function(timestamp) {
    return new Date(timestamp);
  }

	$scope.GetAttribute = function(attribute){
	    let description = attribute.attribute_value_1;

	    if (attribute.attribute_value_2 != null && attribute.attribute_value_2.length != '') {
	    	if (attribute.attribute_value_2 != ' ') {
	        description += ' / ' + attribute.attribute_value_2;
	    	}
	    }

	    if (attribute.attribute_value_3 != null && attribute.attribute_value_3.length != '') {
	    	if (attribute.attribute_value_2 != ' ') {
	        description += ' / ' + attribute.attribute_value_3;
	    }
	    }
	    
	    return description;
	}

	/*watchers*/
	$scope.$watch('Stock.sale_price', function (newVal, oldVal) {
	    if (newVal != oldVal) {
	    	$scope.Stock.margin = (($scope.Stock.sale_price - $scope.Stock.cost_price)/$scope.Stock.cost_price)*100;
	    	$scope.Stock.margin = (parseFloat($scope.Stock.margin)).toFixed(3);
	    }
	});
}]);