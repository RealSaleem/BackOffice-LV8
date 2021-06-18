posApp.controller('DashboardCtrl', function($scope,$rootScope,dashboardDataService,urlService){
	var labels = [];
	var data = [];

	var product_labels = [];
	var product_data = [];
	var product_series = [];

	$scope.max_sell = 0;
	$scope.min_sell = 0;

	$scope.product_max_sell = 0;
	$scope.product_min_sell = 0;
	
	$scope.hours = false;
	$scope.days = false;
	$scope.years = false;
	$scope.months = false;
	$scope.weeks = false;
	$scope.payment_method = false;

	$scope.product_days = false;
	$scope.product_months = false;
	$scope.product_years = false;
	$scope.product_weeks = false;
	$scope.product_hours = false;


	var month = new Array("Jan","Feb","Mar","Apr","May","Jun",
							"Jul","Aug","Sep","Oct","Nov","Dec");
	$scope.init = function(){
		$scope.days = true;
		$scope.years = false;
		$scope.months = false;
		$scope.weeks = false;
		$scope.hours = false;
		$scope.payment_method = false;

		$scope.product_days = true;
		$scope.product_years = false;
		$scope.product_months = false;
		$scope.product_hours = false;
		$scope.product_weeks = false;


		$scope.getCurrency();
		$scope.getDecimal();
		dashboardDataService.get_all().then(function(response){
			for (var i = 0; i <response.length; i++) {
				var n = month[response[i].month-1];
				labels[i] = response[i].day+'-'+n;
				data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data.max();
			$scope.min_sell = data.min();

		});

		dashboardDataService.get_all_product().then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
		});

		$scope.labels = labels;
		  $scope.series_sell = ['Sell'];
		  $scope.data = [
		  	data
		  ];

		  $scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		  $scope.onClick = function (points, evt) {
		  };
		  $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }];
		  $scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.getGraphData = function(day){
		var labels = [];
		var data = [];
		$scope.total_sell = 0;
		$scope.avg_sell = 0;
		$scope.avg_sell_value = 0;
		dashboardDataService.get_graph_days(day).then(function(response){
			console.log(response);
			for (var i = 0; i <response.length; i++) {
				var n = month[response[i].month-1];
				labels[i] = response[i].day+'-'+n;
				data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
				$scope.max_sell = data.max();
				$scope.min_sell = data.min();	
		});

		$scope.labels = labels;
		// $scope.labels = ["January", "February", "March", "April", "May", "June", "July"];
		  $scope.series = ['Sell'];
		  $scope.data = [
		  	data
		    // [65, 59, 80, 81, 56, 55, 40]
		  ];
		  $scope.onClick = function (points, evt) {
		  };
		  $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }];
		  $scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left'
		        }
		      ]
		    }
		  };
	}

	$scope.getProductGraph = function(day){
		var product_labels = [];
		var product_data = [];
		 $scope.product_total_sell = 0;
		 $scope.product_avg_sell = 0;
		 $scope.product_avg_sell_value = 0;
		dashboardDataService.get_product_graph_days(day).then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
				 $scope.product_total_sell = $scope.product_total_sell + parseInt(response[i].revenue);
			}

			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};

			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}

			 var temp = $scope.product_total_sell/response.length;
			 $scope.product_avg_sell = temp.toFixed(3);
		});

		$scope.product_labels = product_labels;
		  $scope.series_product = ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];
		  $scope.onClick = function (points, evt) {
		  };
		  $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }];
		  $scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.sellByYear = function(){
		$scope.hours = false;
		$scope.days = false;
		$scope.years = true;
		$scope.months = false;
		$scope.weeks = false;
		$scope.payment_method = false;
		var labels = [];
		var data = [];

		$scope.total_sell = 0;
		$scope.avg_sell = 0;
		$scope.avg_sell_value = 0;
		dashboardDataService.get_sell_by_year().then(function(response){
			for (var i = 0; i <response.length; i++) {
				labels[i] = response[i].year;
				data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data.max();
			$scope.min_sell = data.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Sell'];
			  $scope.data = [
			  	data
			  ];
		});
	}

	$scope.productByYear = function(){
		$scope.product_days = false;
		$scope.product_months = false;
		$scope.product_years = true;
		$scope.product_weeks = false;
		$scope.product_hours = false;

		var product_labels = [];
		var product_data = [];
		 $scope.product_max_sell = 0;
		 $scope.product_min_sell = 0;
		dashboardDataService.get_product_by_year().then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
			
		});

		$scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		 $scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.sellByMonth = function(){
		$scope.hours = false;
		$scope.days = false;
		$scope.years = false;
		$scope.months = true;
		$scope.weeks = false;
		$scope.payment_method = false;

		var labels = [];
		var data = [];

		$scope.total_sell = 0;
		$scope.avg_sell = 0;
		$scope.avg_sell_value = 0;
		dashboardDataService.get_sell_by_month().then(function(response){
			for (var i = 0; i <response.length; i++) {
				var n = month[response[i].month-1];
				labels[i] = n+'-'+response[i].year;
				data[i] = parseInt(response[i].sell);
				}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data.max();
			$scope.min_sell = data.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Sell'];
			  $scope.data = [
			  	data
			  ];
		});
	}

	$scope.productByMonth = function(){
		$scope.product_days = false;
		$scope.product_years = false;
		$scope.product_months = true;
		$scope.product_weeks = false;
		$scope.product_hours = false;

		var product_labels = [];
		var product_data = [];
		 $scope.product_max_sell = 0;
		 $scope.product_min_sell = 0;
		dashboardDataService.get_product_by_month().then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
		});

		$scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		$scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.sellByWeek = function(){
		$scope.hours = false;
		$scope.days = false;
		$scope.years = false;
		$scope.months = false;
		$scope.weeks = true;
		$scope.payment_method = false;

		var labels = [];
		var data = [];

		$scope.total_sell = 0;
		$scope.avg_sell = 0;
		$scope.avg_sell_value = 0;
		dashboardDataService.get_sell_by_week().then(function(response){
			for (var i = 0; i <response.length; i++) {
				labels[i] = response[i].week+'-week';
				data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data.max();
			$scope.min_sell = data.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Sell'];
			  $scope.data = [
			  	data
			  ];
		});
	}

	$scope.productByWeek = function(){
		$scope.product_days = false;
		$scope.product_years = false;
		$scope.product_months = false;
		$scope.product_weeks = true;
		$scope.product_hours = false;
		var product_labels = [];
		var product_data = [];
		 $scope.product_max_sell = 0;
		 $scope.product_min_sell = 0;
		dashboardDataService.get_product_by_week().then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};

			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
		});

		$scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		$scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.sellByDay = function(){
		$scope.days = true;
		$scope.years = false;
		$scope.months = false;
		$scope.weeks = false;
		$scope.hours = false;
		$scope.payment_method = false;

		$scope.getCurrency();
		$scope.getDecimal();
		dashboardDataService.get_all().then(function(response){
			for (var i = 0; i <response.length; i++) {
				var n = month[response[i].month-1];
				labels[i] = response[i].day+'-'+n;
				data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			
				$scope.max_sell = data.max();
				$scope.min_sell = data.min();
		});

		$scope.labels = labels;
		  $scope.series_sell = ['Sell'];
		  $scope.data = [
		  	data
		  ];

		  $scope.onClick = function (points, evt) {
		    
		  };
		  $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }];
		  $scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	
	}

	$scope.productByDay = function(){

		$scope.product_days 		= true;
		$scope.product_months 		= false;
		$scope.product_years 		= false;
		$scope.product_weeks 		= false;
		$scope.product_hours 		= false;

		$scope.getCurrency();
		$scope.getDecimal();
		dashboardDataService.get_all_product().then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
		});

		  $scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		  $scope.onClick = function (points, evt) {
		  };
		  $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }];
		  $scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.sellByHour = function(){
		$scope.hours = true;
		$scope.days = false;
		$scope.years = false;
		$scope.months = false;
		$scope.weeks = false;
		$scope.payment_method = false;

		var labels = [];
		var data = [];

		$scope.total_sell = 0;
		$scope.avg_sell = 0;
		$scope.avg_sell_value = 0;
		dashboardDataService.get_sell_by_hour().then(function(response){
			for (var i = 0; i <response.length; i++) {
				labels[i] = response[i].time;
				data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data.max();
			$scope.min_sell = data.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Sell'];
			  $scope.data = [
			  	data
			  ];
		});
	}

	$scope.productByHour = function(){
		$scope.product_days = false;
		$scope.product_years = false;
		$scope.product_months = false;
		$scope.product_weeks = false;
		$scope.product_hours = true;

		var product_labels = [];
		var product_data = [];
		 $scope.product_max_sell = 0;
		 $scope.product_min_sell = 0;
		dashboardDataService.get_product_by_hour().then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
		});

		$scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		$scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.sellByPaymentMethod = function(){
		$scope.hours = false;
		$scope.days = false;
		$scope.years = false;
		$scope.months = false;
		$scope.weeks = false;
		$scope.payment_method = true;

		 var labels = [];
		 var data = [];
		 var data_cash = [];
		 var data_credit = [];

		 $scope.total_sell = 0;
		 $scope.avg_sell = 0;
		 $scope.avg_sell_value = 0;
		dashboardDataService.get_sell_by_payment_method().then(function(response){
			for (var i = 0; i <response.cash.length; i++) {
				var n = month[response.cash[i].month-1];
				labels[i] = n+'-'+response.cash[i].year;
				data_cash[i] = parseInt(response.cash[i].sell);
			}
			for (var i = 0; i <response.credit_card.length; i++) {
				var n = month[response.credit_card[i].month-1];
				labels[i] = n+'-'+response.credit_card[i].year;
				data_credit[i] = parseInt(response.credit_card[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data_cash.max();
			$scope.min_sell = data_cash.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Cash','Credit Card'];
			  $scope.data = [
			  	data_cash,
			  	data_credit
			  ];
		});
	}

	$scope.paymentMethodSelectedMonth = function(months){
		$scope.hours = false;
		$scope.days = false;
		$scope.years = false;
		$scope.months = false;
		$scope.weeks = false;
		$scope.payment_method = true;

		 var labels = [];
		 var data = [];
		 var data_cash = [];
		 var data_credit = [];

		 $scope.total_sell = 0;
		 $scope.avg_sell = 0;
		 $scope.avg_sell_value = 0;
		dashboardDataService.payment_method_selected_month(months).then(function(response){
			for (var i = 0; i <response.cash.length; i++) {
				var n = month[response.cash[i].month-1];
				labels[i] = n+'-'+response.cash[i].year;
				data_cash[i] = parseInt(response.cash[i].sell);
			}
			for (var i = 0; i <response.credit_card.length; i++) {
				var n = month[response.credit_card[i].month-1];
				labels[i] = n+'-'+response.credit_card[i].year;
				data_credit[i] = parseInt(response.credit_card[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data_cash.max();
			$scope.min_sell = data_credit.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Cash','Credit Card'];
			  $scope.data = [
			  	data_cash,
			  	data_credit
			  ];
		});
	}

	$scope.sellByYearSelected = function(year){
		$scope.days = false;
		$scope.years = true;
		var labels = [];
		var data = [];

		$scope.total_sell = 0;
		$scope.avg_sell = 0;
		$scope.avg_sell_value = 0;
		dashboardDataService.get_sell_by_year_selected(year).then(function(response){
			for (var i = 0; i <response.length; i++) {
				labels[i] = response[i].year;
				data[i] = parseInt(response[i].sell);
				}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data.max();
			$scope.min_sell = data.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Sell'];
			  $scope.data = [
			  	data
			  ];
		});
	}

	$scope.productYearSelected = function(year){
		$scope.product_days = false;
		$scope.product_years = true;
		var product_labels = [];
		var product_data = [];
		 $scope.product_max_sell = 0;
		 $scope.product_min_sell = 0;
		dashboardDataService.get_product_by_year_selected(year).then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
		});

		$scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		$scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.sellByMonthSelected = function(months){
		$scope.days = false;
		$scope.years = false;
		$scope.months = true;
		var labels = [];
		var data = [];

		$scope.total_sell = 0;
		$scope.avg_sell = 0;
		$scope.avg_sell_value = 0;
		dashboardDataService.get_sell_by_month_selected(months).then(function(response){
			for (var i = 0; i <response.length; i++) {
				var n = month[response[i].month-1];
				labels[i] = n+'-'+response[i].year;
				data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data.max();
			$scope.min_sell = data.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Sell'];
			  $scope.data = [
			  	data
			  ];
		});
	}

	$scope.productMonthSelected = function(month){
		$scope.product_days = false;
		$scope.product_years = false;
		$scope.product_months = true;

		var product_labels = [];
		var product_data = [];
		 $scope.product_max_sell = 0;
		 $scope.product_min_sell = 0;
		dashboardDataService.get_product_by_month_selected(month).then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
		});

		$scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		$scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.sellByWeekSelected = function(week){
		$scope.hours = false;
		$scope.days = false;
		$scope.years = false;
		$scope.months = false;
		$scope.weeks = true;
		$scope.payment_method = false;


		var labels = [];
		var data = [];

		$scope.total_sell = 0;
		$scope.avg_sell = 0;
		$scope.avg_sell_value = 0;
		dashboardDataService.get_sell_by_week_selected(week).then(function(response){
			console.log(response);
			for (var i = 0; i <response.length; i++) {
				labels[i] = response[i].week+'-week';
				data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data.max();
			$scope.min_sell = data.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Sell'];
			  $scope.data = [
			  	data
			  ];
		});
	}

	$scope.productWeekSelected = function(week){
		$scope.product_days = false;
		$scope.product_years = false;
		$scope.product_months = false;
		$scope.product_weeks = true;

		var product_labels = [];
		var product_data = [];
		 $scope.product_max_sell = 0;
		 $scope.product_min_sell = 0;
		dashboardDataService.get_product_by_week_selected(week).then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
		});

		$scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		$scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

	$scope.sellByHourSelected = function(hour){
		$scope.hours = true;
		$scope.days = false;
		$scope.years = false;
		$scope.months = false;
		$scope.weeks = false;

		var labels = [];
		var data = [];

		$scope.total_sell = 0;
		$scope.avg_sell = 0;
		$scope.avg_sell_value = 0;
		dashboardDataService.get_sell_by_hour_selected(hour).then(function(response){
			for (var i = 0; i <response.length; i++) {
				labels[i] = response[i].time;
				data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			$scope.max_sell = data.max();
			$scope.min_sell = data.min();

			$scope.labels = labels;
			  $scope.series_sell = ['Sell'];
			  $scope.data = [
			  	data
			  ];
		});
	}

	$scope.productHourSelected = function(hour){
		$scope.product_days = false;
		$scope.product_years = false;
		$scope.product_months = false;
		$scope.product_weeks = false;
		$scope.product_hours = true;

		var product_labels = [];
		var product_data = [];
		 $scope.product_max_sell = 0;
		 $scope.product_min_sell = 0;
		dashboardDataService.get_product_by_hour_selected(hour).then(function(response){
			for (var i = 0; i <response.length; i++) {
				product_labels[i] = response[i].name;
				product_data[i] = parseInt(response[i].sell);
			}
			Array.prototype.max = function() {
  				return Math.max.apply(null, this);
			};
			Array.prototype.min = function() {
  				return Math.min.apply(null, this);
			};
			if (product_data.length > 0) {
				$scope.product_max_sell = product_data.max();
				$scope.product_min_sell = product_data.min();
			}
			else 
			{
				$scope.product_max_sell = 0;
				$scope.product_min_sell = 0;
			}
		});

		$scope.product_labels = product_labels;
		  $scope.series_product =  ['Sell count'];
		  $scope.product_data = [
		  	product_data
		  ];

		$scope.options = {
		    scales: {
		      yAxes: [
		        {
		          id: 'y-axis-1',
		          type: 'linear',
		          display: true,
		          position: 'left',
		          ticks: {beginAtZero:true}
		        }
		      ],
		      xAxes: [{   
		        ticks: {
		            autoSkip: false
		        }
		    }]
		    }
		  };
	}

});
