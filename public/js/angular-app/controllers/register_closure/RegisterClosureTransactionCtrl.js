posApp.controller('RegisterClosureTransactionCtrl', function ($scope, $rootScope, registerClosureDataService,registerDataService,outletDataService,customerDataService,userDataService,urlService,languageService) {

    $scope.Initialize = function () {
    $scope.getCurrency();
    $scope.getDecimal();
    }

    $scope.Initialize();

    $scope.filters = {};
    $scope.renderReceipt = false;
   $scope.init = function () {
        registerDataService.get_all().then(function(registers){
            $scope.registers = registers.registers;
            console.log(registers);
        });

        outletDataService.get_all().then(function(outlets){
            $scope.outlets = outlets;
            console.log(outlets);
        });

        customerDataService.get_all().then(function(customers){
            $scope.customers = customers;
            console.log(customers);
        });

        userDataService.get_all().then(function(users){
            $scope.users = users;
            console.log(users);
        });
    }

    $scope.printReciept = function(){
       var contents = document.getElementById("dvContents").innerHTML;
        var frame1 = document.createElement('iframe');
        frame1.name = "frame1";
        frame1.style.position = "absolute";
        frame1.style.top = "-1000000px";
        document.body.appendChild(frame1);
        var frameDoc = (frame1.contentWindow) ? frame1.contentWindow : (frame1.contentDocument.document) ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Original Reciept</title>');
        frameDoc.document.write('</head><body>');
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            document.body.removeChild(frame1);
        }, 500);
        return false;
    }

    $scope.printA4Reciept = function(){
       var contents = document.getElementById("dvA4Contents").innerHTML;
        var frame1 = document.createElement('iframe');
        frame1.name = "frame1";
        frame1.style.position = "absolute";
        frame1.style.top = "-1000000px";
        document.body.appendChild(frame1);
        var frameDoc = (frame1.contentWindow) ? frame1.contentWindow : (frame1.contentDocument.document) ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Original Reciept</title>');
        frameDoc.document.write('</head><body>');
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            document.body.removeChild(frame1);
        }, 500);
        return false;
    }

    $scope.applyFilters = function(filters){
        if(filters.customer_id!=null){
        filters.customer_id = parseInt(filters.customer_id);
    }
        console.log(filters);
        getClosureRegisters(filters);
    }

    function getClosureRegisters(filters){
        console.log(filters);
        registerClosureDataService.get_filters({id:urlService.getUrlPrams().id,
            history_id:urlService.getUrlPrams().history_id,customer_id:filters.customer_id,
            user_id:filters.user_id,identity:filters.identity,sub_total:filters.sub_total
        }).then(function(response){
            $scope.payments = response.payment;
            $scope.total_payment = response.total_payment;
            console.log(response);
        });
    }

    $scope.loadData = function(){
        registerClosureDataService.get_transaction({id:urlService.getUrlPrams().id,history_id:urlService.getUrlPrams().history_id})
        .then(function(transaction){
                if(transaction){
                    $scope.payments = transaction.payment;
                    $scope.register = transaction.register;
                    $scope.register_history = transaction.register.register_history[0];
                    $scope.total_payment = transaction.total_payment;
                    $scope.renderReceipt = true;
                }
                console.log(transaction);
            });
    }

    // function getClosureRegisters(filters){
    //     //$scope.fetchingProducts = true;
    //     console.log(filters);
    // }

    $scope.orderLedger = function(){
        registerClosureDataService.get_ledger({id:urlService.getUrlPrams().id,history_id:urlService.getUrlPrams().history_id,order_id:urlService.getUrlPrams().order_id})
        .then(function(ledger){
            $scope.register = ledger.register;
            $scope.payment = ledger.payment;
            $scope.store = ledger.store;
            $scope.payment.created_at = ledger.payment.created_at;
            $scope.orderitems = ledger.payment.orderitems;
            console.log(ledger);
        });
    }

    $scope.loadInvoiceLedger = function(invoice_id){
        registerClosureDataService.get_ledger_by_invoice(invoice_id)
        .then(function(ledger){
            $scope.register = ledger.register;
            $scope.payment = ledger.payment;
            $scope.store = ledger.store;
            $scope.payment.created_at = ledger.payment.created_at;
            $scope.orderitems = ledger.payment.orderitems;
            $scope.renderReceipt = true;
            console.log(ledger);
        });
    }    

    

    $scope.editLedger = function(){
        registerClosureDataService.get_ledger({id:urlService.getUrlPrams().id,history_id:urlService.getUrlPrams().history_id,order_id:urlService.getUrlPrams().order_id})
        .then(function(ledger){
            $scope.register = ledger.register;
            $scope.payment = ledger.payment;
            $scope.payment.created_at = new Date($scope.payment.created_at);
            $scope.orderitems = ledger.payment.orderitems;
            console.log(ledger);
        });
    }

    $scope.getRegister = function(){
        registerDataService.get_all().then(function(registers){
            $scope.registers = registers.registers;
            console.log(registers.registers);
        });
    }

    $scope.edit = function(){
        var data = {
            created_at : $scope.payment.created_at,
            id : $scope.payment.id,
            register_id : $scope.register.id,
            notes : $scope.payment.notes,
        }; 
        console.log(data);  
        registerDataService.edit_order(data).then(function (response){
           /* toastr.success('Order has been updated successfully', 'Order Updated');
            let lang = languageService.get('Order Updated');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.history.back();window.location.reload();},2000);
        });  
    }

    $scope.get_void = function (data) {
        console.log(data);
        registerDataService.void_sale(data).then(function (response){
             /* toastr.success('Order has been voided successfully', 'Order Voided');
            let lang = languageService.get('Order Voided');
                   console.log(lang);*/
                    toastr.success(response.Message,'Success');
                setTimeout(function(){window.location.reload();},2000);
        });
    }

    $scope.showVoidModal = function(sale){
        $scope.selectedSale = sale;
        $('#myModalvoid').modal('show')
    }
});

posApp.filter('getTime',function(){
    return function(input){
        let time = input.split(' ');
        return time[1];
    }
});