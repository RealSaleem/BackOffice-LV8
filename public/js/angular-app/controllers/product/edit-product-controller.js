posApp.controller('productCtrl', function($scope, $sce, $rootScope, categoryDataService,tagDataService, supplierDataService, brandDataService, productDataService, Upload, $timeout ,storeDataService,cityDataService){
  	
  	//$scope.filters = { status: 0 };
	$scope.model = {};
	$scope.product = {};
 	$scope.product.product_variants = [];
	$scope.product_id = null;
	$scope.hasVariants = true;
	$scope.removed_tags = [];
	$scope.remove_composite_products = [];
	/*Tag section*/
	$scope.selectedTag = '';
	$scope.tagSearchTxt = '';
	$scope.tags = [];
	$scope.currentSequenceNumber = null;
	$scope.custom_sku = 0;
	$scope.sku_custom = false;
    $scope.sku_name = false;
    $scope.sku_number = false;
    $scope.default_sku_name = null;

	$scope.init = function(){
		$scope.totalStock = 0;
		$scope.totalStockValue = 0;

		$scope.getCurrency();

		$scope.getDecimal();
	
		inventoryReportDataService.get_all().then(function(inventory){

			for (var i = 0; i<inventory.length; i++) {

				inventory[i].quantity = (inventory[i].quantity == null) ? 0 : inventory[i].quantity;
				$scope.totalStock = $scope.totalStock + parseInt(inventory[i].quantity);
				$scope.totalStockValue = $scope.totalStockValue + (inventory[i].supplier_price * parseInt(inventory[i].quantity));
				
				inventory[i].porductWithVariants = inventory[i].product.name;
				if ( inventory[i].attribute_value_1 !== '') {
					inventory[i].porductWithVariants = inventory[i].porductWithVariants + '/' + inventory[i].attribute_value_1;
				}

				if ( inventory[i].attribute_value_2 !== '' ) {
					inventory[i].porductWithVariants = inventory[i].porductWithVariants + '/' + inventory[i].attribute_value_2;
				}

				if ( inventory[i].attribute_value_3 !== '') {
					inventory[i].porductWithVariants = inventory[i].porductWithVariants + '/' + inventory[i].attribute_value_3;
				}
			}
				//console.log(inventory);

			 	$scope.inventoryData = inventory;  
		})	
	}

	$scope.setLanguage = function(lang,ley){
		$('.lang-title').html(lang);
		CKEDITOR.replace('product_description_lang');		
		$('#product_lang').val(ley);
	}

	var init_sku = 0;
		
		$scope.addTag = function (name) {

		    tagDataService.add({ name: name })
				.then(function(tag){
					addTagInArray(tag);
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

		$scope.addType = function(){
			//// console.log($scope.category);
		categoryDataService.add($scope.category).then(function (response) {
            if (response!=null) {
                categoryDataService.all().then(function(categories){
				$scope.model.categories = categories;
				$scope.product.category_id = categories[categories.length - 1].id;
				$('#typeform')[0].reset();
				toastr.success('Category has been added successfully','Category Added');
				$('#typeModal').modal('hide')
			});
            } else {
                toastr.error(response.Exception)
            }
        });
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

		$scope.addBrand = function(){
			$scope.product.brand_name = $scope.brand_name;
			
			brandDataService.add($scope.brand).then(function(response){
				if (response != null) {
					brandDataService.all().then(function(brands){
						$scope.model.brands = brands;
						$scope.product.brand_id = brands[brands.length - 1].id;
						$('#brandform')[0].reset();
						toastr.success('Brand has been added successfully','Brand Added');
						$('#brandModal').modal('hide');
					});
				} else {
					toastr.error(response.Exception);

				}
			});
		}

		$scope.addSupplier = function(){
			supplierDataService.add($scope.supplier).then(function(response){
				if (response != null) {
					supplierDataService.all().then(function(suppliers){
						$scope.model.suppliers = suppliers;
						$scope.product.supplier_id = suppliers[suppliers.length - 1].id;
						$('#supplierform')[0].reset();
						toastr.success('Supplier has been added successfully','Supplier Added');
						$('#supplierModal').modal('hide');
					});
				} else {
					toastr.error(response.Exception);
				}
			});
		}
	$scope.updateStatus = function(){
		productDataService.updateStatus($scope.product.id, $scope.product.has_variant)
			.then(function(success){
				//// console.log('x')
		
				
			});
	}
	$scope.updateStatus1 = function($event, p){
			//// console.log('aaaa');
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
			var removed = $scope.tags.splice(index, 1);
			$scope.removed_tags.push(removed[0]);
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
	/*Tag section end*/

	/*Product Autocomplete for composite products*/
		$scope.selectedProduct = null;
		$scope.productSearchTxt = '';
		$scope.quantity = 1;
		$scope.addCompositeProduct = function(p){

			if(p){
				let found = $scope.product.composite_products.filter(pro => {
					if(pro.sku == p.sku){
						return pro;
					}
				});

				if(found.length > 0){
					toastr.error('The item has already been added in list','Error');
					$scope.selectedProduct = null;
					$scope.quantity = 1;					
				}else{
					p.quantity = $scope.quantity;
					$scope.product.composite_products.push(p)
					$scope.selectedProduct = null;
					$scope.quantity = $scope.product_quantity;
				}
			}


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
		
		function compositeAlreadyAdded(id){
			var index = $scope.product.composite_products.findIndex(function(cp){
				return (cp.product_variant_id == id);
			});
			return (index >= 0);
		}

		$scope.findProduct = function(txt){
			return productDataService.find(txt);			
		}

		$scope.removeProduct = function(p){
			
			let found = $scope.product.composite_products.filter(pro => {
				if(pro.id != p.id){
					return pro;
				}
			});

			$scope.product.composite_products = [];
			$scope.product.composite_products = found;
		}
	/*Product Autocomplete end*/

	/*
	* load page data i.e dropdowns data from server
	*/
	$scope.loadData = function(){

		brandDataService.all().then(function(brands){
				$scope.model.brands = brands;
				$scope.product.brand_id = $scope.model.brands[0].id;
			});

		supplierDataService.all().then(function(suppliers){
				$scope.model.suppliers = suppliers;
				$scope.product.supplier_id = $scope.model.suppliers[0].id;
			});

		tagDataService.all().then(function(tags){
				$scope.model.tags = tags;
			});

		categoryDataService.all().then(function(categories){
			$scope.model.categories = categories;
			$scope.product.category_id = $scope.model.categories[0].id;
		});

		productDataService.getByStoreId().then(function(product){
                $scope.model.related_products = product;
                $('.select2').select2();
            });		

		productDataService.get($scope.product_id)
			.then(function(product){
				//console.log(product);
				$scope.product = product;
				//$scope.product.product_images = '';
				// p_images = $scope.product.product_images;
				// $scope.product.has_variant=$scope.product.active == 1 ? true:false;
				// $scope.tags = product.tags;
				////console.log($scope.product);
				// $scope.tags = product.product_tags.map(function(t){
				// 	return { 
				// 		product_tag_id : t.id, 
				// 		id : t.tag_id,
				// 		name : t.tag.name
				// 	};
				// });


				//CKEDITOR.instances.product_description.setData(product.description);
				$('#product_description').html(product.description);
				CKEDITOR.replace('product_description');

				$scope.product.default_sku_name = $scope.product.name.replace(/ /g, '-').toLowerCase();
				$scope.has_variants = ( product.product_variants.length >= 1 );
				$scope.product.composite_products = product.composite_products.map(function(cp){
					return { 
						id : cp.id, 
						name : cp.product_variant.product.name, 
						quantity : cp.quantity,
						attribute_value_1 : cp.product_variant.attribute_value_1,
						attribute_value_2 : cp.product_variant.attribute_value_2,
						attribute_value_3 : cp.product_variant.attribute_value_3,
						sku : cp.product_variant.sku
					};
				});

				$scope.removed_tags = [];
				$scope.remove_composite_products = [];
				
				$scope.product.attr1_values = [];
				$scope.product.attr2_values = [];
				$scope.product.attr3_values = [];

				init_sku = $scope.product.product_variants[$scope.product.product_variants.length - 1].sku;
				$scope.custom_sku = $scope.product.product_variants[$scope.product.product_variants.length - 1].sku;
				$scope.name_counter = $scope.product.product_variants.length;
				init_sku = init_sku.split("-");
				init_sku = parseInt(init_sku[1]) + 1;
				$scope.total_variants = $scope.product.product_variants.length;
				//console.log(init_sku);

				if($scope.product.sku_type == 'custom'){
					$scope.product.sku_custom = true;
					$scope.product.sku_name = false;
					$scope.product.sku_number = false;

				}else if($scope.product.sku_type == 'name'){
					$scope.product.sku_name = true;
					$scope.product.sku_custom = false;
					$scope.product.sku_number = false;
				}else{
					$scope.product.sku_number = true;
					$scope.product.sku_custom = false;
					$scope.product.sku_name = false;
				}

				$scope.product.related_id = [];

				if($scope.product.related.length > 0){
					$scope.product.related.map((product) => {
						$scope.product.related_id.push(product.id);
					})
				}
			}).then(initFileUpload);

			storeDataService.get()
	 		.then(function(store){
	 			$scope.currentSequenceNumber = store.current_sequence_number;

				if(localStorage.getItem('currency') === null){
		 			localStorage.setItem('currency',store.default_currency);
		 			$rootScope.currency = localStorage.getItem('currency');
	        	}else{
	        		$rootScope.currency = localStorage.getItem('currency');
	        	}
	 		});

	}

	$scope.ValuePrefix = function (value) {
	for (var i = 0; i < $scope.product.product_variants.length; i++) {
		let sku_num = $scope.product.product_variants[i].sku.split('-');
            init_sku = sku_num[1];
		$scope.product.product_variants[i].sku = value+"-"+init_sku;
		}
	}

	$scope.uploadFiles = function(files, invalidFiles){

		if(invalidFiles[0] != undefined) {
			toastr.error('Image size cannot be greater than 1.5 MB', 'Warning', {timeOut : 3000});
		}
	    $scope.files = files;
	        angular.forEach(files, function(file) {
	           file.upload = Upload.upload({
	               url: site_url('image/upload'),
	               data: { image: file }
	           });

	            file.upload.then(function (response) {

	               $timeout(function () {
	                  $scope.product.image = site_url('storage/' + response.data.path);
	               });
	            }, function (response) {

	               if (response.status > 0)
	                   $scope.errorMsg = response.status + ': ' + response.data;
	            }, function (evt) {

	               file.progress = Math.min(100, parseInt(100.0 * 
	                                        evt.loaded / evt.total));
	        	}).catch(function(error) {
	        		//console.log(error);
	        	});
	    });
	}

	$scope.removeVariant = function(variant){
		//console.log(variant);
		var index = $scope.product.variants.findIndex(function(v){
		    return (v.attribute_value_1 == variant.attribute_value_1
		    		&& v.attribute_value_2 == variant.attribute_value_2
		    		&& v.attribute_value_3 == variant.attribute_value_3);
		});
		$scope.product.variants.splice(index, 1);
	}

	$scope.saveProduct = function(){

		//var image_data defined in edit-new.blade.php
		if(image_data == 0){
			$scope.product.product_images = [];
			// toastr.error('Product image is required','Error');
			// return false;
		}else{
			$scope.product.product_images = image_data;
		}

		//$scope.product.description = $('#product_description').html().replace(/<[^>]+>/g, '').replace('&nbsp;',' ').trim();
		$scope.product.description = CKEDITOR.instances.product_description.getData();

		$scope.product.tags = $scope.tags;
		$scope.product.removed_tags = $scope.removed_tags;
		$scope.product.remove_composite_products = $scope.remove_composite_products;
		productDataService.edit($scope.product)
			.then(function(response){
				toastr.success('Product has been updated successfully', 'Update Product');
			    setTimeout(() => {
			        window.location.href = BASE_URL + "product";
			    },2000)
			});
		
			// .catch(function(response){
			// 	toastr.error(response.Exception);
			// })
	}

	$scope.calculate_margin = function(v){
		var c = parseFloat(v.retail_price) - parseFloat(v.supplier_price);
		c= (c*100)/parseFloat(v.supplier_price);
		v.markup = c;

	}
	/*watchers*/

	// $scope.$watchCollection('product.product_variants[]', function(newVal, oldVal){
	//// 	//console.log(newVal)
	// 	if(newVal && oldVal && newVal.markup != oldVal.markup){
	// 		//calculate retail price
	// 	}
	// }, true);

	/*
	* This function Generates Vairants 
	* by mapping all attributes 
	* possible values
	*/
	function generateVariants(){
		//console.log($scope.product.attr1_values);
		$scope.product.variants = [];
		if($scope.product.attr1_values.length == 0 )
			return;
		_sku = init_sku;

		// if(init_sku != $scope.product.sku){
		// 	_sku = $scope.product.sku;
		// }

		$scope.counter = $scope.currentSequenceNumber;
		$scope.counter++;
		$scope.name_counter = $scope.product.product_variants.length;
		$scope.custom_sku = $scope.product.product_variants[$scope.product.product_variants.length - 1].sku;

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
		//console.log($scope.product.variants);
	}

	/*simply create and return new variant object*/
	function makeVariant(attr1, attr2, attr3){
		//// console.log(_sku);
		// var prefix_sku = $scope.product.prefix+'-'+_sku;
		//_sku++;

		let variant = {
			//supplier_price 		: $scope.product.supplier_price,
			supplier_price      : $scope.product.product_variants[$scope.product.product_variants.length - 1].supplier_price,
			markup 				: $scope.product.markup,
			retail_price 		: $scope.product.product_variants[$scope.product.product_variants.length - 1].retail_price,
			markup              : (($scope.product.product_variants[$scope.product.product_variants.length - 1].retail_price 
								 - $scope.product.product_variants[$scope.product.product_variants.length - 1].supplier_price)
								 *100)/$scope.product.product_variants[$scope.product.product_variants.length - 1].supplier_price,
			sku 				: '',
			//sku 				: ( $scope.product.sku_number && !$scope.product.sku_custom ? _sku++ : $scope.getNamedSku() ),
			supplier_code 		: $scope.product.supplier_code,
			custom_loyalty      : $scope.product.custom_loyalty,
			quantity            : $scope.product.quantity,
			quantity            : 50,

			attribute_value_1 	: attr1,
			attribute_value_2 	: attr2,
			attribute_value_3 	: attr3,
			before_discount_price : $scope.product.product_variants[$scope.product.product_variants.length - 1].before_discount_price,
		}


		return variant;
	}

	$scope.setValue = function()
	{
		if($scope.product.sku_number){
			_sku = $scope.currentSequenceNumber;
			$scope.product.sku = $scope.default_sku;
			$scope.product.sku_custom = false;
	        $scope.product.variants.map((item) => {
	        	item.sku = _sku++,
	        	item.editable = false;
	        	return item;
	        });
		}else if($scope.product.sku_name){
			$scope.namedCounter = 1;

			if($scope.total_variants > 0){
				$scope.namedCounter += $scope.total_variants;
			}

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

		//console.log($scope.product.sku_custom);
	}	
	
	/*
	$scope.getSku = function()
	{
		if($scope.product.sku_type == 'number'){
			let sku = angular.copy($scope.counter);
			//$scope.counter++;
			return sku;
		}else if($scope.product.sku_type == 'name')
		{
			let sku = $scope.product.name.replace(/ /g, '-').toLowerCase();
			sku = `${sku}-${$scope.name_counter}`;
			$scope.name_counter++;
			return sku;
		}else{
			$scope.custom_sku = $scope.custom_sku.replace(/\d+$/, function(n){  return ++n }); 
			return $scope.custom_sku;
		}
	}
	*/

	$scope.getNamedSku = function()
	{
		return `${$scope.product.sku}-${$scope.namedCounter++}`;
	}	


	$scope.$watch('product.attr1_values', function(newVal, oldVal){
		if(newVal != oldVal)
			generateVariants();
	}, true);
	
	$scope.$watch('product.attr2_values', function(newVal, oldVal){
		if(newVal != oldVal)
			generateVariants();
	}, true);

	$scope.$watch('product.attr3_values', function(newVal, oldVal){
		if(newVal != oldVal)
			generateVariants();
	}, true);

	/*watchers end*/
	$scope.ValueChange = function (value,item) {
        if (item.markup > 0) {
            let c = parseFloat(value) * (item.markup / 100);
            item.retail_price = parseFloat(value) + parseFloat(c);
	    } else {
            item.retail_price = value;
	    }
	}

	$scope.updateVariantStatus = function($event, v){
		$event.stopPropagation();
		//toggle status
		v.is_active = !v.is_active;
		productDataService.updateVariantStatus(v.id, v.is_active)
			.then(function(success){
			});
	}

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
	
	// $scope.$watch('product.markup', function(newVal, oldVal){
	// 	if(newVal != oldVal){
	// 		//calculate retail price
	// 		var c = parseFloat($scope.product.supplier_price) * (newVal/100);
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

	$scope.MarkupChange = function (retail_price, item) {

	    if (item.retail_price > 0) {
		//console.log(retail_price);
		//console.log(item);
			var c = ((item.retail_price - item.supplier_price)/item.supplier_price)*100;
			item.markup = parseFloat(c);
	        // let c = parseFloat(item.supplier_price) * (item.markup / 100);
	        // item.retail_price = parseFloat(item.supplier_price) + parseFloat(c);
	    }
	}	
})