posApp.controller('userListingCtrl', function($scope,$rootScope, userDataService, outletDataService){
	$scope.name = [];
	// $scope.role = [];
	$scope.outlet = [];
	$scope.filters = [];
	// $scope.all_outlets = [];
	$scope.selectedOutlet = [];
	$scope.init = function(){

   		$scope.getCurrency();
		userDataService.get_all().then((users) => { $scope.users = users; }).then($scope.getOutlets).then($scope.setAdmin);
		getAllOutlets();
	}

	$scope.getOutlets = function()
	{
		$scope.users.map((user) => {
			if(user.outlets.length > 0){
				let outlets = user.outlets.map(outlet => { 
					return outlet.name; 
				});

				user.outlet_names = outlets.join(' , ');
			}
			return user;
		});
	}

	$scope.setAdmin = function(){

		$scope.users.map((user) => {
			user.is_admin = (user.roles[0].name == 'admin');
			return user;
		});
	}

	$scope.applyFilters = function(filters){
		getAllUsers(filters);
	}

	function getAllUsers(filters){
		userDataService.get_all($scope.filters).then((users) => { $scope.users = users; }).then($scope.getOutlets).then($scope.setAdmin);
	}

	function getAllOutlets(){
		outletDataService.get_all().then((outlet) => { $scope.all_outlet = outlet; });
	}

	$scope.isAdmin = function(user){

		let admin = user.roles.filter(role => {
			if(role.name == 'admin'){
				return role;
			}
		});

		let result = true;

		if(admin.length > 0){
			result = false;
		}else{
			result = true;
		}

		return result;
	}
})