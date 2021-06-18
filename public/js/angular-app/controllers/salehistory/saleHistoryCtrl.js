
posApp.controller('saleHistoryCtrl', function ($scope,languageService, $rootScope, orderDataService,customerDataService,registerDataService,urlService) {
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
    $scope.identity = null;
   setTimeout(() => {  $('.select2').select2();  },500);


    $scope.Search = function () {

        let data = {};

        if($scope.OutletId != 0 && $scope.OutletId > 0){
            data.outlet_id = $scope.OutletId;
        }

        if($scope.UserId != 0 && $scope.UserId > 0){
            data.user_id = $scope.UserId;
        }

        if($scope.Show != 0){
            data.status = $scope.Show;
        }

        if($scope.RegisterId != 0 && $scope.RegisterId > 0){
            data.register_id = $scope.RegisterId;
        }

        if($scope.Customer != 0 && $scope.Customer.length > 0){
            data.customer = $scope.Customer;
        }

        if($scope.DateRange != null && $scope.DateRange.length > 0){
            let dates = $scope.DateRange.split(' - ');
            data.start_date = dates[0];
            data.end_date = dates[1];
        }

        if ($scope.Amount != 0 ) {
            data.amount = $scope.Amount;
        }

        if ($scope.identity != null ) {
            data.identity = $scope.identity;
        }

        if ($scope.Discount != null) {
            data.discount = $scope.Discount;
        }
        $scope.GetOrders(data);
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
    $scope.Clear = function(){
        $scope.Registers = [];
        $scope.Users = [];

        setTimeout(() => { 
            $("#R_reset").select2().trigger("change");
            $("#U_reset").select2().trigger("change");
        },100);


        $scope.OutletId     = setTimeout(() => { $("#O_reset").select2().trigger("change");  },100);
        $scope.Show         = setTimeout(() => { $("#S_reset").select2().trigger("change");  },100);
        $scope.DateRange='';
         $scope.Customer    = setTimeout(() => { $("#C_reset").select2().trigger("change");  },100);
          $scope.Amount='';
           $scope.Discount='0';
        // $scope.Model = '';

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
            //$scope.GetAllUsers();
            //$scope.GetAllRegisters();

            if (urlService.getUrlPrams().user_id != null) {
            data.user_id = urlService.getUrlPrams().user_id;
            }
            
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

        $scope.getDecimal();
        
    }

    $scope.SetFilters = function()
    {
        
        let outlet = $scope.Outlets.filter((outlet) => {
            if($scope.OutletId == outlet.id){
                return outlet;
            }
        })[0];

        if(outlet){
            $scope.Registers = [];
            $scope.Users = [];

            $scope.Registers = outlet.registers;
            $scope.Users = outlet.users;
        }else{
            /*
            $scope.Outlets.map((outlet) => {
                outlet.registers.map((register) => {
                    $scope.Registers.push(register);
                });

                outlet.users.map((user) => {
                    $scope.Users.push(user);
                });                
            }) ;
            */           
        }
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
            // //toastr.success('Order has been voided successfully', 'Order Voided');
            //        console.log(lang);
            // let lang = languageService.get('Order Voided Sales');
                    toastr.success(response.Message,'Success');
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
                //$scope.Outlets.push({ id: outlet.id, name: outlet.name });
                $scope.Outlets.push(outlet);
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

    $scope.Return = function(data){
        result = [];
        if(!localStorage.hasOwnProperty("openRegister") || localStorage.getItem('openRegister') == null || localStorage.getItem('openRegister') == 0 ){
           
                   /* //toastr.error('Kindly open register first');
 let lang = languageService.get('Open Register');
                   console.log(lang);*/
                    toastr.error(data.Message,'Error'); 
            return;
        }
        console.log(data);
        var qtyExceeds = false;
        data.orderitems.forEach(item => {
            if(item.quantity > item.qtylimit && item.hasOwnProperty('itemChecked') && item.itemChecked){
                qtyExceeds = true;
                return;
            }
            else if('itemChecked' in item  && item.itemChecked){
                result.push(item);
            }            
        });
        if(qtyExceeds){
            /*toastr.error('Invalid quantity');
 let lang = languageService.get('Invalid Quantity');
                   console.log(lang);*/
                    toastr.error(data.Message,'Error'); 
            return;
        }
        if(result.length == 0){
           /* toastr.error('No item selected');
 let lang = languageService.get('Select Item');
                   console.log(lang);*/
                   toastr.error(data.Message,'Error'); 
            return;
        }        
        else{
            data.orderitems = result;
            sessionStorage.setItem("Order",JSON.stringify(data));
            window.location.href = BASE_URL + 'sale#main?id=' + data.id;
        }
        
    }

    $scope.init();
})