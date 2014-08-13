var shiftWishesApp = angular.module('shiftWishesApp', ['ngRoute'], function($interpolateProvider) {
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
});

shiftWishesApp.config(['$routeProvider',
	function($routeProvider) {
		$routeProvider.
		when('/list', {templateUrl: 'partials/list', controller: 'listController'}).
		when('/fill', {templateUrl: 'partials/fill', controller: 'fillController'}).			

		otherwise({redirectTo: '/list'});
	}]);

shiftWishesApp.factory( 'listFactory', function($http, $location) {
	var SelectedPlanId = false;
	var plans = false;
	var plan = false;
	return{
		getlist : function() {
			if(!plans){
				plans = $http({
					url: window.grafixapp.shiftplansURL+'/list',
					method: 'GET'
				});
			};
			return plans;
		},
		setSelectedPlanId : function(id){
			SelectedPlanId = id;
		},
		getSelectedPlanId : function(){
			return SelectedPlanId;
		}
	}
});

shiftWishesApp.factory( 'shiftplansFactory', function($http, $location) {
	return{
		getplan : function(pattern, year, month) {
			return $http({
				url: window.grafixapp.shiftplansURL+'/shiftplan/'+pattern+'/'+year+'/'+month,
				method: 'GET'
			})
		}
	}
});

shiftWishesApp.factory( 'wishlistFactory', function($http, $location) {
	return{
		getwishlist : function(pattern, year, month) {
			return $http({
				url: window.grafixapp.shiftplansURL+'/wishlist/'+pattern+'/'+year+'/'+month,
				method: 'GET'
			})
		}
	}
});

shiftWishesApp.controller('listController', function ($scope, $location, listFactory) {
	$scope.list = {};
	listFactory.getlist().success(function(data){
		$scope.list = data;
	});
	$scope.manage = function (id) {
		listFactory.setSelectedPlanId(id);
		$location.path( "/fill" );
	}
});


shiftWishesApp.controller('fillController', function ($scope, $location, listFactory, shiftplansFactory, wishlistFactory) {
	if (!($scope.planid = listFactory.getSelectedPlanId())){
		$location.path( "/list" );
	};

	$scope.plan = {};
	$scope.monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
	'July', 'August', 'September', 'October', 'November', 'December'];
	$scope.shiftplan = {};
	$scope.shiftplan.day = {};
	$scope.selected = {};
	$scope.selected.year = moment().add(1, 'months').year();
	$scope.selected.month = moment().add(1, 'months').month();
	$scope.selected.wish = -1;
	$scope.wishlist = {};


	var getShiftplan = function(id, year, month){
		shiftplansFactory.getplan($scope.planid,$scope.selected.year,$scope.selected.month )
		.success(function(data){
			$scope.shiftplan = data;
		});
	}
	var getWishlist = function(id, year, month){
		wishlistFactory.getwishlist($scope.planid,$scope.selected.year,$scope.selected.month )
		.success(function(data){
			$scope.wishlist = data;
		});

	}

	getShiftplan($scope.planid,$scope.selected.year,$scope.selected.month );
	getWishlist($scope.planid,$scope.selected.year,$scope.selected.month );

	var showMonth = function(year, month){
		$scope.calendar = {};
		$scope.month = [[]];
		$scope.ofset = 0;
		start = moment([year, month, 1]);
		$scope.calendar.firstWeekDay = start.isoWeekday();
		$scope.calendar.daysInMonth = moment(start).endOf('month').date();

		for (var i = 1; i < moment([year, month, 1]).isoWeekday(); i++) {
			$scope.ofset += (i < 6 ? 2 : 1);
		};
		var week = 0;
		for (var i = 1; i <= moment(start).endOf('month').date(); i++) {
			$scope.month[week].push({'date':i, 'weekday':moment([year, month, i]).isoWeekday()});
			if (moment([year, month, i]).isoWeekday() ==7) {week++;$scope.month[week]=[]};
		};
	}

	showMonth($scope.selected.year, $scope.selected.month);



	listFactory.getlist().success(function(data){
		console.log(data);
		$scope.plan = data[$scope.planid];
		$scope.plan.pattern = jQuery.parseJSON($scope.plan.pattern);
	});
	$scope.selectWish = function(wish){
		$scope.selected.wish = wish;
	}
	$scope.setWish = function(day, slot){
		if($scope.selected.wish==1||$scope.selected.wish==-1){
			if(!$scope.wishlist[day]){
				$scope.wishlist[day] = {};
			}
			$scope.wishlist[day][slot] = $scope.selected.wish;
		}else{
			if($scope.wishlist[day]){
				if($scope.wishlist[day][slot]){
					delete $scope.wishlist[day][slot];
					if (JSON.stringify($scope.wishlist[day]) == '{}'){
						delete $scope.wishlist[day];
					}
				}
			}
		}
		console.log($scope.wishlist);
	}

	$scope.changeMonth = function(month){
		var newMonth = moment([$scope.selected.year, $scope.selected.month]).add(month, 'months');
		$scope.selected.year = newMonth.year();
		$scope.selected.month = newMonth.month();
		showMonth($scope.selected.year, $scope.selected.month);
		getShiftplan($scope.planid,$scope.selected.year,$scope.selected.month );
		getWishlist($scope.planid,$scope.selected.year,$scope.selected.month );
	}


	$scope.update = function() {

		$.post("wishlist",
		{
			'wishlist':$scope.wishlist,
			'name':$scope.plan.name,
			'pattern':$scope.planid,
			'month': ""+$scope.selected.year+$scope.selected.month
		},
		function(result){
			$(".content").html("<pre>"+result+'</pre>');
		});
	};

});