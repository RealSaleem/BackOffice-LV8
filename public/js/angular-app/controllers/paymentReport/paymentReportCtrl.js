posApp.controller('paymentReportCtrl', function($scope,$rootScope,paymentReportDataService,urlService){

	// $scope.viewModel = {};
	filter = {};

	$scope.init = function(){

		$scope.getCurrency();

		$scope.getDecimal();
		
		paymentReportDataService.get_all().then(function(report){
			$scope.weeks = report.weeks;
			$scope.data = report.data;

			
			$scope.cashTotal = 0;
			$scope.cardTotal = 0;

			if($scope.data != null &&$scope.data.length > 0 )
			{
				$scope.data.map((item) => {
					$scope.cashTotal += parseFloat(item.cash);
					$scope.cardTotal += parseFloat(item.credit_card);
				});
			}

			$scope.year = $scope.weeks[$scope.weeks.length - 1].year;

			// $scope.cashTotal = report.data[0].cash+report.data[1].cash+report.data[2].cash+report.data[3].cash+report.data[4].cash;
			// $scope.cardTotal = report.data[0].credit_card+report.data[1].credit_card+report.data[2].credit_card+report.data[3].credit_card+report.data[4].credit_card;

			// $scope.cashTotal = report.data[0].cash;
			// $scope.cardTotal = report.data[0].credit_card;

			$scope.week1Total = parseFloat(report.data[0].cash)+parseFloat(report.data[0].credit_card);

			if(report.data[1]){
				$scope.week2Total = parseFloat(report.data[1].cash)+parseFloat(report.data[1].credit_card);
			}

			if(report.data[2]){
				$scope.week3Total = parseFloat(report.data[2].cash)+parseFloat(report.data[2].credit_card);
		
			}

			if(report.data[3]){
				$scope.week4Total = parseFloat(report.data[3].cash)+parseFloat(report.data[3].credit_card);
			}


			if(report.data[4]){
				$scope.week5Total = parseFloat(report.data[4].cash)+parseFloat(report.data[4].credit_card);
			}						

			$scope.Total = parseFloat($scope.week1Total)+parseFloat($scope.week2Total)+parseFloat($scope.week3Total)+parseFloat($scope.week4Total);

		})	
	}
	$scope.Clear = function() {
        $scope.DateRange  = null;
        $scope.init();
    	}

	$scope.Search = function(){

		$scope.getCurrency();
		let data = {};
        if ($scope.DateRange == null) {
        	$scope.init();
        	return;
        }
        if($scope.DateRange != null && $scope.DateRange.length > 0){
            let dates = $scope.DateRange.split(' - ');
            data.start_date = dates[0];
            data.end_date = dates[1];
        }
		paymentReportDataService.filterDate(data).then(function(filter){
			$scope.cashTotal = 0;
			$scope.cardTotal = 0;
			//$scope.voucherTotal = 0; 
			$scope.Total = 0;

			$scope.data = null;

			// $scope.cash = null;
			// $scope.credit = null;
			//$scope.voucher = null;

			$scope.weeks = null;
			$scope.year = null;
			$scope.week1Total = null;
			$scope.week2Total = null;
			$scope.week3Total = null;
			$scope.week4Total = null;
			$scope.week5Total = null;

			 filter.data = {};
			 for (var i = 0; i < filter.length; i++) {
			 	if (filter[i].method == 'Cash') {
			 		for (var j = 0; j < filter[i].data.length; j++) {
			 			$scope.cashTotal = $scope.cashTotal + parseInt(filter[i].data[j].total);
			 		}
			 	}
			 	if (filter[i].method == 'Credit Card') {
			 		for (var j = 0; j < filter[i].data.length; j++) {
			 			$scope.cardTotal = $scope.cardTotal + parseInt(filter[i].data[j].total);
			 		}
			 	}
			 	// if (filter[i].method == 'Voucher') {
			 	// 	for (var j = 0; j < filter[i].data.length; j++) {
			 	// 		$scope.voucherTotal = $scope.voucherTotal + parseInt(filter[i].data[j].total);
			 	// 	}
			 	// }
			 }
			$scope.Total = $scope.cashTotal + $scope.cardTotal;

		})	
	}


});

posApp.filter('getMondayOfWeek', function() {
  return function(input) {
    return input ? `${moment().week(input).startOf('week').format("MMM Do")} - ${moment().week(input).endOf('week').format("MMM Do")}` : "";
  }
});
