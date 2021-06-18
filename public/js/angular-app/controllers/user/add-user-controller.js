posApp.controller('addUserCtrl', function($scope, $http,userDataService, currencyDataService,cityDataService,outletDataService, rolesDataService, Upload, $timeout, languageService){
	$scope.outlets = [];
	$scope.user = {};

	$scope.button = {};
	$scope.button.disabled = false;

	$scope.init = function(){
		outletDataService.get_all().then((outlets) => { $scope.outlets = outlets; });
		rolesDataService.get_all().then((roles) => { $scope.roles = roles; });

		currencyDataService.get_all().then(function(currency){
                $scope.currencies = currency;
            });

		cityDataService.get_all_city().then(function(city){
                 $scope.cities = city;
            });
		setTimeout(()=>{
			$("#acc_outlet").select2();
			$("#acc_role").select2();
		},2000)
	}

	$scope.checkEmail = function(email){
		userDataService.checkEmail(email).then(function(response){
			if (response != null) {
				//toastr.error('This Email Already Taken', 'Email Exist');
				 let lang = languageService.get('Email Exist');
                   console.log(lang);
                    toastr.error(lang.Value.Message,lang.Value.Title);	
			}
		});
	}

	$scope.createUser = function(user){
		if (user.password != user.confirm_password) {
			/*toastr.error('The password confirmation does not match.', 'Error');
			let lang = languageService.get('Password Error');
					console.log(lang);*/
					toastr.error(user.Message,'Error');


		}else if(user.role == 0){
			/*toastr.error('Plese select role.', 'Error');
			 let lang = languageService.get('Select Role');
                   console.log(lang);*/
                    toastr.error(user.Message,'Error');
		}else{
			$scope.button.disabled = true;
			$('#LoaderDiv').show();
			userDataService.add_confirmed(user).then((response)=>{ 

			if(response!=null){
				$('#LoaderDiv').hide();
				/*toastr.success('User has been created successfully', 'User Created');
				 let lang = languageService.get('User Created');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
				setTimeout(function(){window.location.href = BASE_URL + 'users';},2000);
			}
		})
		}
	}

	$scope.setOutlet = function(){
		$('#outletModal').modal('show');
	}

	$scope.addOutlet = function(){	
		outletDataService.add($scope.outlet).then(function(){
		    outletDataService.get_all().then(function(outlet){
		    	$scope.outlets = outlet;
				$scope.user.outlet_id = outlet[outlet.length - 1].id;
			});
		})
	}

	$scope.uploadFiles = function(files, invalidFiles){

		if(invalidFiles[0] != undefined) {
			/*toastr.error('Image size cannot be greater than 1.5 MB', 'Warning', {timeOut : 3000});
			 let lang = languageService.get('Image Size');
                   console.log(lang);*/
                    toastr.Warning(files.Message,'Warning');
		}
	    $scope.files = files;
	        angular.forEach(files, function(file) {
	           file.upload = Upload.upload({
	               url: site_url('image/upload'),
	               data: { image: file }
	           });

	            file.upload.then(function (response) {

	               $timeout(function () {
	                  $scope.user.user_image = site_url(IMAGE_URL( response.data.path));
	               });
	            }, function (response) {

	               if (response.status > 0)
	                   $scope.errorMsg = response.status + ': ' + response.data;
	            }, function (evt) {

	               file.progress = Math.min(100, parseInt(100.0 * 
	                                        evt.loaded / evt.total));
	        	}).catch(function(error) {
	        		console.log(error);
	        	});
	    });
	}

	// $scope.UploadFile = function(){
	// 	userDataService.upload_image('image-upload-form').then(function(response){
	// 		console.log(response);
	// 		// $scope.user.user_image = response.image_path;
	// 	});
	// }
})