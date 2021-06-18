posApp.controller('incomeLedgerCtrl', function ($scope, $rootScope,urlService,ledgerDataService) {
    $scope.init = function () {
    	console.log("This is Test income leadger");        
    }

    $scope.ledgerRecord;
    
    $scope.getIncomeledger = function(id){
    	ledgerDataService.get_record({id:id}).then(function (response) {
            if (response!=null) {
                $scope.ledgerRecord = response;
                $('#myModal2').modal('show');
            } else {
                toastr.error(response.Exception)
            }
        });
	}
})