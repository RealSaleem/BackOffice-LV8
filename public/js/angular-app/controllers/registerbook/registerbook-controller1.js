posApp.controller('registerbookCtrl', ['$scope', 'registerDataService', 'urlService' ,function($scope,registerDataService, urlService){

	$scope.viewModel = {};

	$scope.add = function(register){
		console.log(register);
		console.log('Register Data');
		$scope.ajaxPost('registerbook/confirm_add', register)
			.then(function(response){
				if(response.IsValid){
				  	toastr.success(response.Message,'Success');
					window.location.href = document.referrer;
				}
				else
					toastr.error(response.Exception)
			})
        
		return false;
	}

	$scope.init = function(){
		$scope.ajaxGet('registerbook/get_all', null)
			.then(function(response){
				$scope.viewModel.registers = response.Payload;
			})
	}

	$scope.edit = function(register){
		
		registerDataService.edit(register).then(function(){
			toastr.success(response.Message,'Success');
			window.location.href = document.referrer;
		})
	}

	$scope.deleteRegister = function(register){
		$scope.ajaxPost('registerbook/delete', register)
			.then(function(response){
				if(response.IsValid){
					toastr.success(response.Message,'Success');
					window.location.href = document.referrer;
				}else{
					toastr.error(response.Message,'Error');
				}
			})
	}

	$scope.showeditdata = function(){
	 	registerDataService.get(urlService.getUrlPrams().id)
	 		.then(function(register){
	 			$scope.register = register;
	 		});
	}


}])