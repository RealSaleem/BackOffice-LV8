posApp.controller('cashFlowCtrl', function ($scope, $rootScope,storeDataService, cashFlowDataService, urlService) {

    $scope.CashFlowList = [];
    $scope.IsRegisterOpen = false;

    $scope.Type = '';
    $scope.Amount = null;
    $scope.Notes = '';
    $scope.RegisterId = localStorage.getItem("openRegister");
    $scope.RegisterHistoryId = localStorage.getItem("registerHistoryId");

    $scope.Initialize = function () {
        $scope.IsRegisterOpen = (localStorage.getItem("isRegisterOpen") === 'true') ? true : false;

        if ($scope.IsRegisterOpen) {

            if ($scope.RegisterHistoryId != null && $scope.RegisterHistoryId > 0) {
                $scope.Refresh();
            }
        }

        // if(localStorage.getItem('currency') === null){
        //     storeDataService.get(store_id)
        //     .then(function(store){
        //         localStorage.setItem('currency',store.default_currency);
        //         $rootScope.currency = localStorage.getItem('currency');
        //     });

        // }else{
        //     $rootScope.currency = localStorage.getItem('currency');
        // }
        $scope.getCurrency();

        $scope.getDecimal();
    }

    $scope.Refresh = function(){
        cashFlowDataService.get_all($scope.RegisterHistoryId).then((response) => {
            if (response != null && response.length > 0) {
                $scope.CashFlowList = [];
                response.map((item) => {
                    item.created_at = moment.utc(item.created_at).local().format('LT');
                    $scope.CashFlowList.push(item);
                });
            }
        });
    }

    $scope.Initialize();

    $scope.deleteCashflow = function(cashflowId){
        cashFlowDataService.delete_cashflow(cashflowId).then(function(response){
            if (response != null) {
                toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'cash-management'},2000);
            }
            else{
                toastr.error(response.Exception);
            }
        });
    }

    $scope.Add = function () {
 
        if ($('#add-form')[0].checkValidity()) {
            $scope.cashflow.register_id = $scope.RegisterId;
            $scope.cashflow.register_history_id = $scope.RegisterHistoryId;
            $scope.cashflow.outlet_id = localStorage.getItem("outletId");

            cashFlowDataService.add($scope.cashflow).then((response) => {
/*                toastr.success('Cash has been added successfully', 'Cash Added');
                let lang = languageService.get('Add Cash');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                $scope.cashflow = {};
                $scope.Refresh();
                $('#add-form')[0].reset();
            });
            $('#myModal').modal('hide');
        }

        return false;
    }

    $scope.Remove = function (register) {
        if ($('#remove-form')[0].checkValidity()) {
            $scope.cashflow.register_id = $scope.RegisterId;
            $scope.cashflow.register_history_id = $scope.RegisterHistoryId;
            $scope.cashflow.outlet_id = localStorage.getItem("outletId");

            cashFlowDataService.remove($scope.cashflow).then((response) => {
              /* toastr.success('Cash has been removed successfully', 'Cash Removed');
               let lang = languageService.get('Remove Cash');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                $scope.Type = '';
                $scope.Amount = null;
                $scope.Notes = '';                    
                $scope.Refresh();
            });
            $('#myModalR').modal('hide');
        }

        return false;
    }
})