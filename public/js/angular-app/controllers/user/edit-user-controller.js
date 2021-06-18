posApp.controller('editUserCtrl', function($scope, userDataService, outletDataService,urlService, Upload, $timeout, languageService){
	$scope.users = [];
	$scope.getUserData = function(){
		userDataService.get_user(urlService.getUrlPrams().id)
		.then(function(users){ 
			users.role = (users.roles[0].id).toString();
			users.outlet = [];

			users.outlets.map((outlet) => {
				users.outlet.push(outlet.id);
			});

			$('#user-outlets').find('option').each(function(){
				users.outlet.map((id) => {
					if(id === parseInt($(this).val())){
						setTimeout(() => {
							$(this).attr('selected',true).trigger('change');
						},1000);
					}
				})
			});

			$scope.users = users; 
			

		});
	}

	$scope.uploadFiles = function(files, invalidFiles){

		if(invalidFiles[0] != undefined) {
			/*toastr.error('Image size cannot be greater than 1.5 MB', 'Warning', {timeOut : 3000});
			 let lang = languageService.get('Image Size');
                   console.log(lang);*/
                    toastr.error(files.Message,'Error');
		}
	    $scope.files = files;
	        angular.forEach(files, function(file) {
	           file.upload = Upload.upload({
	               url: site_url('image/upload'),
	               data: { image: file }
	           });

	            file.upload.then(function (response) {

	               $timeout(function () {
	                  $scope.users.user_image = site_url(IMAGE_URL(response.data.path));
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

	$scope.editUser = function(users){		
		if (users.password != null) {
			if (users.password != users.confirm_password) {
		/*	toastr.error('The password confirmation does not match.', 'Password Mismatch');
			let lang = languageService.get('Password');
                   console.log(lang);*/
                    toastr.error(users.Message,'Error');
                    return;
			}	
		}

		userDataService.edit_confirmed(users).then(function(response){
			/*toastr.success('User has been updated successfully', 'User Updated');
			let lang = languageService.get('User Updated');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'users';},2000);
		});
	}

	
})