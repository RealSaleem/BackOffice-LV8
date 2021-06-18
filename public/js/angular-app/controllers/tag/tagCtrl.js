posApp.controller('tagCtrl', function($scope, tagDataService, languageService){

	$scope.viewModel = {};

	$scope.add = function(tag){
		tagDataService.add(tag).then(function (response) {
            if (response!=null) {
               /* toastr.success('Tag has been added successfully', 'Tag Added');
                let lang = languageService.get('Tag Added');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'tag';},2000);
                //window.location.href = document.referrer;
            } else {
                toastr.error(response.Exception)
            }
        });
	}

	$scope.init = function(){
		tagDataService.all().then(function (response) {
            $scope.viewModel.tags = response;
        });
	}

	$scope.editTag = function(tag){
			tagDataService.edit(tag).then(function (response) {
            //$scope.viewModel.brands = response.Payload;
            //if (response!=null) {
/*               toastr.success('Tag has been updated successfully', 'Tag Updated');
                let lang = languageService.get('Tag Updated');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
               setTimeout(function(){window.location.href = BASE_URL + 'tag';},2000);
                //window.location.href = document.referrer;
           // } else {
            //    toastr.error(response.Exception)
           // }
        });
	}

	$scope.showEditModal = function(tag){
		console.log(tag)
		$scope.selectedTag = tag;
		$('#editModal').modal('show')
	}

	$scope.deleteTag = function(tag){
		// $scope.ajaxPost('tag/delete', tag)
		// 	.then(function(response){
		// 		if(response.IsValid){
		// 			toastr.success('Tag has been deleted successfully', 'Tag Deleted');
		// 			setTimeout(function(){window.location.href = BASE_URL + 'tag';},2000);
		// 			//location.reload(true);
		// 		}else{
		// 			alert('You are not allowed to delete this tag.')
		// 		}
		// 	})
		tagDataService.delete_tag(tag)
			.then(function(){
				/*toastr.success('Tag has been deleted successfully','Tag Deleted');
				let lang = languageService.get('Tag Deleted');
                   console.log(lang);*/
                    toastr.success(tag.Message,'Success');
				setTimeout(function(){window.location.href = BASE_URL + 'tag';},2000);
				//window.location.href = BASE_URL + 'customer_group';
			
			})
	}

	$scope.showDeleteModal = function(tag){
		console.log(tag)
		$scope.selectedTag = tag;
		$('#deleteModal').modal('show')
	}
})