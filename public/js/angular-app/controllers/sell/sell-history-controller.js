posApp.controller('SellHistoryCtrl', function ($scope, $rootScope, orderDataService,customerDataService,registerDataService,urlService) {


$scope.viewModel = {};
    $scope.Outlets = [];
    $scope.OutletId = 0;
    $scope.Customer = '';
    $scope.UserId = 0;
    $scope.RegisterId = 0;
    $scope.DateRange = null;
    $scope.Users = [];
    $scope.Registers = [];
    $scope.OutletsData = [];
    $scope.OutletsDataHolder = [];
    //$scope.viewModel.customers = [];
    $scope.Orders = [];
    $scope.Links = null;
    $scope.Next = null;
    $scope.Previous = null;
    $scope.Amount = null;


    $scope.Search = function () {

        let data = {};

        if($scope.OutletId != null && $scope.OutletId > 0){
            data.outlet_id = $scope.OutletId;
        }

        if($scope.UserId != null && $scope.UserId > 0){
            data.user_id = $scope.UserId;
        }

        if($scope.Show != null){
            data.status = $scope.Show;
        }

        if($scope.RegisterId != null && $scope.RegisterId > 0){
            data.register_id = $scope.RegisterId;
        }

        if($scope.Customer != null && $scope.Customer.length > 0){
            data.customer = $scope.Customer;
        }

        if($scope.DateRange != null && $scope.DateRange.length > 0){
            let dates = $scope.DateRange.split(' - ');
            data.start_date = dates[0];
            data.end_date = dates[1];
        }

        if ($scope.Amount != null && $scope.Amount > 0) {
            data.amount = $scope.Amount;
        }

        if ($scope.Discount != null) {
            data.discount = $scope.Discount;
        }
        $scope.GetOrders(data);
    }

    $scope.Clear = function(){
        console.log('Clear');
        $scope.OutletId = $scope.UserId = $scope.Show = $scope.RegisterId = $scope.Customer = 
        $scope.DateRange = $scope.Amount = $scope.Discount = null;

        $scope.init();
    }

    $scope.Filter = function () {

        if ($scope.OutletId > 0) {
            let outlet = $scope.OutletsDataHolder.filter((outlet) => {
                return outlet.id == $scope.OutletId;
            });

            if (outlet[0] != null) {
                $scope.Users = [];

                outlet[0].users.map((user) => {
                    $scope.Users.push(user);
                });

                $scope.Registers = [];

                outlet[0].registers.map((register) => {
                    $scope.Registers.push(register);
                });
            }
        } else {
            $scope.GetAllUsers();
            $scope.GetAllRegisters();
        }
    }

    $scope.init = function () {
        let data = {};
        customerDataService.get_all().then(function (customers) {
                $scope.viewModel.customers = customers;
            });

        orderDataService.get_filters().then(function (response) {
            $scope.OutletsData = response;
            $scope.OutletsDataHolder = response;
            $scope.GetOutlets();
            $scope.GetAllUsers();
            $scope.GetAllRegisters();
            data.customer = urlService.getUrlPrams().id;
            $scope.GetOrders(data);
            //$scope.GetOrders(null);
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

    $scope.GetOrders = function (params) {
        orderDataService.get_orders(params).then(function (response) {
            
            if (response.data != undefined) {
                $scope.Orders = [];
                $scope.Orders = response.data.data;
                $scope.Links = response.pagination;
                $scope.Next = response.data.next_page_url;
                $scope.Previous = response.data.prev_page_url;
            }
        });
    }

    $scope.get_void = function (data) {
        console.log(data);
        registerDataService.void_sale(data).then(function (response){
            toastr.success(response.Message, 'Success');
                setTimeout(function(){window.location.reload();},2000);
        });
    }

    $scope.showVoidModal = function(sale){
        $scope.selectedSale = sale;
        $('#myModalvoid').modal('show')
    }

    $scope.Go = function (link) {
        if (link != null) {
            let params = link.split('?');

            let data = params[1].split('&');
            let queryparams = {};
            
            if (data.length > 0) {
                data.map((item) => {
                    let keyvalue = item.split('=');
                    queryparams[keyvalue[0]] = keyvalue[1];
                });
            }

            $scope.GetOrders(queryparams);
        }
    }

    $scope.GetOutlets = function () {
        if ($scope.OutletsDataHolder.length > 0) {
            $scope.Outlets = [];
            $scope.OutletsDataHolder.map((outlet) => {
                $scope.Outlets.push({ id: outlet.id, name: outlet.name });
            });
        }
    }

    $scope.GetAllUsers = function () {
        if ($scope.OutletsDataHolder.length > 0) {
            $scope.Users = [];
            $scope.OutletsDataHolder.map((outlet) => {
                outlet.users.map((user) => {
                    $scope.Users.push(user);
                });
            });
        }
    }

    $scope.GetAllRegisters = function () {
        if ($scope.OutletsDataHolder.length > 0) {
            $scope.Registers = [];
            $scope.OutletsDataHolder.map((outlet) => {
                outlet.registers.map((register) => {
                    $scope.Registers.push(register);
                });
            });
        }
    }

    $scope.GetDescription = function (item) {

        return item.payment_method + ' - ' + item.created_at + ' - ' + item.register.name;
    }

    $scope.init();
})