posApp.controller('editSupplierCtrl', function($scope, urlService, currencyDataService,cityDataService,languageService,supplierDataService){
	$scope.supplier = {};

	$scope.countryPhySelected = true;
    $scope.countryPostSelected = true;
	
	$scope.init = function(){
	
		supplierDataService.get(urlService.getUrlPrams().id)
			.then(function(supplier){
				$scope.supplier = supplier;
			}).then($scope.getPhyCities);
		
		currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });

		// cityDataService.get_all_city().then(function(city){
  //                $scope.physical_cities = city;
  //               $scope.postal_cities = city;
  //           });
	}

	$scope.getPhyCities = function(){
        if ($scope.supplier.country != undefined) {
            $scope.countryPhySelected = false;
        }
        if ($scope.supplier.country == undefined) {
            $scope.countryPhySelected = true;
        }

        if($scope.supplier.country.length > 0){
            cityDataService.get_selected_cities($scope.supplier.country).then(function(city){
                $scope.physical_cities = city;
            });
        }
    }

    $scope.getPostCities = function(){
        if ($scope.supplier.postal_country != undefined) {
            $scope.countryPostSelected = false;
        }
        if ($scope.supplier.postal_country == undefined) {
            $scope.countryPostSelected = true;
        }

        cityDataService.get_selected_cities($scope.supplier.postal_country).then(function(city){
            $scope.postal_cities = city;
        });
    }

	$scope.edit = function(supplier){
		// $scope.ajaxPost('supplier/confirm_edit', supplier)
		// 	.then(function(response){
		// 		if(response!=null){
		// 			toastr.success('Supplier has been updated successfully', 'Supplier Updated');
		// 			window.location.href = BASE_URL + "supplier";
		// 		}
		// 		else{
		// 			toastr.error(response.Exception);
		// 		}
		// 		console.log(response);
		// 	});

		supplierDataService.edit(supplier).then(function (response) {
            //$scope.viewModel.brands = response.Payload;
            //if (response!=null) {
               // toastr.success('Supplier has been updated successfully', 'Supplier Updated');
                /*let lang = languageService.get('Supplier Updated');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'supplier';},2000);
                //window.location.href = document.referrer;
           // } else {
            //    toastr.error(response.Exception)
           // }
        });
		
	}
})