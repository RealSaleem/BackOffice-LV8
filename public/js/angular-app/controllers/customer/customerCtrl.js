posApp.controller('customerCtrl',['$scope','urlService','$rootScope','customerDataService','cityDataService','customerGroupDataService','currencyDataService','supplierDataService' ,'orderDataService','languageService',function($scope,urlService,$rootScope,customerDataService,cityDataService,customerGroupDataService,currencyDataService,supplierDataService,orderDataService,languageService){

	$scope.viewModel = {};
    $scope.customer = {};
	$scope.filters = { status: 1 , customer_id : null};

    $scope.button = {};
    $scope.button.disabled = false;

    $scope.countryPhySelected = true;
    $scope.countryPostSelected = true;

	$scope.Outlets = [];
    $scope.OutletId = 0;
    $scope.Customer = '';
    $scope.UserId = 0;
    $scope.RegisterId = 0;
    $scope.DateRange = null;
    $scope.Users = [];
    $scope.Registers = [];
    $scope.Customers = [];
    $scope.OutletsData = [];
    $scope.OutletsDataHolder = [];
    $scope.Orders = [];
    $scope.Links = null;
    $scope.Next = null;
    $scope.Previous = null;
    $scope.Amount = null;
    setTimeout(() => {  $('.select2').select2();  },500);

    $scope.Search = function () {

        let data = {};

        if($scope.OutletId != null && $scope.OutletId > 0){
            data.outlet_id = $scope.OutletId;
        }

        if($scope.UserId != null && $scope.UserId > 0){
            data.user_id = $scope.UserId;
        }
        if($scope.RegisterId != null && $scope.RegisterId > 0){
            data.register_id = $scope.RegisterId;
        }

        if($scope.Customer != null && $scope.Customer.length > 0){
            data.customer = $scope.Customer;
        }

        if($scope.DateRange != null && $scope.DateRange.length > 0){
            let dates = $scope.DateRange.split(' - ');
            data.start_date = dates[0];
            data.end_date = dates[1];
        }

        if ($scope.Amount != null && $scope.Amount > 0) {
            data.amount = $scope.Amount;
        }

        $scope.GetOrders(data);
    }

    $scope.getPhyCities = function(){
        if ($scope.customer.country != undefined) {
            $scope.countryPhySelected = false;
        }
        if ($scope.customer.country == undefined) {
            $scope.countryPhySelected = true;
        }

        cityDataService.get_selected_cities($scope.customer.country).then(function(city){
            $scope.physical_cities = city;
        });
    }

    $scope.getPostCities = function(){
        if ($scope.customer.postal_country != undefined) {
            $scope.countryPostSelected = false;
        }
        if ($scope.customer.postal_country == undefined) {
            $scope.countryPostSelected = true;
        }

        cityDataService.get_selected_cities($scope.customer.postal_country).then(function(city){
            $scope.postal_cities = city;
        });
    }

    $scope.Filter = function () {

        if ($scope.OutletId > 0) {
            let outlet = $scope.OutletsDataHolder.filter((outlet) => {
                return outlet.id == $scope.OutletId;
            });

            if (outlet[0] != null) {
                $scope.Users = [];

                outlet[0].users.map((user) => {
                    $scope.Users.push(user);
                });

                $scope.Registers = [];

                outlet[0].registers.map((register) => {
                    $scope.Registers.push(register);
                });

            }
        } else {
            $scope.GetAllUsers();
            $scope.GetAllRegisters();
        }
    }

 $scope.reset = function(){
        
        $scope.filters.name=''; 
        $scope.filters.customerGroup_id = setTimeout(() => { $("#CG_reset").select2().trigger("change");  },100);
        $scope.filters.mobile='';   

    }




    $scope.setCustomerGroup = function(type){
                $('#groupModal').modal('show');
        }

    $scope.setSupplier = function(){
                $('#supplierModal').modal('show');
        }

    $scope.addGroup = function(){
            // //console.log($scope.category);
        customerGroupDataService.add_customer_group($scope.customer_group).then(function (response) {
            if (response!=null) {
                customerGroupDataService.all().then(function(customerGroup){
                $scope.customerGroup = customerGroup;
                $scope.customer.customer_group_id = customerGroup[customerGroup.length - 1].id;
            });
            } else {
                toastr.error(response.Exception)
            }
        });
    }

    $scope.addSupplier = function(){
            supplierDataService.add($scope.supplier).then(function(response){
                if (response != null) {
                    supplierDataService.all().then(function(supplier){
                        $scope.supplier = supplier;
                        $scope.customer.supplier_id = supplier[supplier.length - 1].id;
                    });
                } else {
                    toastr.error(response.Exception);
                }
            });
        }

	$scope.add = function(customer){
        $scope.button.disabled = true;
        $('#LoaderDiv').show();
        
        if(!customer.postal_street)
            customer.postal_street = customer.street;
        if(!customer.postal_street2)
            customer.postal_street2 = customer.street2;
        if(!customer.postal_suburb)
            customer.postal_suburb = customer.suburb;
        if(!customer.postal_postcode)
            customer.postal_postcode = customer.postcode;
        if(!customer.postal_state)
            customer.postal_state = customer.state;
        if(!customer.postal_country)
            customer.postal_country = customer.country;
        if(!customer.postal_city)
            customer.postal_city = customer.city;


		////console.log(customer);
	    customerDataService.add(customer).then(function (response) {
	    	
	        if (response!=null) {
                $('#LoaderDiv').hide();
	            /*toastr.success('Customer has been added successfully', 'Customer Added');
                let lang = languageService.get('Customer Added');
                    console.log(lang);*/
                    toastr.success(response.Message,'Success');

	            setTimeout(function(){window.location.href = BASE_URL + 'customer';},2000);
	            //window.location.href = BASE_URL + 'customer';
	        } else {
                $('#LoaderDiv').hide();
	            toastr.error(response.Exception);
                $scope.button.disabled = false;
	        }
        });
            $scope.button.disabled = false;
	}

	$scope.edit = function(customer){
		
		customerDataService.edit(customer).then(function (response){
			/*toastr.success('Customer has been updated successfully', 'Customer Updated');
            let lang = languageService.get('Customer Updated');
                    console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'customer';},2000);
		});
		////console.log(customer);
	}

	$scope.init = function(){
        let data = {};
        // if(!is_customer){
                customerDataService.get_all().then(function (customers) {
                $scope.viewModel.customers = customers;
            });

                cityDataService.get_all_city().then(function(city){
                 $scope.physical_cities = city;
                $scope.postal_cities = city;
            });
        // }
        // else
        //     $scope.filters.customer_id = urlService.getUrlPrams().id;
        
	    
		customerGroupDataService.all().then(function(customerGroup){
				$scope.customerGroup = customerGroup;
			});

        currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });

		supplierDataService.all().then(function(supplier){
				$scope.supplier = supplier;
			});
			$scope.groups();
   			$scope.getCurrency();
            $scope.getDecimal();
   			getGroups($scope.filters);

   		orderDataService.get_filters().then(function (response) {
            //console.log(response);
            $scope.OutletsData = response;
            $scope.OutletsDataHolder = response;
            $scope.GetOutlets();
            $scope.GetAllUsers();
            $scope.GetAllRegisters();
            data.customer = urlService.getUrlPrams().id;
            $scope.GetOrders(data);
            // $scope.GetOrders(null);
        });
	}

	$scope.GetOrders = function (params) {
        orderDataService.get_orders(params).then(function (orderData) {
            //console.log(orderData.data.data);
            if (orderData.data != undefined) {
                $scope.Orders = [];
                $scope.Orders = orderData.data.data;
                $scope.Links = orderData.pagination;
                $scope.Next = orderData.data.next_page_url;
                $scope.Previous = orderData.data.prev_page_url;
            }
        });
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

            $scope.GetOrders(queryparams);
        }
    }

    $scope.GetOutlets = function () {
        if ($scope.OutletsDataHolder.length > 0) {
            $scope.Outlets = [];
            $scope.OutletsDataHolder.map((outlet) => {
                $scope.Outlets.push({ id: outlet.id, name: outlet.name });
            });
        }
    }

    $scope.GetAllUsers = function () {
        if ($scope.OutletsDataHolder.length > 0) {
            $scope.Users = [];
            $scope.OutletsDataHolder.map((outlet) => {
                outlet.users.map((user) => {
                    $scope.Users.push(user);
                });
            });
        }
    }

    $scope.GetAllRegisters = function () {
        if ($scope.OutletsDataHolder.length > 0) {
            $scope.Registers = [];
            $scope.OutletsDataHolder.map((outlet) => {
                outlet.registers.map((register) => {
                    $scope.Registers.push(register);
                });
            });
        }
    }

    $scope.GetDescription = function (item) {

        return item.payment_method + ' - ' + item.created_at + ' - ' + item.register.name;
    }

	$scope.groups = function(){
		if(sessionStorage.customerGroup_id){
			$scope.filters.customerGroup_id = sessionStorage.customerGroup_id;
			sessionStorage.removeItem('customerGroup_id');
		}
	}	

	function getGroups(filters){
		$scope.fetchingProducts = true;
		//console.log(filters);
		customerDataService.list(filters).then(function(response){
			//$scope.customer = [];
		    $scope.viewModel.customers = response;
		    });
	}

	$scope.applyFilters = function(filters){
		getGroups(filters);
	}

	$scope.showEditData = function(){
		customerGroupDataService.all().then(function(customerGroup){
				$scope.customerGroup = customerGroup;
			});

        currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });

		supplierDataService.all().then(function(supplier){
				$scope.supplier = supplier;
			});

        cityDataService.get_all_city().then(function(city){
                 $scope.physical_cities = city;
                $scope.postal_cities = city;
            });

		customerDataService.get(urlService.getUrlPrams().id)
		.then(function(customer){
			$scope.customer = customer;
			$scope.customer.date_of_birth = new Date($scope.customer.date_of_birth);
			//console.log(customer);
		});
	}

	$scope.showDetailData = function(){
		customerDataService.getDetail(urlService.getUrlPrams().id)
		.then(function(customer){
			$scope.customer = customer;
			//console.log(customer);
		});	
	}

	$scope.deleteCustomer = function(customer){
		$scope.ajaxPost('customer/delete', customer)
			.then(function(response){
				if(response.IsValid){
					/*toastr.success('Customer has been deleted successfully', 'Custommer Deleted');
                    let lang = languageService.get('Customer Deleted');
                    console.log(lang);*/
                    toastr.success(response.Message,'Success');
					setTimeout(function(){window.location.href = BASE_URL + 'customer';},2000);
				}else{
					 toastr.error(response.Message,'Error');
				}
			})
	}

    $scope.getDateFormat = function(timestamp) {
    return new Date(timestamp);
  }

	
	
}])