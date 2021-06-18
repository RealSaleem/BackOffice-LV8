posApp.controller('outletCtrl', function($scope,outletDataService,currencyDataService,cityDataService,storeDataService,urlService, languageService){

	$scope.viewModel = {};

	$scope.button = {};

	$scope.countrySelected = true;

	$scope.add = function(outlet){
		outletDataService.add(outlet).then(function(){
	  /* toastr.success('Outlet has been added successfully', 'Outlet Added');
	   let lang = languageService.get('Outlet Added');
                   console.log(lang);*/
                    toastr.success(outlet.Message,'Success');
	    setTimeout(function(){window.location.href = BASE_URL + 'outlet';},2000);
		})
	}


	$scope.init = function(){
		outletDataService.get_all().then(function(outlet){
			$scope.viewModel.outlets = outlet;
		})	;

		currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });

		cityDataService.get_all_city().then(function(city){
                 $scope.cities = city;
            });
	}

	$scope.getCities = function(){
        if ($scope.outlet.country != undefined) {
            $scope.countrySelected = false;
        }
        if ($scope.outlet.country == undefined) {
            $scope.countrySelected = true;
        }

        cityDataService.get_selected_cities($scope.outlet.country).then(function(city){
            $scope.cities = city;
        });
    }	

	$scope.Start = function(){
		storeDataService.get().then(function(store){
			$scope.storeName = store.name;
		});
	}

	$scope.Start();

	$scope.get_with_registers = function(){
		outletDataService.get_with_registers(urlService.getUrlPrams().id)
		  .then(function(outlet){
			$scope.viewModel.outlet = outlet;
		})
	}

	$scope.edit = function(outlet){
		console.log(outlet);
	    outletDataService.edit(outlet).then(function () {
	     /*  toastr.success('Outlet has been updated successfully', 'Outlet Updated');
	       let lang = languageService.get('Outlet Updated');
                   console.log(lang);*/
                    toastr.success(outlet.Message,'Success');
	        setTimeout(function(){window.location.href = BASE_URL + 'outlet';},2000);
		})
	}

	 $scope.showeditdata = function(){
	
	 	outletDataService.get(urlService.getUrlPrams().id)
	 		.then(function(outlet){
	 			$scope.outlet = outlet;
	 		});

	 		currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
              
            });
		
		cityDataService.get_all_city().then(function(city){
                 $scope.cities = city;
            });
	 }

	 $scope.deleteOutlet = function(outlet){
		$scope.ajaxPost('outlet/delete', outlet)
			.then(function(response){
				if(response.IsValid){
					/*toastr.success('Outlet has been deleted successfully', 'Outlet Deleted');
					let lang = languageService.get('Outlet Deleted');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
					setTimeout(function(){window.location.href = BASE_URL + 'outlet';},2000);
				}else{
					 toastr.error(response.Message,'Error');
				}
			})

		// outletDataService.delete_outlet(outlet)
		// 	.then(function(){
		// 		toastr.success('Outlet has been deleted successfully','Outlet Deleted');
		// 		setTimeout(function(){window.location.href = BASE_URL + 'outlet';},2000);
		// 		//window.location.href = BASE_URL + 'customer_group';
			
		// 	})
	}

})