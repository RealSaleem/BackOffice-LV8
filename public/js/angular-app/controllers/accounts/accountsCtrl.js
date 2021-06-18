posApp.controller('accountsCtrl', function ($scope, $rootScope,urlService,AccountsService) {
    $scope.init = function () {
        $scope.ApplyFilters();
    }

    $scope.accountData = {};
    $scope.COGS = 0;
    $scope.Total_sales = 0;
    $scope.gross = 0;
    $scope.expenseRecord;
    $scope.incomeRecord;
    $scope.other_income=0;
    $scope.other_expense =0;
    $scope.operating_profit = 0;
    $scope.tax = 0;
    $scope.netProfit = 0;
    $scope.currency = localStorage.getItem('currency');

    $scope.ApplyFilters = function(){

        var dataSend = {
            dateFrom: $scope.accountData.dateFrom,
            dateTo: $scope.accountData.dateTo,
            outlet: $scope.accountData.outlet
        };

    $scope.reset = function(filters){
        setTimeout(() => { $("#O_reset").select2().trigger("change");  },100);
        $scope.accountData.dateFrom = '';
        $scope.accountData.dateTo = '';
        $scope.accountData.outlet = '';
        
        }
        
    	AccountsService.filter_record( dataSend ).then(function (response) {
            if (response!=null) {
                $scope.COGS = response.COGS;
                $scope.Total_sales = response.Total_sales;
                $scope.gross = $scope.Total_sales - $scope.COGS;
                $scope.expenseRecord = response.expenseledgerRecords;
                $scope.incomeRecord = response.incomeledgerRecords;
                $scope.other_income = response.Other_income;
                $scope.other_expense = 0;// response.Other_expense;

                $scope.expenseRecord.map((item) => {
                    $scope.other_expense += parseFloat(item.amount);
                });

                $scope.operating_profit = $scope.other_income - $scope.other_expense; 
                $scope.netProfit = $scope.operating_profit + $scope.gross - $scope.tax;

            } else {
                toastr.error(response.Exception)
            }
        });
	}
})