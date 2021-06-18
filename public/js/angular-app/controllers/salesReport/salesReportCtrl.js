posApp.filter("toArray", function(){
    return function(obj) {
        var result = [];
        angular.forEach(obj, function(val, key) {
            result.push(val);
        });
        return result;
    };
});

posApp.controller('salesReportCtrl', function($scope, $rootScope, salesReportDataService, urlService) {

    $scope.margin = [];

    $scope.init = function() {

        $scope.getCurrency();

        $scope.getDecimal();

        setTimeout(() => {
            $('.select2').select2();
        }, 500);

        salesReportDataService.get_all().then(function(report) {
            $scope.records = [];

            $scope.TotalSale = report.total[0].sell;
            $scope.TotalCost = report.total[0].cost;
            $scope.TotalProfit = report.total[0].profit;
            $scope.TotalMargin = report.total[0].margin;

            $scope.year = report.sells[report.sells.length - 1].year;

            $scope.weeks = report.weeks;
            $scope.data = report.sells;
            $scope.reportType = 'Summary';
            $scope.colspan = report.weeks.length;
        })
    }

    $scope.Clear = function() {
        $scope.DateRange = $scope.ReportType = null;
        $scope.ReportType = 'summary';
        $scope.init();
    }

    $scope.Search = function() {

        let filters = {};
        filters.type = $scope.ReportType;
        
        if ($scope.DateRange != null && $scope.DateRange.length > 0) {
            let dates = $scope.DateRange.split(' - ');
            filters.start_date = dates[0];
            filters.end_date = dates[1];
        }

        $scope.TotalSale = null;
        $scope.TotalCost = null;
        $scope.TotalProfit = null;
        $scope.TotalMargin = null;

        $scope.year = null;

        $scope.weeks = null;
        $scope.data = null;
        $scope.reportType = null;
        $scope.colspan = null;        
        $scope.reportType = null;
        $scope.records = [];

        if ($scope.ReportType == 'summary') {
            $scope.getSummary(filters);
        }else{
            $scope.getSummary(filters);
            // $scope.getDetails(filters);
        }
    }

    $scope.getSummary = function(filters)
    {
        salesReportDataService.get_all(filters).then(function(report) {
            $scope.records = [];

            if(report.total != undefined){
                $scope.TotalSale = report.total[0].sell;
                $scope.TotalCost = report.total[0].cost;
                $scope.TotalProfit = report.total[0].profit;
                $scope.TotalMargin = report.total[0].margin;
            }

            if(report.sells != undefined){
                $scope.year = report.sells[report.sells.length - 1].year;
            }

            $scope.weeks = report.weeks;
            $scope.data = report.sells;
            $scope.reportType = 'Summary';

            if(report.weeks != undefined){
                $scope.colspan = report.weeks.length;
            }

            if(report.record_array != undefined && report.record_array.length > 0){
                $scope.records = report.record_array;
            }
        }).then(()=>{
              if ($scope.ReportType != 'summary') {    
                    $scope.getDetails(filters);
                }
            });
    }

    $scope.getDetails = function(filters)
    {
        if($scope.ReportType != 'summary'){
            salesReportDataService.filterByReport(filters).then((reportFilter)=>{
                $scope.reportType = reportFilter.reportType;
                $scope.records = reportFilter.record_array;
            });
        }
    }
});


posApp.filter('getMondayOfWeek', function() {
    return function(input) {
        return input ? moment().day("Monday").week(input).format("MMM Do") : "";
    }
});