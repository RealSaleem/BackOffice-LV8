posApp.controller('inventoryReportCtrl', function($scope,$rootScope,inventoryReportDataService,languageService,urlService,outletDataService){

	$scope.init = function(){
		$scope.totalStock = 0;
		$scope.totalStockValue = 0;

		setTimeout(() => {  $('.select2').select2();  },500);
		$scope.getCurrency();

		$scope.getDecimal();

		outletDataService.get_user_outlets().then(function(outlets){
			$scope.Outlets = outlets;
		})

		getInventoryData();	
		
	}

	function getInventoryData(data =null){
		inventoryReportDataService.get_all(data).then(function(inventory){
			$scope.totalStock = 0;
			$scope.totalStockValue = 0;
			
			for (var i = 0; i<inventory.length; i++) {

				inventory[i].quantity = (inventory[i].quantity == null) ? 0 : inventory[i].quantity;
				$scope.totalStock = parseInt($scope.totalStock) + parseInt(inventory[i].quantity);
				$scope.totalStockValue = parseFloat($scope.totalStockValue) + parseFloat(inventory[i].tot_cost_price);
				
				inventory[i].porductWithVariants = inventory[i].product.name;
				console.log(inventory[i]);
				if ( inventory[i].attribute_value_1 !== '' && inventory[i].attribute_value_1 !== ' ') {
					inventory[i].porductWithVariants = inventory[i].porductWithVariants + '/' + inventory[i].attribute_value_1;
				}

				if ( inventory[i].attribute_value_2 !== '' && inventory[i].attribute_value_2 !== ' ' ) {
					inventory[i].porductWithVariants = inventory[i].porductWithVariants + '/' + inventory[i].attribute_value_2;
				}

				if ( inventory[i].attribute_value_3 !== '' && inventory[i].attribute_value_3 !== ' ') {
					inventory[i].porductWithVariants = inventory[i].porductWithVariants + '/' + inventory[i].attribute_value_3;
				}
			}

			 	$scope.inventoryData = inventory;  
		})	

	}
	$scope.Clear = function() {
        $scope.report_type = $scope.outlet_id = null;
        $scope.ReportType = 'inventory_report';
        $scope.init();
    }
	$scope.Search = function () {

        let data = {};
        data.report_type = $scope.ReportType;
        data.outlet_id = $scope.outlet_id;

        if(data.report_type == 'low_stock'){
        	if($scope.outlet_id > 0){
				getInventoryData(data);
        	}else{
				//toastr.warning('Please select an outlet','Warning');
				let lang = languageService.get('Select Outlet');
                   console.log(lang);
                    toastr.warning(lang.Value.Message,lang.Value.Title);
        	}
        }else{
        	getInventoryData(data);
        }
    }

});
