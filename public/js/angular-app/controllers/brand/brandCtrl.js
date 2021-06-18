 posApp.controller('brandCtrl', function($scope, brandDataService, urlService, languageService){

	$scope.viewModel = {};
	$scope.selectedBrand = null;

	$scope.add = function(brand){
		brandDataService.add(brand).then(function (response) {
            if (response!=null) {
                
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'brand';},2000);
                //window.location.href = document.referrer;
             } else {
                toastr.error(response.Exception)
            }
        });
	}

	$scope.init = function(){
		brandDataService.all().then(function (response) {
            $scope.viewModel.brands = response;
        });
	}

	$scope.editBrand = function(brand){
		console.log(brand);
		brandDataService.edit(brand).then(function (response) {
            console.log(response);
            //$scope.viewModel.brands = response.Payload;
            if (response!=null) {
                /*toastr.success('Brand has been updated successfully', 'Brand Updated');
                let lang = languageService.get('Brand Edited');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'brand';},2000);
                //window.location.href = document.referrer;
             } else {
                 toastr.error(response.Exception)
             }
        });
	}

	$scope.showEditModal = function(brand){
		//console.log(brand)
		$scope.selectedBrand = brand;
		$('#editModal').modal('show')
	}

	$scope.deleteBrand = function(brand){
		brandDataService.delete_brand(brand).then(function (response) {
            if (response!=null) {
/*               toastr.success('Brand has been deleted successfully', 'Brand Deleted');
               let lang = languageService.get('Brand Deleted');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'brand';},2000);
                //window.location.href = document.referrer;
            } else {
                toastr.error(response.Exception)
            }
        });
	}

	$scope.showDeleteModal = function(brand){
		console.log(brand)
		$scope.selectedBrand = brand;
		$('#deleteModal').modal('show')
	}

    $scope.SetBrand = function(brand_id){
        sessionStorage.brand_id = brand_id;
        return true;
    }

})