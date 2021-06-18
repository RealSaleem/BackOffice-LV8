posApp.controller('customer_groupCtrl', function($scope, customerGroupDataService){

	$scope.viewModel = {};

	$scope.button = {};
	$scope.button.disabled = false;

	$scope.add = function(customer_group){
			$scope.button.disabled = true;
			$('#LoaderDiv').show();
			customerGroupDataService.add_customer_group(customer_group)
			.then(function(){
				$('#LoaderDiv').hide();
				toastr.success(customer_group.Message,'Success');
				
				setTimeout(function(){window.location.href = BASE_URL + 'customer_group';},2000);
				//window.location.href = BASE_URL + 'customer_group';
			})
	}

	$scope.init = function(){
		customerGroupDataService.all().then(function(customers){
			$scope.viewModel.customer_groups = customers;
		})	;
	}

	$scope.deleteCustomerGroup = function(customer_group){
		customerGroupDataService.delete_customer_group(customer_group)
			.then(function(){
				toastr.success(customer_group.Message,'Success');
				setTimeout(function(){window.location.href = BASE_URL + 'customer_group';},2000);
				//window.location.href = BASE_URL + 'customer_group';
			
			})
	}

	$scope.showDeleteModal = function(customer_group){
		console.log(customer_group)
		$scope.selectedcustomer_group = customer_group;
		$('#deleteModal').modal('show')
	}

	$scope.showEditModal = function(customer_group){
		console.log(customer_group)
		$scope.selectedcustomer_group = customer_group;
		$('#editModal').modal('show')
	}

	$scope.edit = function(customer_group){
			customerGroupDataService.edit_customer_group(customer_group)
			.then(function(){
				toastr.success(customer_group.Message,'Success');
				setTimeout(function(){window.location.href = BASE_URL + 'customer_group';},2000);
				//window.location.href = BASE_URL + 'customer_group';
			})
	}

	$scope.SetGroup = function(customerGroup_id){
        sessionStorage.customerGroup_id = customerGroup_id;
        return true;
    }

})