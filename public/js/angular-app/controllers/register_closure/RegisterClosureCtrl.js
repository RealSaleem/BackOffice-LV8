posApp.controller('RegisterClosureCtrl', function ($scope, $rootScope, registerClosureDataService,registerDataService,outletDataService,urlService) {

    $scope.filters = { status: 1 , register_id : null};
    $scope.Initialize = function () {
    $scope.getCurrency();

    $scope.getDecimal();
    }

    $scope.voucher = {};
    $scope.cash = {};
    $scope.credit = {};

    $scope.Initialize();
    setTimeout(() => {  $('.select2').select2();  },500);
   $scope.init = function () {
        // var test = [];
        registerClosureDataService.get_all(null).then(function(register_closure){
            $scope.register_closure = register_closure;
        });
        // registerDataService.get_all().then(function(allRegisters){
        //     $scope.registers = allRegisters;
        // });
        outletDataService.get_user_outlets().then(function(outlets){
            $scope.outlets = outlets;
        })
    }

    $scope.GetRegisters = function()
    {
        let outlet = $scope.outlets.filter((outlet) => {
            if($scope.OutletId == outlet.id){
                return outlet;
            }
        })[0];
        // console.log(outlet,$scope.OutletId);

        if(outlet){
            $scope.registers = [];
            $scope.registers = outlet.registers;
        }
    }

    $scope.Clear = function() {
        $scope.OutletId = $scope.registers = null;
        $scope.init();
    }

    $scope.applyFilters = function(filters){
        getRegisters(filters);
    }
    
    $scope.convertInt = function(str) {
    var num = parseInt(str);
    return num;
}
    function getRegisters(filters){
        //$scope.fetchingProducts = true;
        // console.log(filters);
        if (filters.register_id != null) {
            registerClosureDataService.list(filters).then(function(response){
            $scope.register_closure = response;
            });
        }
        if (filters.register_id == null) {
            registerClosureDataService.get_all({outlet_id:$scope.OutletId}).then(function(register_closure){
                $scope.register_closure = register_closure;
            });
        }
    }
    $scope.loadData = function (){
        registerClosureDataService.get({id:urlService.getUrlPrams().id,history_id:urlService.getUrlPrams().history_id})
        .then(function(summary){
                $scope.total = summary.total;
                $scope.sub_total = summary.sub_total;
                $scope.discount = summary.discount;

                $scope.cash.total = summary.cash_total;
                $scope.cash.sub_total = summary.cash_sub_total;
                $scope.cash.discount = summary.cash_discount;

                $scope.credit.total = summary.credit_total;
                $scope.credit.sub_total = summary.credit_sub_total;
                $scope.credit.discount = summary.credit_discount;

                $scope.voucher.garbage = null;
                $scope.voucher.total = summary.voucher_total;
                $scope.voucher.sub_total = summary.voucher_sub_total;
                $scope.voucher.discount = summary.voucher_discount;

                $scope.cash_flows = summary.cash_flow;
                $scope.register = summary.register;
                $scope.register_history = summary.register.register_history[0];
                // console.log(summary);

            });
    }
})