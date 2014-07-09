	var createShiftplanApp = angular.module('createShiftplanApp', [], function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%');
		$interpolateProvider.endSymbol('%>');
	});

	createShiftplanApp.factory( 'workers', function() {
		var workers = ['aaa','sss','ddd'];
		return{
			getWorkers: function(){ return workers; }
		}

	});

	createShiftplanApp.factory( 'timespan', function() {
		var date = new Date();
		date.setMonth(date.getMonth() + 1, 1);
		var selectedMonth = date.getMonth();
		var selectedYear = date.getFullYear();

		return {
			getMonth: function () { return date.getMonth(); },
			getTimestamp: function () { return date.getTime(); },
			setMonth: function ( month ) { date.setMonth(month); },
			incrementMonth: function ( n ) { 
				date.setMonth(date.getMonth() + n, 1);
			},			
			getYear: function () { return date.getFullYear(); },
			setYear: function ( year ) { date.setFullYear(year); },
			getDateObj: function () { return date; },
			getDateString: function () { return date.toDateString(); }, //not needed


		};
	});


	createShiftplanApp.controller('datePicker', function ($scope, timespan) {
		var date = new Date();
		$scope.months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
		$scope.selectedMonth = timespan.getMonth();
		$scope.selectedYear = timespan.getYear();

		$scope.changeMonth = function(n){
			timespan.incrementMonth(n);
		};

		$scope.changeYear = function(n){		
				timespan.setYear($scope.selectedYear+n);
		};

		$scope.$watch( function () { 
			return timespan.getTimestamp()
		}, function () {
			$scope.selectedMonth = timespan.getMonth();
			$scope.selectedYear = timespan.getYear();
		}); 	
	});


	createShiftplanApp.controller('shiftPlaner', function ($scope, timespan, workers) {
		$scope.datestring = timespan.getDateString();
		$scope.workers = workers.getWorkers();

		$scope.$watch( function () { 
			return timespan.getTimestamp()
		}, function () {
			$scope.datestring = timespan.getDateString();
		});
	});