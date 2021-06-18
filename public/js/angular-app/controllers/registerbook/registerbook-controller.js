
posApp.controller('registerbookCtrl', function ($scope, $rootScope,orderDataService,registerDataService,outletDataService, urlService, languageService) {

    $scope.viewModel = {};
    $scope.register = {};
    $scope.Model = {};
    $scope.IsRegisterOpen = false;

    $scope.button = {};


    $scope.Notes = '';
    $scope.ClosingExpected = null;
    $scope.ClosingActual = null;
    $scope.CashFlow = null;
    $scope.ShowCashMovement = false;
    $scope.Total = 0;

    $scope.Registers = [];

    $scope.GetRegisters = function() {
        if (!$scope.IsRegisterOpen) {
            registerDataService.get_all($scope.SelectedOutlet).then(function (results) {
                $scope.Registers = results;
            });
        }
    }

    $scope.GetOutlets = function() {
        if (!$scope.IsRegisterOpen) {
            outletDataService.get_user_outlets().then(function (results) {
                $scope.Outlets = results;
            });
        }
    }    

    $scope.Initialize = function () {

        $scope.IsRegisterOpen = (localStorage.getItem("isRegisterOpen") === 'true') ? true : false;

        if (!$scope.IsRegisterOpen) {
            $scope.GetOutlets();
        }        

        let registerbookHistoryId = localStorage.getItem("registerHistoryId");
        if (registerbookHistoryId > 0) {
            registerDataService.get_registerbook_history(registerbookHistoryId).then((response) => {
                if(response != null){
                    $scope.registerHistory = response.registerHistory;
                    $scope.Model = response.registerHistory;
                    $scope.ClosingExpected = response.registerHistory.closing_expected;
                    $scope.CashFlow = response.cash_flow[0];
                    $scope.Total = response.total;
                }
            });
    }
    $scope.getCurrency();

    $scope.getDecimal();
    }

    $scope.Initialize();

    $scope.CloseRegisterBookHistory = function () {
        if ($('#closing-form')[0].checkValidity()) {
            $('#LoaderDiv').show();

            let data = {
                register_id: $scope.close.RegisterId,
                register_history_id: localStorage.getItem("registerHistoryId"),
                closing_expected: $scope.ClosingExpected,
                closing_actual: $scope.close.ClosingActual,
                closing_notes: $scope.close.Notes,
            };

            registerDataService.close(data).then(function(response) {
                if (response != null && response > 0) {
                    $('#LoaderDiv').hide();
                    /*toastr.success('Registerbook has been closed successfully', 'Registerbook Closed');

                    let lang = languageService.get('Register Close');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');

                    localStorage.setItem("isRegisterOpen", false);
                    localStorage.setItem("registerHistoryId", null);
                    localStorage.setItem("openRegister", null);

                    window.location.reload(true);
                }
            });
        }

        return false;
    }

   $scope.add = function(register){
        $('#LoaderDiv').show();
        registerDataService.add(register).then(function (response) {
            if (response!=null) {
                $('#LoaderDiv').hide();
             /*  toastr.success('Registerbook has been added successfully', 'Registerbook Added');
              let lang = languageService.get('Register Added');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'outlet';},200);
            } else {
                $('#LoaderDiv').hide();
                toastr.error(response.Exception);
            }
        });
        return false;
    }

    $scope.OpenRegister = function () {
        $('#LoaderDiv').show();
        let data = {
            register_id: $scope.SelectedRegister,
            opening_balance: $scope.OpeningBalance,
            opening_notes: $scope.OpeningNotes
        };

        registerDataService.open(data).then(function (results) {
            if (results > 0) {
                $('#LoaderDiv').hide();
                localStorage.setItem("isRegisterOpen", true);
                localStorage.setItem("outletId", $scope.SelectedOutlet);
                localStorage.setItem("registerHistoryId", results);
                localStorage.setItem("openRegister", $scope.SelectedRegister);
                window.location.href = 'sale#/main';
                $scope.IsRegisterOpen = true;
               /* toastr.success('Register has been opened successfully', 'Register Status');
                let lang = languageService.get('Register Open');
                   console.log(lang);*/
                    toastr.success(results.Message,'Success');
            } else if(results.Errors) {
                toastr.error(results.Errors);
            } else {
               /* toastr.error('Unable to open register. Please try again later', 'Register Status');
                let lang = languageService.get('Register Error');
                   console.log(lang);*/
                    toastr.error(results.Message,'Error');
            }
            
        });    

        $('#myModalCloser').modal('hide');
        return false;
    }

    //$scope.GetRegisters();

   $scope.init = function () {

        registerDataService.get_all().then(function (response) {
                $scope.viewModel.registers = response.Payload;
            });
    }

    $scope.edit = function (register) {
        registerDataService.edit(register).then(function(response) {
            if (response!=null) {
               /* toastr.success('Registerbook has been updated successfully', 'Registerbook Updated');
                let lang = languageService.get('Register Edited');
                   console.log(lang);*/
                    toastr.success(register.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'outlet';},2000);
            }
            else{
                toastr.error(response.Exception);
            }
        });
        return false;
    }

   $scope.deleteRegister = function (register) {
        registerDataService.delete_registerbook(register).then(function (response) {
            if (response.IsValid) {
              /*  toastr.success('Registerbook has been deleted successfully', 'Registerbook Deleted');
                let lang = languageService.get('Register Deleted');
                   console.log(lang);*/
                    toastr.success(register.Message,'Success');
                setTimeout(function(){window.location.href = BASE_URL + 'outlet';},2000);
            } else {
                toastr.error(response.Exception)
            }
        });
    }


    $scope.showeditdata = function () {
        registerDataService.get(urlService.getUrlPrams().id)
	 		.then(function (register) {
	 		    $scope.register = register;
	 		});
    }

    $scope.showHistoryData = function () {
        registerDataService.get_registerbook_history(urlService.getUrlPrams().id)
            .then(function (response) {
                $scope.registerHistory = response.registerHistory;
            });

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
        
    }

    // $scope.getValue = function (a,b) {
    //     return $scope[a +'.'+ b];
    // }
})