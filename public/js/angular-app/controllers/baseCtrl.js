posApp.controller('baseCtrl', function($scope, $rootScope,storeDataService,ajaxService){
		$scope.activeService = 0;
		$scope.updateActiveService = function(num){
		    $scope.activeService += num;
		}

		$scope.$watch('activeService', function(newVal, oldVal) {
		    if (typeof newVal == 'undefined') return;
		    if (newVal == 0){
		        //hide loader here
		    }
		    else{
		    	//show loader here
		    }
		});

		$scope.ajaxGet = function(url, data, isBackground) {
		    return ajaxService.get(url, data, $scope, isBackground);
		}

		$scope.ajaxPost = function(url, data, isBackground, headers) {
		    return ajaxService.post(url, data, $scope, isBackground, headers);
		}

		$scope.getCurrency = function() {
			if(localStorage.getItem('currency') === null){
				storeDataService.get()
		 		.then(function(store){
		 			localStorage.setItem('currency',store.default_currency);
		 			$rootScope.currency = localStorage.getItem('currency');
		 		});

	        }else{
	        	$rootScope.currency = localStorage.getItem('currency');
	        }
		}

		$scope.getDecimal = function() {
			if(localStorage.getItem('store.round_off') === null)
			{
			storeDataService.get()
	 		.then(function(store){
	 			localStorage.setItem('decimal',store.round_off);
	 			$rootScope.decimal = localStorage.getItem('decimal');
	 			
	 		});
	        }
	        else
	        {
	        	$rootScope.decimal = localStorage.getItem('decimal');
	        }
		}

		// setInterval(function(){ 
		// 	storeDataService.dummy_request().then(function(){
		// 		console.log('dummy request to avoid session break');
		// 	}) 
		// }, 60000);

		$scope.init = function(){
			$scope.getDecimal();
		}

		$scope.init();

})