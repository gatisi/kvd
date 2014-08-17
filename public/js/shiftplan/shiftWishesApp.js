var shiftWishesApp = angular.module('shiftWishesApp', ['ngRoute'], function($interpolateProvider) {
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
});

shiftWishesApp.config(['$routeProvider',
	function($routeProvider) {
		$routeProvider.
		when('/list', {templateUrl: 'partials/list', controller: 'listController'}).
		when('/fill', {templateUrl: 'partials/fill', controller: 'fillController'}).			
		when('/view', {templateUrl: 'partials/view', controller: 'viewController'}).			
		when('/view/calendar', {templateUrl: 'partials/view', controller: 'viewController'}).			
		when('/view/table', {templateUrl: 'partials/table', controller: 'viewController'}).			

		otherwise({redirectTo: '/list'});
	}]);

shiftWishesApp.factory('listFactory', function($http) {
	var list = false;
	return {
		getListLocal:function(){
			/*data = JSON.parse(localStorage.getItem('list.plans'))||false;
			if(data){
				return data;
			}*/
			return list;
		},
		getListHttp: function() {
			var promise = $http.get(window.grafixapp.shiftplansURL+'/list').then(function (response) {
				//localStorage.setItem('list.plans', JSON.stringify(response.data));
				list = response.data;
				return response.data;
			});
			return promise;
		}
	};

});

shiftWishesApp.factory( 'shiftplansFactory', function($http, $location) {
	var plan = false;
	return{
		getplan: function(pattern, year, month) {
			var promise = $http.get(window.grafixapp.shiftplansURL+'/shiftplan/'+pattern+'/'+year+'/'+month)
			.then(function (response) {
				plan = response.data;
				return response.data;
			});
			return promise;
		}
	}

});

shiftWishesApp.factory( 'wishlistFactory', function($http, $location) {
	var wishlist = false;
	return{
		getwishlist: function(pattern, year, month) {
			var promise = $http.get(window.grafixapp.shiftplansURL+'/wishlist/'+pattern+'/'+year+'/'+month)
			.then(function (response) {
				wishlist = response.data;
				return response.data;
			});
			return promise;
		}
	}
});

shiftWishesApp.factory( 'contactsFactory', function($http, $location) {

	return{
		getcontacts: function() {
			var promise = $http.get(window.grafixapp.usersURL+'/contacts')
			.then(function (response) {
				wishlist = response.data;
				return response.data;
			});
			return promise;
		}
	}
});	







shiftWishesApp.controller('listController', function ($scope, $location, listFactory) {
	$scope.list = listFactory.getListLocal();

	if(!$scope.list){
		listFactory.getListHttp().then(function(d) {
			$scope.list = d;
		});
	}


	$scope.wishes = function (id) {
		localStorage.setItem('list.SelectedPlanId', id);
		$location.path( "/fill" );
	}
	$scope.view = function (id) {
		localStorage.setItem('list.SelectedPlanId', id);
		$location.path( "/view" );
	}
});


shiftWishesApp.controller('fillController', function ($scope, $location, listFactory, shiftplansFactory, wishlistFactory) {
	$scope.planid = localStorage.getItem('list.SelectedPlanId')||false;
	if (!$scope.planid){
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
		.then(function(data){
			$scope.shiftplan = data;
		});
	}
	var getWishlist = function(id, year, month){
		wishlistFactory.getwishlist($scope.planid,$scope.selected.year,$scope.selected.month )
		.then(function(data){
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

	var list = listFactory.getListLocal();
	if(!list){
		listFactory.getListHttp().then(function(d) {
			$scope.plan = d[$scope.planid];
			$scope.plan.pattern = jQuery.parseJSON($scope.plan.pattern);
		});
	}else{
		$scope.plan = list[$scope.planid];
	}


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


shiftWishesApp.controller('viewController', function ($scope, $location, listFactory, shiftplansFactory, wishlistFactory, contactsFactory) {
	$scope.planid = localStorage.getItem('list.SelectedPlanId')||false;
	if (!$scope.planid){
		$location.path( "/list" );
	};
	$scope.user = {'id':window.grafixapp.user};
	$scope.plan = {};
	$scope.monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
	'July', 'August', 'September', 'October', 'November', 'December'];
	$scope.shiftplan = {};
	$scope.shiftplan.day = {};
	$scope.selected = {};
	$scope.selected.year = moment().add(1, 'months').year();
	$scope.selected.month = moment().add(1, 'months').month();

	contactsFactory.getcontacts().then(function(data){
		$scope.contacts=data;
	});
	var tableView = function(){
		var oddWeekday = true;
		$scope.view = {};
		$scope.view.table = {};
		$scope.view.table.data = [];
		$scope.view.table.thData = [];

		for (var i = 1; i <= moment(start).endOf('month').date(); i++) {
			var weekday = moment([$scope.selected.year, $scope.selected.month, i]).isoWeekday()-1;
			if(oddWeekday){tdClass='odd';oddWeekday=false;}else{tdClass='even';oddWeekday=true};
			if(weekday>4){tdClass+=' weekend'}else{tdClass+=' workday'}

				if($scope.plan.pattern[weekday]){

					var slotsInDay = 0;
					$.each($scope.plan.pattern[weekday], function( index, slot ) {
						$scope.view.table.data.push({'slot':index,
							'day':i, 
							'class':tdClass,
							'start':slot.start,
							'end':slot.end});
						++slotsInDay;
					});

					$scope.view.table.thData.push({'colSpan':slotsInDay});
				}else{
					$scope.view.table.data.push({'day':i, 'class':tdClass});
					$scope.view.table.thData.push({'colSpan':1, 'class':tdClass});
				}

			};
		}

		var getShiftplan = function(id, year, month){
			shiftplansFactory.getplan($scope.planid,$scope.selected.year,$scope.selected.month )
			.then(function(data){
				$scope.shiftplan = data;
				getPattern();//Needs to be caled when shiftplan exists!

			});
		}

		var getPattern = function(){
			var list = listFactory.getListLocal();
			if(!list){
				listFactory.getListHttp().then(function(d) {
					$scope.plan = d[$scope.planid];
					$scope.plan.pattern = jQuery.parseJSON($scope.plan.pattern);
					$scope.plan.workers = jQuery.parseJSON($scope.plan.workers);
					console.log($scope.plan);
					tableView();
					console.log($scope.view);
				});
			}else{
				$scope.plan = list[$scope.planid];
				tableView();
			}
		}

		getShiftplan($scope.planid,$scope.selected.year,$scope.selected.month );

		$scope.changeMonth = function(month){
			var newMonth = moment([$scope.selected.year, $scope.selected.month]).add(month, 'months');
			$scope.selected.year = newMonth.year();
			$scope.selected.month = newMonth.month();
			showMonth($scope.selected.year, $scope.selected.month);
			getShiftplan($scope.planid,$scope.selected.year,$scope.selected.month );
		}

		var showMonth = function(year, month){
			$scope.calendar = {};
			$scope.month = [[]];
			$scope.view = {};
			$scope.view.table = {};
			$scope.view.table.data = [];
			$scope.view.table.thData = [];
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

		$scope.arrFromInts = function(n){
			var arr = [];
			for (var i = 0; i < n; i++) {
				arr.push(i+1);
			};
			return arr;
		}


		$scope.goTo = function(path){
			$location.path( '/'+path );
		}

	});