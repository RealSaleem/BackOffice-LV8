posApp.controller('retailDashboardCtrl', function($scope,retailDashboardDataService,urlService){

	$scope.viewModel = {};

	$scope.init = function(){
		
		retailDashboardDataService.get_top_sale_people().then(function(topSale){
			 $scope.top_sale_people = topSale[0];
			console.log(topSale);
		});

		retailDashboardDataService.get_sold_product().then(function(soldProduct){
			 $scope.soldProduct = soldProduct;
			console.log(soldProduct);
		});
	}

	$scope.getProducts = function(numOfProducts){
		retailDashboardDataService.get_num_product(numOfProducts).then(function(numProducts){
			$scope.soldProduct = numProducts;
		});
		//console.log(numOfProducts);
	}


})