posApp.controller('incomeHeadCtrl', function ($scope, $rootScope,urlService,ledgerHeadDataService) {
    $scope.init = function () {
    	console.log("This is Test income Head");        
    }

    $scope.incomeHead;
    
    $scope.getIncomehead = function(id){
    	ledgerHeadDataService.get_record({id:id}).then(function (response) {
            if (response!=null) {
                $scope.incomeHead = response;
                $('#myModal2').modal('show');
            } else {
                toastr.error(response.Exception)
            }
        });
	}
})