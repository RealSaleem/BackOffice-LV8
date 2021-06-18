posApp.controller('productCtrl', function($scope, $sce, $rootScope, variantDataService, categoryDataService,tagDataService, supplierDataService, brandDataService, productDataService, Upload, $timeout ,storeDataService,languageService){
  	
  	//$scope.filters = { status: 0 };
	$scope.model = {};
	$scope.product = {};
	$scope.product_id = null;
	$scope.hasVariants = true;
	$scope.deleted_var = false;

	$scope.removed_tags = [];
	$scope.product.product_variants = [];

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
	$scope.watch_attr1_values = false;
	$scope.watch_attr2_values = false;
	$scope.watch_attr3_values = false;

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
				//toastr.success('Category has been added successfully','Category Added');
				let lang = languageService.get('Category Added');
						console.log(lang);
						toastr.success(lang.Value.Message,lang.Value.Title);
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
			supplierDataService.add($scope.supplier).then(function(response){
				if (response != null) {
					supplierDataService.all().then(function(suppliers){
						$scope.model.suppliers = suppliers;
						$scope.product.supplier_id = suppliers[suppliers.length - 1].id;
						$('#supplierform')[0].reset();
						//toastr.success('Supplier has been added successfully','Supplier Added');
						let lang = languageService.get('Supplier Added');
							console.log(lang);
							toastr.success(lang.Value.Message,lang.Value.Title);
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
					//toastr.error('The item has already been added in list','Error');
					let lang = languageService.get('Item Exist');
						console.log(lang);
						toastr.success(lang.Value.Message,lang.Value.Title);				
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

		// brandDataService.all().then(function(brands){
		// 		$scope.model.brands = brands;
		// 		$scope.product.brand_id = $scope.model.brands[0].id;
		// 	});

		// supplierDataService.all().then(function(suppliers){
		// 		$scope.model.suppliers = suppliers;
		// 		$scope.product.supplier_id = $scope.model.suppliers[0].id;
		// 	});

		// tagDataService.all().then(function(tags){
		// 		$scope.model.tags = tags;
		// 	});

		// categoryDataService.all().then(function(categories){
		// 	$scope.model.categories = categories;
		// 	$scope.product.category_id = $scope.model.categories[0].id;
		// });

		// productDataService.getByStoreId().then(function(product){
  //               $scope.model.related_products = product;
  //               $('.select2').select2();
  //           });	

  		setTimeout(()=>{
  			$('.attibutes-container').find('input').prop('class','form-control');
  		},500);

        let id = new URL(window.location.href).pathname.split('/').pop();

		let pid = id.match(/\d+/g);

		if(pid != null){
			let product_id = pid.map(Number)[0];
			if(typeof product_id === 'number'){
		        variantDataService.get_variant_edit(product_id).then(function(data){
		        $scope.product = data.product;
		        $scope.product.has_variants = true;
		        $scope.product.product_variants		 =[...data.product.variants.map(item =>{
		        	// console.log(item);
		        	item.is_deleted = 0;
		        	item.is_new = 0;
		        	return item;
		        })];
		        $scope.languages = [];
		        $scope.product.attr1_values = [...data.product.attr1_values];
		        $scope.product.attr2_values = [...data.product.attr2_values];
		        $scope.product.attr3_values = [...data.product.attr3_values];

		        $scope.product.attribute_1 = data.product.attribute_1;
		        $scope.product.attribute_2 = data.product.attribute_2;
		        $scope.product.attribute_3 = data.product.attribute_3;
		        
		        if(data.languages != null && data.languages.length > 0){
		        	
			        data.languages.map(item=>{
			        	let lang = {
			        		...item,
			        		attr1_values: [...item.attr1_values],
			        		attr2_values: [...item.attr2_values],
			        		attr3_values: [...item.attr3_values]
			        	}
			        	$scope.languages.push(lang);
			        })
		        }
		        // console.log(data.product);

					if($scope.product.sku_type == 'custom'){
						$scope.product.prefix = data.product.prefix;
					}else{
						$scope.product.prefix = data.product.variants[0].sku;
					}

					setTimeout(()=>{
						$scope.watch_attr1_values = true;
						$scope.watch_attr2_values = true;
						$scope.watch_attr3_values = true;
					},1000);
					
				});	
			}

		}

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
								// console.log(product);
								// console.log(init_sku);
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
	 			$scope.currentSequenceNumber = store.current_sequence_number;
	 			
	 	});
	}
	$scope.Upadte = function(product){
	product.languages = [...$scope.languages ];
	$scope.product.variants = [];
	$scope.product.variants = $scope.product.product_variants;

	console.log(product);
	// return;
	variantDataService.update(product)
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

	// $scope.ValuePrefix = function (value) {
	// for (var i = 0; i < $scope.product.product_variants.length; i++) {
	// 	let sku_num = $scope.product.product_variants[i].sku.split('-');
 //            init_sku = sku_num[1];
	// 	$scope.product.product_variants[i].sku = value+"-"+init_sku;
	// 	}
	// }
	$scope.ValuePrefix = function (value) {	
		$scope.product.sku = init_sku;
	    $scope.product.sku = value+'-'+$scope.product.sku;
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
	                  $scope.product.image = site_url(IMAGE_URL(response.data.path));
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
		variant.is_deleted = 1;
		// 
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
				//toastr.success('Product has been updated successfully', 'Update Product');
					let lang = languageService.get('Product Updated');
						console.log(lang);
						toastr.success(lang.Value.Message,lang.Value.Title);
			    setTimeout(() => {
			        window.location.href = BASE_URL + "product";
			    },2000)
			});
		
			// .catch(function(response){
			// 	toastr.error(response.Exception);
			// })
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
		// console.log($scope.product.attr1_values);
		let variant = [];
		if($scope.product.attr1_values == undefined || $scope.product.attr1_values.length == 0 )
			return;
		_sku = init_sku;

		
		$scope.counter = $scope.currentSequenceNumber;
		$scope.counter++;
		$scope.name_counter = $scope.product.product_variants.length;
		$scope.custom_sku = $scope.product.product_variants[$scope.product.product_variants.length - 1].sku;
		// console.log($scope.name_counter);
		// console.log($scope.custom_sku);
		/*
		*/

			let matcher = '';
		for(let i = 0; i < $scope.product.attr1_values.length; i++ ){
			matcher = `${$scope.product.attr1_values[i]}`;
			


			if($scope.product.attr2_values.length == 0){
				variant.push(matcher)
				continue;
			}

			for(let j = 0; j < $scope.product.attr2_values.length; j++ ){
				matcher = `${$scope.product.attr1_values[i]} ${$scope.product.attr2_values[j]}`;
				

				if($scope.product.attr3_values.length == 0){
					variant.push(matcher);
					continue;
				}

				for(let k = 0; k < $scope.product.attr3_values.length; k++ ){
					matcher = `${$scope.product.attr1_values[i]} ${$scope.product.attr2_values[j]} ${$scope.product.attr3_values[k]}`;

					variant.push(matcher);
				}
			}
		}
		
			// console.log(variant);
			let modified_var = [];
		let modified_variants = [...$scope.product.product_variants.map(item => {
			let attri_vals = `${item.attribute_value_1} ${item.attribute_value_2} ${item.attribute_value_3}`;
			// modified_var.push(attri_vals);
			if(variant.includes(attri_vals)){
				return	item;
			}
		})];

		let attr_values = []
		variant.map(it =>{
			$scope.product.product_variants.map(item =>{
			let attri_vals = `${item.attribute_value_1} ${item.attribute_value_2} ${item.attribute_value_3}`;
				attr_values.push(attri_vals);
			})
		})
		let attr_valuess = Array.from(new Set(attr_values));
		let difference = variant.filter(x => !attr_valuess.includes(x));
		let new_var = [];
			console.log(difference);
		difference.map(it => {
			console.log(it.split(' ')[0]);
			if(it.split(' ')[0] == `${$scope.new_attr}` && `${$scope.new_attr}` != undefined){
				new_var = makeVariant(it.split(' ')[0],it.split(' ')[1],it.split(' ')[2]);
				let set_value = $scope.setValue(new_var);
					$scope.product.product_variants.push(new_var);
				$scope.product.variants.push(new_var);

			}
			if(it.split(' ')[1] == `${$scope.new_attr}` && `${$scope.new_attr}` != undefined){
				new_var = makeVariant(it.split(' ')[0],it.split(' ')[1],it.split(' ')[2]);
				let set_value = $scope.setValue(new_var);
					$scope.product.product_variants.push(new_var);
				$scope.product.variants.push(new_var);
			}
			if(it.split(' ')[2] == `${$scope.new_attr}` &&  `${$scope.new_attr}` != undefined){
				console.log(`${$scope.new_attr}`);
				new_var = makeVariant(it.split(' ')[0],it.split(' ')[1],it.split(' ')[2]);

				let set_value = $scope.setValue(new_var);
				$scope.product.product_variants.push(new_var);
				$scope.product.variants.push(new_var);
			}
			// new_var = makeVariant(it.split(' ')[0],it.split(' ')[1],it.split(' ')[2]);
			// console.log(new_var);
			
		})

	}
	

	/*simply create and return new variant object*/
	function makeVariant(attr1, attr2, attr3){
		let new_variant = {
			allow_out_of_stock              : 1,
			attr1_values					: $scope.product.attr1_values,
			attr2_values					: $scope.product.attr2_values,
			attr3_values					: $scope.product.attr3_values,
			attribute_value_1               : attr1,
			attribute_value_2               : attr2,
			attribute_value_3               : attr3,
			sku                             :'',
			retail_price                    : 0,
			before_discount_price           : 0,
			outlets 			: [...$scope.outlets.map(item => {
				item.retail_price = 0;
				item.supply_price = 0;
				return {...item}
			})],
			supplier_price                  : 0,
			editable                        : true,
			is_active                       : 1,
			is_deleted                      : 0,
			is_new                       	: 1,
			id                              :'',
		}
		return new_variant;
	}
	$scope.setCustomSku = function()
	{
		$scope.product.sku_custom = true;
		$scope.product.sku_number = false;
		$scope.product.sku_name	 = false;	
	}

	$scope.setValue = function(variant)
	{
		$scope.namedCounter = $scope.counter++;
		let sku = `${$scope.product.sku}-${$scope.namedCounter}`;
		// $scope.namedCounter++;

		if($scope.product.sku_number){
			console.log('getSku');
			_sku = init_sku++;
			variant.sku = $scope.default_sku;
			variant.sku_custom = false;
	        variant.sku  = ( $scope.product.sku_number && !$scope.product.sku_custom ? $scope.getSku() : '' );

		}else if($scope.product.sku_name){
			console.log('getNamedSku');
			$scope.namedCounter = 1;
			variant.sku = $scope.product.default_sku_name;
			variant.sku_custom = false;
			variant.sku  = ( $scope.product.sku_name && !$scope.product.sku_custom ? $scope.getNamedSku() : '' );
			variant.sku  = editable = false;
	        				
		}else if($scope.product.sku_custom){
			console.log('sku_custom');
			_sku = init_sku++;
			variant.sku  =  $scope.getSku();
			// variant.sku = $scope.default_sku;
	        // variant.sku  = sku;

		}else{
			variant.sku  = '';
			variant.editable = true;
		}
		return  variant;
	}

	
	
	$scope.getSku = function()
	{
		if($scope.product.sku_type == 'number'){
			// console.log('number');
			let sku = angular.copy($scope.counter);
			// $scope.counter++;
			return sku;
		}else if($scope.product.sku_type == 'custom')
		{
			// console.log('custom');
			let sku = $scope.product.name.replace(/ /g, '-').toLowerCase();
			sku = `${sku}-${$scope.name_counter}`;
			$scope.name_counter++;
			return sku;
		}else{
			// console.log('else');
			let sku =$scope.counter;
			// $scope.counter++;
			return sku;
		}
	}
	

	$scope.getNamedSku = function()
	{
		let sku = `${$scope.product.sku}-${$scope.namedCounter}`;
		$scope.namedCounter++;
		return sku;
	}

	$scope.$watch('product.attr1_values', function(newVal, oldVal){
		if($scope.watch_attr1_values && newVal != oldVal){

			$scope.languages.map((lang,index) => {

				if(lang.attr1_values.length > 0){

					let attribs = [...lang.attr1_values.filter(item => {
						if(newVal.includes(item.key)){
							return item;
						}
					})];

					if(index == 0){

						if(attribs.length != newVal.length){
							attribs.push({ key : newVal.slice(-1)[0] , value : newVal.slice(-1)[0] });
						}

					}else{

						if(attribs.length != newVal.length){
							attribs.push({ key : newVal.slice(-1)[0] , value : '' });
						}
					}

					lang.attr1_values = attribs;
				}else{
					if(index == 0){

						newVal.map(val => {
							lang.attr1_values.push({ key : val , value : val });
						});

					}else{

						newVal.map(val => {
							lang.attr1_values.push({ key : val , value : '' });
						});
					}					
				}
				
				return lang;
			});
			if($scope.watch_attr1_values &&  newVal.length > oldVal.length){
				$scope.new_attr = '';
				$scope.new_attr = newVal.filter(pop => !oldVal.includes(pop));
				generateVariants();
			}else{
				let pop_attr = newVal.filter(pop => !oldVal.includes(pop));
				$scope.product.variants.filter(item => {
					if(item.is_new == 1 ){
						if(item.attribute_value_1 != null){
							if(item.attribute_value_1.includes(pop_attr) ){
								item.is_deleted = 1;
							}
						}
					}
				})
			}
		}
	}, true);
	
	$scope.$watch('product.attr2_values', function(newVal, oldVal){
		if($scope.watch_attr2_values && newVal != oldVal){

			$scope.languages.map((lang,index) => {

				if(lang.attr2_values.length > 0){

					let attribs = [...lang.attr2_values.filter(item => {
						if(newVal.includes(item.key)){
							return item;
						}
					})];

					if(index == 0){

						if(attribs.length != newVal.length){
							attribs.push({ key : newVal.slice(-1)[0] , value : newVal.slice(-1)[0] });
						}

					}else{

						if(attribs.length != newVal.length){
							attribs.push({ key : newVal.slice(-1)[0] , value : '' });
						}
					}

					lang.attr2_values = attribs;
				}else{
					if(index == 0){

						newVal.map(val => {
							lang.attr2_values.push({ key : val , value : val });
						});

					}else{

						newVal.map(val => {
							lang.attr2_values.push({ key : val , value : '' });
						});
					}					
				}
				
				return lang;
			});

			if($scope.watch_attr2_values && newVal.length > oldVal.length){
				$scope.new_attr = '';
				$scope.new_attr = newVal.filter(pop => !oldVal.includes(pop));
				generateVariants();
			}else{
				let pop_attr2 = newVal.filter(pop => !oldVal.includes(pop));
				$scope.product.variants.filter(item => {
					if(item.is_new == 1 ){
						if(item.attribute_value_2 != null){
							if(item.attribute_value_2.includes(pop_attr2) ){
								item.is_deleted = 1;
							}
						}
					}
				})
			}
		}
	}, true);
	$scope.$watch('product.attr3_values', function(newVal, oldVal){
		if($scope.watch_attr3_values && newVal != oldVal){

			$scope.languages.map((lang,index) => {

				if(lang.attr3_values.length > 0){

					let attribs = [...lang.attr3_values.filter(item => {
						if(newVal.includes(item.key)){
							return item;
						}
					})];

					if(index == 0){

						if(attribs.length != newVal.length){
							attribs.push({ key : newVal.slice(-1)[0] , value : newVal.slice(-1)[0] });
						}

					}else{

						if(attribs.length != newVal.length){
							attribs.push({ key : newVal.slice(-1)[0] , value : '' });
						}
					}

					lang.attr3_values = attribs;
				}else{
					if(index == 0){

						newVal.map(val => {
							lang.attr3_values.push({ key : val , value : val });
						});

					}else{

						newVal.map(val => {
							lang.attr3_values.push({ key : val , value : '' });
						});
					}					
				}
				
				return lang;
			});

			if($scope.watch_attr3_values && newVal.length > oldVal.length){
				$scope.new_attr = '';
				$scope.new_attr = newVal.filter(pop => !oldVal.includes(pop));
				generateVariants();
			}else{
				let pop_attr3 = oldVal.filter(pop => !newVal.includes(pop));
				$scope.product.variants.filter(item => {
					if(item.is_new == 1 ){
						if(item.attribute_value_3 != null){
							if(item.attribute_value_3.includes(pop_attr3) ){
								item.is_deleted = 1;
							}
						}
					}
				})
			}
		}
	}, true);

	// $scope.$watch('product.attr3_values', function(newVal, oldVal){
	// 	if($scope.watch_attr3_values && newVal != oldVal){
	// 		console.log(newVal);

	// 		$scope.languages.map((lang,index) => {

	// 			if(lang.attr3_values.length > 0){

	// 				let attribs = [...lang.attr3_values.filter(item => {
	// 					if(newVal.includes(item.key)){
	// 						return item;
	// 					}
	// 				})];

	// 				if(index == 0){

	// 					if(attribs.length != newVal.length){
	// 						attribs.push({ key : newVal.slice(-1)[0] , value : newVal.slice(-1)[0] });
	// 					}

	// 				}else{

	// 					if(attribs.length != newVal.length){
	// 						attribs.push({ key : newVal.slice(-1)[0] , value : '' });
	// 					}
	// 				}

	// 				lang.attr3_values = attribs;
	// 			}else{
	// 				if(index == 0){

	// 					newVal.map(val => {
	// 						lang.attr3_values.push({ key : val , value : val });
	// 					});

	// 				}else{

	// 					newVal.map(val => {
	// 						lang.attr3_values.push({ key : val , value : '' });
	// 					});
	// 				}					
	// 			}
				
	// 			return lang;
	// 		});

	// 		if(newVal.length > oldVal.length){
	// 			$scope.new_attr = newVal.filter(pop => !oldVal.includes(pop));
	// 			generateVariants();
	// 		}else{
	// 			let pop_attr3 = newVal.filter(po => !oldVal.includes(po));
	// 			console.log(newVal);
	// 			// $scope.product.variants.filter(item => {
	// 			// 	if(item.is_new == 1 ){
	// 			// 		if(item.attribute_value_3.includes(pop_attr3) ){
	// 			// 			item.is_deleted = 1;
	// 			// 		}
	// 			// 	}
	// 			// })
	// 		}
	// 	}
	// }, true);


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
	$scope.$watch('product.name', function(newVal, oldVal){
		if(!newVal){
			$scope.product.prefix = '';
			return;
		}
		$scope.product.prefix = newVal.replace(/\s/g,'-');
		$scope.product.prefix = $scope.product.prefix.toLowerCase();
		$scope.setValue();
	});
	$scope.$watch('product.name', function(newVal, oldVal){
		if(!newVal){
			$scope.product.handle = '';
			return;
		}
		$scope.product.handle = newVal.replace(/\s/g,'');
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
	// $scope.$watch('product.sku_custom', function (newVal, oldVal) {
	//     if (newVal) {
	//         $scope.product.sku = '';
	//         variant.map((item) => {
	//         	item.sku = '';
	//         	item.editable = true;
	//         	return item;
	//         });
	//     }
	// });	

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