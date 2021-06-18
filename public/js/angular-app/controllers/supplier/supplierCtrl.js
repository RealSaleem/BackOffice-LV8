posApp.controller('supplierCtrl', function($scope,supplierDataService,cityDataService,currencyDataService,languageService ){

	$scope.viewModel = {};

    $scope.button = {};
    $scope.button.disabled = false;

    $scope.countryPhySelected = true;
    $scope.countryPostSelected = true;

	$scope.add = function(supplier){
        $scope.button.disabled = true;
        $('#LoaderDiv').show();
        if(!supplier.postal_street)
            supplier.postal_street = supplier.street;
        if(!supplier.postal_street2)
            supplier.postal_street2 = supplier.street2;
        if(!supplier.postal_suburb)
            supplier.postal_suburb = supplier.suburb;
        if(!supplier.postal_postcode)
            supplier.postal_postcode = supplier.postcode;
        if(!supplier.postal_city)
            supplier.postal_city = supplier.city;
        if(!supplier.postal_state)
            supplier.postal_state = supplier.state;
        if(!supplier.postal_country)
            supplier.postal_country = supplier.country;

        //console.log(supplier);
		supplierDataService.add(supplier).then(function (response) {
            console.log(response);
            if (response!=null) {
                $('#LoaderDiv').hide();
             /*  toastr.success('Supplier has been added successfully', 'Supplier Added');
               let lang = languageService.get('Supplier Added');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'supplier';},2000);
                //window.location.href = document.referrer;
            } else {
                $('#LoaderDiv').hide();
                toastr.error(response.Exception);
                $scope.button.disabled = false;
            }
        });
            $scope.button.disabled = false;
	}

	$scope.init = function(){
		supplierDataService.all().then(function (response) {
            $scope.viewModel.suppliers = response;
        });

        currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });

        cityDataService.get_all_city().then(function(city){
                 $scope.physical_cities = city;
                $scope.postal_cities = city;
            });
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

	$scope.deleteSupplier = function(supplier){
		supplierDataService.delete_supplier(supplier).then(function (response) {
            if (response!=null) {
               /* toastr.success('Supplier has been deleted successfully', 'Supplier Deleted');
                let lang = languageService.get('Supplier Deleted');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'supplier';},2000);
                //window.location.href = document.referrer;
            } else {
                toastr.error(response.Exception)
            }
        });
	}

	$scope.showDeleteModal = function(supplier){
		console.log(supplier)
		$scope.selectedSupplier = supplier;
		$('#deleteModal').modal('show')
	}

    $scope.SetSupplier = function(supplier_id){
        sessionStorage.supplier_id = supplier_id;
        return true;
    }

$scope.groups = function(){
        if(sessionStorage.supplierGroup_id){
            $scope.filters.supplierGroup_id = sessionStorage.supplierGroup_id;
            sessionStorage.removeItem('supplierGroup_id');
        }
    }   

     function getGroups(filters){
        $scope.fetchingProducts = true;
        console.log(filters);
        supplierDataService.list(filters).then(function(response){
            $scope.supplier = [];
            $scope.viewModel.suppliers = response;
            });
    }

    $scope.applyFilters = function(filters){
        //console.log(filters);
        getGroups(filters);
    }

    
    $scope.reset = function(){
        
       $scope.filters.name=''; 
      $scope.filters.email='';
      $scope.filters.mobile='';
      
 
    }

})