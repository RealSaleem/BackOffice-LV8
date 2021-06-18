posApp.controller('store-controller', function($scope,storeDataService,languageService,currencyDataService,cityDataService,urlService, Upload, $timeout ){

    $scope.viewModel = {};
    $scope.StoreId = 0;
    $scope.samePhysicalPostal = false;
    $scope.currency_disabled = false;

    $scope.button = {};
	$scope.button.disabled = false;

	$scope.store = {};

	$scope.countryPhySelected = true;
    $scope.countryPostSelected = true;

    $scope.addSelect2 = function()
    {
		setTimeout(() => {  $('.select2').select2();  },2000);
		$('.moreless').trigger('click');
    }


	$scope.edit = function(store){
		$scope.button.disabled = true;
		$('#LoaderDiv').show();

		if(store.postal_city == null)
			store.postal_city = store.physical_city;
		if(store.postal_country == null)
			store.postal_country = store.physical_country;
		if(parseInt(store.postal_postcode) == null)
			store.postal_postcode = store.physical_postcode;
		if(store.postal_state == null)
			store.postal_state = store.physical_state;
		if(store.postal_street_1 == null)
			store.postal_street_1 = store.physical_street_1;
		if(store.postal_street_2 == null)
			store.postal_street_2 = store.physical_street_2;
		if(store.postal_suburb == null)
			store.postal_suburb = store.physical_suburb;

		if ($scope.samePhysicalPostal) {
			store.postal_city = store.physical_city;
			store.postal_country = store.physical_country;
			store.postal_postcode = parseInt(store.physical_postcode);
			store.postal_state = store.physical_state;
			store.postal_street_1 = store.physical_street_1;
			store.postal_street_2 = store.physical_street_2;
			store.postal_suburb = store.physical_suburb;
			}
		
		storeDataService.edit(store).then(function(){
			$('#LoaderDiv').hide();
		   /*toastr.success('Store has been Setup successfully', 'Store Setup');
		    let lang = languageService.get('Store Setup');
                   console.log(lang);*/
                    // toastr.success(store.Message,lang.'Success');
		    setTimeout(function(){window.location.href = BASE_URL + 'outlet';},2000);

		    /*setTimeout(() => {
		        location.reload(true)
		    }, 3000);
            */
		});
		$scope.button.disabled = false;
	}


	$scope.getPhyCities = function(){
        if ($scope.store.physical_country != undefined) {
            $scope.countryPhySelected = false;
        }
        if ($scope.store.physical_country == undefined) {
            $scope.countryPhySelected = true;
        }

        cityDataService.get_selected_cities($scope.store.physical_country).then(function(city){
            $scope.physical_cities = city;
        });
    }

    $scope.getPostCities = function(){
        if ($scope.store.postal_country != undefined) {
            $scope.countryPostSelected = false;
        }
        if ($scope.store.postal_country == undefined) {
            $scope.countryPostSelected = true;
        }

        cityDataService.get_selected_cities($scope.store.postal_country).then(function(city){
            $scope.postal_cities = city;
        });
    }

	$scope.uploadFiles = function(files, invalidFiles){

		if(invalidFiles[0] != undefined) {
		/*	toastr.error('Image size cannot be greater than 1.5 MB', 'Warning', {timeOut : 3000});
			let lang = languageService.get('Image Size');
                   console.log(lang);*/
                    toastr.error(files.Message,'Success');
		}
	    $scope.files = files;
	        angular.forEach(files, function(file) {
	           file.upload = Upload.upload({
	               url: site_url('image/upload'),
	               data: { image: file }
	           });

	            file.upload.then(function (response) {
	            	console.log(response);
	// alert(response);

	               $timeout(function () {
	                  $scope.store.store_logo = site_url(IMAGE_URL(response.data.path));
	                  // console.log($scope.store.store_logo);
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
// FOR MULTISTORE
	$scope.showeditdata = function(){
	 	$('.moreless').trigger('click');
	 	storeDataService.get_all_industry().then(function(response){
	 		$scope.allIndustries = response;
	 	});
	 	storeDataService.get_all_languages().then(function(languages){
	 		$scope.allLanguages = languages;
	 	});
	 	console.log('$scope.allLanguages');
	 	console.log($scope.allLanguages);
		let store_id =  $('#store_id').val();
		//console.log(store_id);
		if(store_id != null && store_id>0 && store_id != ''){
	     storeDataService.get_by_id(store_id)
	 		.then(function(store){
	 			if (store.default_currency != null) {
	 				$scope.currency_disabled = true;

	 			}
	 			$scope.store = store;
	 				$scope.store.language_ids =[];
	 				store.languages.map(lang=>{
	 					$scope.store.language_ids.push(lang.id);
	 				});
	 				if ($scope.store.language_ids.length == 0) {
	 				$scope.store.language_ids =[$scope.allLanguages[0].id];
	 			}
	 		}).then($scope.addSelect2);
		} else {
			$scope.store.id = 0;
		}

		currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });

		cityDataService.get_all_city().then(function(city){
                 $scope.physical_cities = city;
                $scope.postal_cities = city;
            });

		// else
		// {
		// 	$scope.store.store_logo = site_url('img/photo1.jpg');
		// }
	 }
// FOR SINGLE
	$scope.editdata = function(){
	 	$('.moreless').trigger('click');
	 	storeDataService.get_all_industry().then(function(response){
	 		$scope.allIndustries = response;
	 	});
	 	storeDataService.get_all_languages().then(function(languages){
	 		$scope.allLanguages = languages;
	 	});
	     storeDataService.get()
	 		.then(function(store){
	 			if (store.default_currency != null) {
	 				$scope.currency_disabled = true;

	 			}
	 			$scope.store = store;
	 				$scope.store.language_ids =[];
	 				store.languages.map(lang=>{
	 					$scope.store.language_ids.push(lang.id);
	 				});
	 				if ($scope.store.language_ids.length == 0) {
	 				$scope.store.language_ids =[$scope.allLanguages[0].id];
	 			}
	 		}).then($scope.addSelect2);

		currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });

		cityDataService.get_all_city().then(function(city){
                 $scope.physical_cities = city;
                $scope.postal_cities = city;
            });

		
	 }
})