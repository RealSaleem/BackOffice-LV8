posApp.controller('outletStoreController', function($scope,storeDataService, urlService){

    $scope.viewModel = {};
    $scope.StoreId = 0;


	$scope.init = function(id){
		
		storeDataService.get(id).then(function(store){
			//console.log(store.name);
			$scope.storeName = store.name;
		})	
	}

})