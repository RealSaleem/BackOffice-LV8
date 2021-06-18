posApp.controller('productCtrl', function($scope, $rootScope, variantDataService, categoryDataService,currencyDataService, tagDataService, supplierDataService, brandDataService, productDataService, Upload, $timeout ,cityDataService,languageService,outletDataService,storeDataService){

	$scope.model = {};
	 var _sku = null;
	 var init_sku = null;
	 var count = 0;
	 var temp_tag = {};
	//TODO: remove prefilled values, except for defaults
	$scope.product = {
		supplier_price 			: '0.0',
		retail_price 			: '0.0',
		before_discount_price 	: '0.0',
		markup 					: '0',
		has_variant 			: true,
		//quantity                : '0.0',
		supplier_code			: '',
		sales_account_code 		: '',
		purchase_account_code 	: '',
		attr1_values 			: [],
		attr2_values 			: [],
		attr3_values 			: [],
		attribute_1  			: '',
		attribute_2  			: '',
		attribute_3  			: '',		
		variants 				: [],
		tags 					: [],
		track_inventory 		: false,
		is_composite            : false,
		custom_loyalty 			: 0.0,
		sku 					: _sku,
		composite_products 		: [],
		custom_sku 				: false
	};

	$scope.translations = []

	$scope.default_sku = null;
	$scope.product.default_sku_name = '';
	$scope.mark_disabled = false;
		// _sku = $scope.product.sku;
	/*Tag section*/
		$scope.selectedTag = '';
		$scope.tagSearchTxt = '';
		$scope.tags = [];
		$scope.category = {};
		
		$scope.addTag = function(name){
		    tagDataService.add({ name: name })
				.then(function(tag){
					addTagInArray(tag);
				});
		}

		$scope.addType = function(){
			// console.log($scope.category);
		categoryDataService.add($scope.category).then(function (response) {
            if (response!=null) {
                categoryDataService.all().then(function(categories){
				$scope.model.categories = categories;
				$scope.product.category_id = categories[categories.length - 1].id;
				$('#typeform')[0].reset();
				/*toastr.success('Category has been added successfully','Category Added');
				let lang = languageService.get('Category Added');
						console.log(lang);*/
						toastr.success(response.Message,'Success');
				$('#typeModal').modal('hide');
			});
            } else {
                toastr.error(response.Exception)
            }
        });
		}
		$scope.addNewType = function(){
			// console.log($scope.category);
			$scope.category.title_en = $scope.category.title_en;
			$scope.category.name = $scope.category.title_en;
			$scope.category.pos_display = 1;
			$scope.category.web_display = 1;

		categoryDataService.addNew($scope.category).then(function (response) {
            if (response!=null) {
                categoryDataService.all().then(function(categories){
				$scope.model.categories = categories;
				$scope.product.category_id = categories[categories.length - 1].id;
				$('#typeform')[0].reset();
				//toastr.success('Category has been added successfully','Category Added');
				let lang = languageService.get('Category Added');
						console.log(lang);
						toastr.success(lang.Value.Message,lang.Value.Title);
				$('#typeModal').modal('hide');
			});
            } else {
                toastr.error(response.Exception)
            }
        });
		}


		$scope.addBrand = function(){
			$scope.brand.name = $scope.brand.name;
			brandDataService.add($scope.brand).then(function(response){
				if (response != null) {
					brandDataService.all().then(function(brands){
						$scope.model.brands = brands;
						$scope.product.brand_id = brands[brands.length - 1].id;
						$('#brandform')[0].reset();
					/*toastr.success('Brand has been added successfully','Brand Added');
					let lang = languageService.get('Brand Added');
						console.log(lang);*/
						toastr.success(response.Message,'Success');
					$('#brandModal').modal('hide');
					});
				} else {
					toastr.error(response.Exception);
				}
			});
		}

		$scope.addNewBrand = function(){
			// console.log('assaddsd');
			$scope.brand.title_en = $scope.brand.title_en;
			$scope.brand.name = $scope.brand.title_en;
			brandDataService.add_new($scope.brand).then(function(response){
				// console.log(response);
				if (response != null) {
					brandDataService.all().then(function(brands){
						$scope.model.brands = brands;
						$scope.product.brand_id = brands[brands.length - 1].id;
						$('#brandform')[0].reset();
					//toastr.success('Brand has been added successfully','Brand Added');
					let lang = languageService.get('Brand Added');
						console.log(lang);
						toastr.success(lang.Value.Message,lang.Value.Title);
					$('#brandModal').modal('hide');
					});
				} else {
					toastr.error(response.Exception);
				}
			});
		}

		$scope.addSupplier = function(){
			$scope.model.suppliers = $scope.suppliers;
			supplierDataService.add($scope.supplier).then(function(response){
				if (response != null) {
					supplierDataService.all().then(function(suppliers){
						$scope.model.suppliers = suppliers;
						$scope.product.supplier_id = suppliers[suppliers.length - 1].id;
						$('#supplierform')[0].reset();
						/*toastr.success('Supplier has been added successfully','Supplier Added');
						let lang = languageService.get('Supplier Added');
						console.log(lang);*/
						toastr.success(response.Message,'Success');
						$('#supplierModal').modal('hide');
					});
				} else {
					toastr.error(response.Exception);
				}
			});
		}

		$scope.setProductType = function(){
				$('#typeModal').modal('show');
		}

		$scope.setBrand = function(){
				$('#brandModal').modal('show');
		}

		$scope.setSupplier = function(){
				$('#supplierModal').modal('show');
		}

	    $scope.getPhyCities = function(){
	        if ($scope.supplier.country != undefined) {
	            $scope.countryPhySelected = false;
	        }
	        if ($scope.supplier.country == undefined) {
	            $scope.countryPhySelected = true;
	        }

	        cityDataService.get_selected_cities($scope.supplier.country).then(function(city){
	            $scope.physical_cities = city;
	        });
	    }		

		$scope.uploadFiles = function(files, invalidFiles){

		if(invalidFiles[0] != undefined) {
			/*toastr.error('Image size cannot be greater than 1.5 MB', 'Warning', {timeOut : 3000});
			let lang = languageService.get('Image Size');
						console.log(lang);*/
						toastr.success(files.Message,'Success');
		}
	    $scope.files = files;
	        angular.forEach(files, function(file) {
	           file.upload = Upload.upload({
	               url: site_url('image/upload'),
	               data: { image: file }
	           });

	            file.upload.then(function (response) {

	               $timeout(function () {
	                  $scope.product.image = site_url(IMAGE_URL( response.data.path));
	               });
	            }, function (response) {

	               if (response.status > 0)
	                   $scope.errorMsg = response.status + ': ' + response.data;
	            }, function (evt) {

	               file.progress = Math.min(100, parseInt(100.0 * 
	                                        evt.loaded / evt.total));
	        	}).catch(function(error) {
	        		console.log(error);
	        	});
	    });
	}

		$scope.selectedTagChanged = function(tag){
			if(tag){
				addTagInArray(tag);
			}
		}

		$scope.findTag = function(txt){
			return tagDataService.find(txt);
		}

		$scope.removeTag = function(t){
			var index = $scope.tags.findIndex(function(tag){
			    return (tag.id == t.id);
			});
			$scope.tags.splice(index, 1);
		}

		function tagAlreadyAdd(id){
			var index = $scope.tags.findIndex(function(tag){
				return (tag.id == id);
			});
			return (index >= 0);
		}

		function addTagInArray(tag){
			$scope.tagSearchTxt = '';

			if(tagAlreadyAdd(tag.id)) 
				return;

			$scope.tags.push(tag);
		}
		
		// $scope.changedValue=function(tag){

		// // if(tagAlreadyAdd(tag)) 
		// // 	return;
		// for (var j = 0; j < tag.length; j++) {
		// 	if(tagAlreadyAdd(tag[j])) 
		// 		return;	
		// 	for (var i = 0; i <$scope.model.tags.length; i++) {
		// 		if (tag[j] == $scope.model.tags[i].id) {
		// 			temp_tag.id = $scope.model.tags[i].id;
		// 			temp_tag.name = $scope.model.tags[i].name;
	 //    			$scope.tags.push(temp_tag);
		// 		}
		// 	}
		// }
		// console.log(temp_tag)
    	
  //   	console.log($scope.tags);
  //   	} 
	/*Tag section end*/

	/*Product Autocomplete for composite products*/
		$scope.selectedProduct = null;
		$scope.namedCounter = 0;
		$scope.productSearchTxt = '';
		$scope.quantity = $scope.product_quantity;

		$scope.addCompositeProduct = function(p){
			console.log(p);
			if(p){
				let found = $scope.product.composite_products.filter(pro => {
					if(pro.sku == p.sku){
						return pro;
					}
				});

				if(found.length > 0){
					/*toastr.error('The item has already been added in list','Error');
					let lang = languageService.get('Item Exist');
							console.log(lang);*/
							toastr.success(p.Message,'Success');
				}else{
					$scope.GetAttribs(p);
					p.quantity = $scope.quantity;
					$scope.product.composite_products.push(p)
					$scope.selectedProduct = null;
					$scope.quantity = $scope.product_quantity;
				}
				$scope.mark_disabled = true;
			}
		}

		$scope.GetAttribs = function(item){
			let str = '';
			if(item.attribute_value_1.length > 2){
				str = item.attribute_value_1;
			}

			if(item.attribute_value_2.length > 2){
				str += ', '
				str += item.attribute_value_2;
			}

			if(item.attribute_value_3.length > 2){
				str += ', '
				str += item.attribute_value_3;
			}	
			item.attributes_data = str;
			return item;
		}

		$scope.findProduct = function(txt){
			return productDataService.find(txt);
		}

		$scope.ShowComposite = 0;

		$scope.findProductWithOutletAndSupplier = function(txt){
			if($scope.SupplierId == undefined || $scope.SupplierId == 0){
				/*toastr.error('Please select supplier first','Erorr');
				let lang = languageService.get('Select Supplier');
						console.log(lang);*/
						toastr.warning(txt.Message,'Error');
			}else if($scope.OutletId == undefined || $scope.OutletId == 0){
				/*toastr.error('Please select outlet first','Erorr');
				let lang = languageService.get('Select Outlet');
						console.log(lang);*/
						toastr.warning(txt.Message,'Erorr');
			}else{
				return productDataService.findWithOutletAndSupplier(txt,$scope.OutletId,$scope.SupplierId,$scope.ShowComposite);
			}
		}		

		$scope.findProductWithOutlet = function(txt){
			if($scope.OutletId > 0){
				return productDataService.findWithOutlet(txt,$scope.OutletId,$scope.ShowComposite);
			}else{
				/*toastr.error('Please select outlet first','Erorr');
				let lang = languageService.get('Select Outlet');
						console.log(lang);*/
						toastr.warning(txt.Message,'Error')
			}
		}

		$scope.verifyQuantity = function(product)
		{
			//productDataService.verify_quantity($scope.OutletId,product.product_id,product.quantity);
		}		

		$scope.removeProduct = function(p){
			var index = $scope.product.composite_products.findIndex(function(i){
			    return (i.product_variant_id == p.product_variant_id);
			});
			$scope.product.composite_products.splice(index, 1);
		}
	/*Product Autocomplete end*/

	/*
	* load page data i.e dropdowns data from server
	*/
	$scope.loadData = function(){
		brandDataService.all().then(function(brands){
				$scope.model.brands = brands;
			});

		supplierDataService.all().then(function(suppliers){
				$scope.model.suppliers = suppliers;
			});

		currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });

		productDataService.getByStoreId().then(function(product){
                $scope.model.related_products = product;
                $('.select2').select2();
            });

		tagDataService.all().then((tags) => { $scope.model.tags = tags; });

		categoryDataService.all().then(function(categories){
			$scope.model.categories = categories;
		});

		// outletDataService.get_all().then((outlets) => { $scope.outlets = outlets; });

		variantDataService.get_all().then(function(variant){
			// variant.current_sequence_number = parseInt(variant.current_sequence_number);
			// console.log(variant);
			_sku = variant.current_sequence_number;
			init_sku = variant.current_sequence_number;
			
			 $scope.default_sku = variant.current_sequence_number;
			 $scope.product.sku = $scope.default_sku;
			 $scope.product.sku_by_name = ( parseInt(variant.sku_generation) === 0 );
			 $scope.product.sku_custom = false;
			 $scope.product.sku_name = ( parseInt(variant.sku_generation) === 0 );
			 $scope.product.sku_number = ( parseInt(variant.sku_generation) === 1 );

			let id = new URL(window.location.href).pathname.split('/').pop();

			let pid = id.match(/\d+/g);

			if(pid != null){

				let product_id = pid.map(Number)[0];

				if(typeof product_id === 'number'){
					productDataService.get(product_id)
						.then(function(product){
							if(product.sku_type == 'custom'){
								_sku = product.prefix;
								init_sku = product.prefix;

								$scope.default_sku = product.prefix;
								$scope.product.sku = product.prefix;
								$scope.product.sku_by_name = false;
								$scope.product.sku_custom = true;
								$scope.product.sku_name = false;
								$scope.product.sku_number = false;
							}

							$scope.product.handle = product.name.replace(/\s/g,'');
							$scope.product.prefix = product.name.replace(/\s/g,'').toLowerCase();
				 		});		
				}

			}
		});


		storeDataService.get()
	 		.then(function(store){
	 			$scope.outlets = store.outlets;
	 			$scope.languages = store.languages;

	 			$scope.languages.map(lang => {
					lang.attribute_1 = '';
					lang.attr1_values = [];
					lang.attribute_2 = '';
					lang.attr2_values = [];
					lang.attribute_3 = '';
					lang.attr3_values = [];
					return lang;
	 			});
	 				
	 			console.log($scope.languages);
	 		});

   		$scope.getCurrency();
	}

	$scope.loadNewData = function(){
		brandDataService.all().then(function(brands){
				$scope.model.brands = brands;
			});

		supplierDataService.all().then(function(suppliers){
				$scope.model.suppliers = suppliers;
			});

		currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });
	}




	$scope.setCustomSku = function()
	{
		$scope.product.sku_custom = true;
		$scope.product.sku_number = false;
		$scope.product.sku_name	 = false;	
	}

	$scope.setValue = function()
	{
		if($scope.product.sku_number){
			_sku = init_sku;
			$scope.product.sku = $scope.default_sku;
			$scope.product.sku_custom = false;
	        $scope.product.variants.map((item) => {
	        	item.sku = ( $scope.product.sku_number && !$scope.product.sku_custom ? _sku++ : '' ),
	        	item.editable = false;
	        	return item;
	        });			
		}else if($scope.product.sku_name){
			$scope.namedCounter = 1;
			$scope.product.sku = $scope.product.default_sku_name;
			$scope.product.sku_custom = false;
	        $scope.product.variants.map((item) => {
	        	item.sku = ( $scope.product.sku_name && !$scope.product.sku_custom ? $scope.getNamedSku() : '' ),
	        	item.editable = false;
	        	return item;
	        });				
		}else{
	        $scope.product.variants.map((item) => {
	        	item.editable = true;
	        	item.sku = '';
	        	return item;
	        });				
		}
	}

	$scope.removeVariant = function(variant){
		var index = $scope.product.variants.findIndex(function(v){
		    return (v.attribute_value_1 == variant.attribute_value_1
		    		&& v.attribute_value_2 == variant.attribute_value_2
		    		&& v.attribute_value_3 == variant.attribute_value_3);
		});
		$scope.product.variants.splice(index, 1);
	}

	$scope.addProduct = function(product){
		
		if($('#productform')[0].checkValidity()) {
			//$scope.product.description = $('#product_description').html().replace(/<[^>]+>/g, '').replace('&nbsp;',' ').trim();
			$scope.product.tags = $scope.tags;
			var str = $scope.product.prefix;
			// var str = str.toLowerCase();
			// var str = str.slice(0, 3);
			
			//$scope.product.sku = str+'-'+String(_sku);

			if(image_data == 0){
				$scope.product.product_images = [];
				// toastr.error('Product image is required','Error');
				// return false;
			}else{
				$scope.product.product_images = image_data;
			}

			$scope.product.description = CKEDITOR.instances.product_description.getData();

			// if ($scope.product.description.length > 0) {	
			// }
			
			productDataService.add($scope.product)
				.then(function(response){
					  if (response != null) {
						/*toastr.success('Product has been added successfully','Product Added');
						let lang = languageService.get('Product Added');
						console.log(lang);*/
						toastr.success(response.Message,'Success');
						setTimeout(function(){window.location.href = BASE_URL + 'product';},2000);
					}
					else {
		            toastr.error(response.Exception);
		        	}
				});
		}		
	}

	/*
	* This function Generates Vairants 
	* by mapping all attributes 
	* possible values
	*/
	$scope.generateVariants = function(){

		

		$scope.product.variants = [];
		if($scope.product.attr1_values.length == 0 )
			return;
		_sku = init_sku;

		// if(init_sku != $scope.product.sku){
		// 	_sku = $scope.product.sku;
		// }
		for(var i = 0; i < $scope.product.attr1_values.length; i++ ){
			var variant_1_value = $scope.product.attr1_values[i];
			if($scope.product.attr2_values.length == 0){
				$scope.product.variants.push(makeVariant(variant_1_value))
				continue;
			}

			for(var j = 0; j < $scope.product.attr2_values.length; j++ ){
				var variant_2_value = $scope.product.attr2_values[j];
				if($scope.product.attr3_values.length == 0){
					$scope.product.variants.push(makeVariant(variant_1_value, variant_2_value));
					continue;
				}

				for(var k = 0; k < $scope.product.attr3_values.length; k++ ){
					var variant_3_value = $scope.product.attr3_values[k];
					$scope.product.variants.push(makeVariant(variant_1_value, variant_2_value, variant_3_value));
				}
			}
		}

		$scope.setValue();
	}

	/*simply create and return new variant object*/
	function makeVariant(attr1, attr2, attr3){
		console.log(_sku);
		// var prefix_sku = $scope.product.prefix+'-'+_sku;
		//_sku++;
		return {
			supplier_price 		: $scope.product.supplier_price,
			markup 				: $scope.product.markup,
			retail_price 		: $scope.product.retail_price || 0,
			before_discount_price 		: $scope.product.before_discount_price,
			sku 				: '',//( $scope.product.sku_number && !$scope.product.sku_custom ? _sku++ : $scope.getNamedSku() ),
			supplier_code 		: $scope.product.supplier_code,
			custom_loyalty      : $scope.product.custom_loyalty,
			//quantity            : $scope.product.quantity,
			//quantity            : 1,
			outlets 			: [...$scope.outlets.map(item => {
				item.retail_price = 0;
				item.supply_price = 0;
				return {...item}
			})],
			attribute_value_1 	: attr1,
			attribute_value_2 	: attr2,
			attribute_value_3 	: attr3,
			allow_out_of_stock 	: true,
			is_active 	: true,
		}
	}

	$scope.calculateDiff = function(v,o){
		if(v.retail_price == 0 || o.supply_price == 0){
			return 0;
		}
		let num = v.retail_price - o.supply_price;
		return (num) ?  num.toFixed(2) : 0;
	}

	$scope.calculateMargin = function(v,o){
		if(v.retail_price == 0 || o.supply_price == 0){
			return 0;
		}
		let diff = v.retail_price - o.supply_price;
		let diffs = diff / v.retail_price * 100;
		return ((diffs) ?  diffs.toFixed(2) : 0) + '%';
	}

	$scope.getNamedSku = function()
	{
		let sku = `${$scope.product.sku}-${$scope.namedCounter}`;
		$scope.namedCounter++;
		return sku;
	}

    /*watchers*/
	$scope.$watch('product.supplier_price', function (newVal, oldVal) {
	    if (newVal != oldVal) {
	        $scope.product.retail_price = $scope.product.supplier_price;
	    }
	});
	$scope.$watch('product.supplier_price', function (newVal, oldVal) {
	    if (newVal != oldVal) {
	        $scope.product.retail_price = $scope.product.supplier_price;
	    }
	});

	$scope.$watch('product.sku_custom', function (newVal, oldVal) {
	    if (newVal) {
	        $scope.product.sku = '';
	        $scope.product.variants.map((item) => {
	        	item.sku = '';
	        	item.editable = true;
	        	return item;
	        });
	    }
	});	

	
	// $scope.$watch('product.markup', function(newVal, oldVal){
	// 	if(newVal != oldVal){
	// 		//calculate retail price
	// 		var c = parseFloat($scope.product.supplier_price) * (parseFloat(newVal)/100);
	// 		$scope.product.retail_price = parseFloat($scope.product.supplier_price) + parseFloat(c);
	// 	}
	// });

	$scope.$watch('product.retail_price', function(newVal, oldVal){
		if(newVal != oldVal){
			//calculate mark up
			var c = (($scope.product.retail_price - $scope.product.supplier_price)/$scope.product.supplier_price)*100;
			$scope.product.markup = parseFloat(c);
		}
	});

	$scope.$watch('product.attr1_values', function(newVal, oldVal){
		if(newVal != oldVal)
			$scope.languages.map((lang,index) => {
				lang.attr1_values = [];
				if(index == 0){
					lang.attribute_1 = $scope.product.attribute_1;
					newVal.map(val => {
						lang.attr1_values.push({ key : val , value : val });
					});
				}else{
					lang.attribute_1 = '';
					newVal.map(val => {
						lang.attr1_values.push({ key : val , value : '' });
					});					
				}
				
				return lang;
			});
			
			$scope.generateVariants();
	}, true);
	
	$scope.$watch('product.attr2_values', function(newVal, oldVal){
		if(newVal != oldVal)
			$scope.languages.map((lang,index) => {
				lang.attr2_values = [];
				if(index == 0){
					lang.attribute_2 = $scope.product.attribute_2;
					newVal.map(val => {
						lang.attr2_values.push({ key : val , value : val });
					});
				}else{
					lang.attribute_2 = '';
					newVal.map(val => {
						lang.attr2_values.push({ key : val , value : '' });
					});					
				}
				
				return lang;
			});			
			$scope.generateVariants();
	}, true);

	$scope.$watch('product.attr3_values', function(newVal, oldVal){
		if(newVal != oldVal)
			$scope.languages.map((lang,index) => {
				lang.attr3_values = [];
				if(index == 0){
					lang.attribute_3 = $scope.product.attribute_3;
					newVal.map(val => {
						lang.attr3_values.push({ key : val , value : val });
					});
				}else{
					lang.attribute_3 = '';
					newVal.map(val => {
						lang.attr3_values.push({ key : val , value : '' });
					});					
				}
				
				return lang;
			});			
			$scope.generateVariants();
	}, true);

	$scope.$watch('product.name', function(newVal, oldVal){
		if(!newVal){
			$scope.product.handle = '';
			return;
		}
		$scope.product.handle = newVal.replace(/\s/g,'');
	});

	$scope.$watch('product.name', function(newVal, oldVal){
		if(!newVal){
			$scope.product.prefix = '';
			return;
		}
		$scope.product.prefix = newVal.replace(/\s/g,'-');
		$scope.product.prefix = $scope.product.prefix.toLowerCase();
		$scope.setValue();
	});

	$scope.$watch('product.prefix', function(newVal, oldVal){
		var prefix_sku;
		prefix_sku = newVal.replace(/\s/g,'-');
		$scope.product.default_sku_name = prefix_sku;
		if($scope.product.sku_by_name){
			$scope.product.sku = prefix_sku;
		}
	});
	
	$scope.ValuePrefix = function (value) {	
		$scope.product.sku = init_sku;
	    $scope.product.sku = value+'-'+$scope.product.sku;
	}

	/*watchers end*/
    $scope.ValueChange = function (value,item) {
        if (item.markup > 0) {
            let c = parseFloat(value) * (item.markup / 100);
            item.retail_price = parseFloat(value) + parseFloat(c);
	    } else {
            item.retail_price = value;
	    }
	}


	// $scope.MarkupChange = function (markup, item) {
	//     if (item.markup > 0) {
	//         let c = parseFloat(item.supplier_price) * (item.markup / 100);
	//         item.retail_price = parseFloat(item.supplier_price) + parseFloat(c);
	//     }
	// }	

	$scope.MarkupChange = function (retail_price, item) {
	    if (item.retail_price > 0) {
		
			var c = ((item.retail_price - item.supplier_price)/item.supplier_price)*100;
			item.markup = parseFloat(c);
	        // let c = parseFloat(item.supplier_price) * (item.markup / 100);
	        // item.retail_price = parseFloat(item.supplier_price) + parseFloat(c);
	    }
	}

	$scope.Save = function(product){
		product.languages = [...$scope.languages ];
		// console.log(product);
		// return;
		productDataService.add(product)
				.then(function(response){
					console.log(response);
					if(response != null){

                        toastr.success(response.Message,'Success');
                        setTimeout(()=>{
                            window.location.href = site_url('product');
                        },3000);
                      }
				});
	}

	$scope.validateAttribute = function(index){
		if(index == 1){
			if($scope.product.attribute_1 == ''){
				toastr.error('Attribute is required','Error');
				return false;
			}

			if($scope.product.attr1_values == ''){
		        toastr.error('One or more values are required','Error');
		        return false;
			}
		}

		if(index == 2){
			if($scope.product.attribute_2 == ''){
				toastr.error('Attribute is required','Error');
				return false;
			}

			if($scope.product.attr2_values == ''){
		        toastr.error('One or more values are required','Error');
		        return false;
			}
		}

		if(index == 3){
			if($scope.product.attribute_3 == ''){
				toastr.error('Attribute is required','Error');
				return false;
			}

			if($scope.product.attr3_values == ''){
		        toastr.error('One or more values are required','Error');
		        return false;
			}
		}

	    $('#modal-' + index).modal('show');
	}
})