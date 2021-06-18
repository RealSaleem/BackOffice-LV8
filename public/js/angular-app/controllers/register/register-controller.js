posApp.controller('register-controller', function($scope,registerDataService, urlService, languageService){

	$scope.viewModel = {};

	$scope.add = function(register){
		// console.log(register);
		// $scope.ajaxPost('register/confirm_add', register)
		// 	.then(function(response){
		// 		if(response.IsValid)
		// 			window.location.href = document.referrer;
		// 		else
		// 			toastr.error(response.Exception)
		// 	})

		registerDataService.add(register)
			.then(function(){
				/*toastr.success('Register has been added successfully','Register Added');
				let lang = languageService.get('Register Added');
                   console.log(lang);*/
                    toastr.success(register.Message,'Success');
				setTimeout(function(){},20000);
				//window.location.href = BASE_URL + 'customer_group';
			})
	}

	$scope.init = function(){
		// $scope.ajaxGet('register/get_all', null)
		// 	.then(function(response){
		// 		$scope.viewModel.registers = response.Payload;
		// 	})
		registerDataService.get_all().then(function(registers){
			$scope.viewModel.registers = registers;
		})
	}

	$scope.edit = function(register){
		
		registerDataService.edit(register).then(function(){
			window.location.href = document.referrer;
		})
	}

	$scope.deleteRegister = function(register){
		// $scope.ajaxPost('register/delete', register)
		// 	.then(function(response){
		// 		if(response.IsValid){
		// 			window.location.href = document.referrer;
		// 		}else{
		// 			alert('You are not allowed to delete this register.')
		// 		}
		// 	})
		registerDataService.delete_register(register)
			.then(function(){
				/*toastr.success('Register has been deleted successfully','Register Deleted');
				let lang = languageService.get('Register Deleted');
                   console.log(lang);*/
                    toastr.success(register.Message,'Success');
				setTimeout(function(){},2000);
				//window.location.href = BASE_URL + 'customer_group';
			
			})
	}

	$scope.showeditdata = function(){
	
	 	registerDataService.get(urlService.getUrlPrams().id)
	 		.then(function(register){
	 			$scope.register = register;
	 		});
	
	 }

})