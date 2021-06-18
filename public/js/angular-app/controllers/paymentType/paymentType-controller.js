posApp.controller('paymentType-controller', function($scope,paymentTypeDataService,urlService,languageService){

	$scope.viewModel = {};

	$scope.init = function(){
		
		paymentTypeDataService.get_all().then(function(paymentType){
			$scope.viewModel.paymentType = paymentType;
		})	
	}

	$scope.add = function(paymentType){
		
		paymentTypeDataService.add(paymentType).then(function(){
			/*toastr.success('Payment type has been added successfully',' Payment type Added');
			let lang = languageService.get('Payment Added');
                   console.log(lang);*/
                    toastr.success(paymentType.Message,'Success');
			setTimeout(function(){window.location.href = BASE_URL + 'paymentType';},2000);
			//window.location.href = BASE_URL + 'paymentType';
		})
	}

	$scope.deletePaymentType = function(paymentType){
		paymentTypeDataService.delete_paymentType(paymentType)
			.then(function(){
				/*toastr.success('Payment Type has been deleted successfully','Payment Type Deleted');
				let lang = languageService.get('Payment Added');
                   console.log(lang);*/
                    toastr.success(paymentType.Message,'Success');
				setTimeout(function(){window.location.href = BASE_URL + 'paymentType';},2000);
				//window.location.href = BASE_URL + 'customer_group';
			
			})
	}
	
	$scope.showDeleteModal = function(paymentType){
		console.log(paymentType)
		$scope.selectedPaymentType = paymentType;
		$('#deleteModal').modal('show')
	}

	$scope.edit = function(paymentType){
		
		paymentTypeDataService.edit(paymentType).then(function(){
			setTimeout(function(){window.location.href = BASE_URL + 'paymentType';},2000);
		})
	}

	 $scope.showeditdata = function(){
	
	 	paymentTypeDataService.get(urlService.getUrlPrams().id)
	 		.then(function(paymentType){
	 			$scope.paymentType = paymentType;
	 		});
	
	 }

})