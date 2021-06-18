 posApp.controller('categoryCtrl', function($scope,categoryDataService, $timeout, languageService ){

	$scope.viewModel = {};
    $scope.viewModel1 = {};
    $scope.category = {
       sort_order  :   '',
       pos_display : 0

    };

 /*$scope.GetLang = function(key){
        let lang = languageService.get('Category Added');
        toastr.success(lang.Message,lang.Title);
    }*/



	$scope.add = function(){

        if($('#category-add-form')[0].checkValidity()) {
            if(image_data == 0){
                $scope.category.category_images = [];
                // toastr.error('Category image is required','Error');
                // return false;
            }else{
                $scope.category.category_images = image_data;
            }

        	categoryDataService.add($scope.category).then(function (response) {
                if (response!=null) {
                   /* toastr.success('Category has been added successfully', 'Category Added');
                   let lang = languageService.get('Category Added');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                    setTimeout(function(){window.location.href = BASE_URL + 'category';},2000);
                } 
                else {
                    toastr.error(response.Exception)
                }
            });
            return false;
        }
	}

    

	$scope.init = function(){
		categoryDataService.all().then(function (response) {
            $scope.viewModel.categories = response;
            console.log(response);
        });
	}

	$scope.editCategory = function(){
        if($('#category-edit-form')[0].checkValidity()) {
            
            if(image_data == 0){
                $scope.category.category_images = [];
                // toastr.error('Category image is required','Error');
                // return false;
            }else{
                $scope.category.category_images = image_data;
            }

            categoryDataService.edit($scope.category).then(function (response) {
                if (response!=null) {
                   /* toastr.success('Category has been updated successfully', 'Category Updated');
                    let lang = languageService.get('Category Updated');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                    setTimeout(function(){window.location.href = BASE_URL + 'category';},2000);
                } else {
                    toastr.error(response.Exception)
                }
            });

            return false;
        }        
    }

	$scope.showEditModal = function(category){
		
		$scope.category = angular.copy(category);
        $scope.category.sort_order = category.sort_order;


        $scope.viewModel1.categories = $scope.viewModel.categories.filter((category) => {
            if(category.id != $scope.category.id){
                return category;
            }
        })

        p_images = category.images;
		$('#editModal').modal('show')
	}

    $scope.hideEditModal = function(){
        // $scope.category = '';
        // $scope.category.sort_order = '';
        $scope.category = {
            sort_order  :   ''
        };   
        $('#category-add-form')[0].reset();
        
        $('#editModal').modal('hide');
    }

    $('#myModal').on('hidden.bs.modal', function () {
        $('#category-add-form')[0].reset();
        $scope.category = {
            sort_order  :   ''
        };
    })

    $('#editModal').on('hidden.bs.modal', function () {
        $('#category-edit-form')[0].reset();
        $scope.category = {
            sort_order  :   ''
        };          
    }) 
   
    $('#myModal').on('shown.bs.modal', function() {
        $('#category-add-form')[0].reset();
        $scope.category = {
            sort_order  :   ''
        }; 
    })     

	$scope.deleteCategory = function(category){
		categoryDataService.delete_category(category).then((response) => {
            if (response != null) {
              /*  toastr.success('Category has been deleted successfully', 'Category Deleted');
                let lang = languageService.get('Category Deleted');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'category';},2000);
            }
        });
        $("#deleteModal").modal('hide');
    }

	$scope.showDeleteModal = function(category){
		$scope.selectedCategory = category;
		$('#deleteModal').modal('show')
	}

    $scope.SetCategory = function(category_id){
        sessionStorage.category_id = category_id;
        return true;
    }

    $scope.UploadImg = function(){
      FileUpload( function(response){
        if(response.errors){
          //toastr.error(response.errors);
          let lang = languageService.get('Image Size Cat');
                   console.log(lang);
                    toastr.success(lang.Value.Message,lang.Value.Title);
        }
        else{
            $scope.category.image = site_url(IMAGE_URL(response.path));
        }
      })
    }
    function FileUpload(callback) {
        var file_data = document.getElementById('file').files[0];
        var form_data = new FormData();
        form_data.append('image', file_data);
        $.ajax({
            url: site_url('image/upload'), // point to server-side controller method
            dataType: 'text', // what to expect back from the server
            cache: false,
            contentType: false,
            processData: false,
            async:false,
            data: form_data,
            type: 'post',
            success: function (response) {

                var fileData = JSON.parse(response);// display success response from the server
                callback(fileData);
            },
           
        });

    }

})