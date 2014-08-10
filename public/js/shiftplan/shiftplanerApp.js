	var shiftplanerApp = angular.module('shiftplanerApp', ['ngRoute'], function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%');
		$interpolateProvider.endSymbol('%>');
	});

	shiftplanerApp.config(['$routeProvider',
		function($routeProvider) {
			$routeProvider.
			when('/list', {templateUrl: 'partials/list', controller: 'listController'}).
			when('/plan', {templateUrl: 'partials/plan', controller: 'planController'}).			

			otherwise({redirectTo: '/list'});
		}]);

	shiftplanerApp.factory( 'listFactory', function($http, $location) {
		var planid = false;
		var plans = false;
		var plan = false;
		return{
			getlist : function() {
				if(!plans){
					plans = $http({
						url: window.grafixapp.shiftplanerURL+'/list',
						method: 'GET'
					});
				};
				return plans;
			},
			setplanid : function(id){
				planid = id;
			},
			getplanid : function(){
				return planid;
			}
		}
	});

	shiftplanerApp.factory( 'contactsFactory', function($http, $location) {

		return{
			getcontacts : function() {
				return $http({
					url: window.grafixapp.usersURL+'/contacts',
					method: 'GET'
				})
			}
		}
	});

	shiftplanerApp.controller('listController', function ($scope, $location, listFactory) {
		$scope.list = {};
		listFactory.getlist().success(function(data){
			$scope.list = data;
		});
		$scope.manage = function (id) {
			listFactory.setplanid(id);
			$location.path( "/plan" );
		}
	});
	shiftplanerApp.controller('planController', function ($scope, $location, listFactory, contactsFactory) {
		if (!($scope.planid = listFactory.getplanid())){
			$location.path( "/list" );
		};
		$scope.plan = {};
		$scope.monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
		'July', 'August', 'September', 'October', 'November', 'December'];
		$scope.shiftplan = {};
		$scope.shiftplan.day = {};

		$scope.shiftplan.year = moment().add(1, 'months').year();
		$scope.shiftplan.month = moment().add(1, 'months').month();

		$scope.showMonth = function(year, month){
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

		$scope.showMonth($scope.shiftplan.year, $scope.shiftplan.month);



		listFactory.getlist().success(function(data){
			$scope.plan = data[$scope.planid];
			$scope.plan.pattern = jQuery.parseJSON($scope.plan.pattern);
			$scope.plan.workers = jQuery.parseJSON($scope.plan.workers);
		});
		contactsFactory.getcontacts().success(function(data){
			$scope.contacts=data;
			console.log($scope.contacts);
		});

		$scope.selectWorker = function(id){
			$scope.selectedWorker = $scope.contacts[id];
		}
		$scope.clearSelectedWorker = function(){
			$scope.selectedWorker = false;
		}
		$scope.arrFromInt = function(n){
			var arr = [];
			for (var i = 0; i < n; i++) {
				arr.push(i);
			};
			return arr;
		}
		$scope.setWorker = function(date, start, end, slot){
			if($scope.selectedWorker){
				if(!$scope.shiftplan.day[date]){
					$scope.shiftplan.day[date] = {};
				}
				if(!$scope.shiftplan.day[date].shift){
					$scope.shiftplan.day[date].shift = {};
				}
				if(!$scope.shiftplan.day[date].shift[slot]){
					$scope.shiftplan.day[date].shift[slot] = {};
				}
				$scope.shiftplan.day[date].shift[slot].worker = $scope.selectedWorker;
				$scope.shiftplan.day[date].shift[slot].start = start;
				$scope.shiftplan.day[date].shift[slot].end = end;
			}
			console.log($scope.shiftplan);
		}
		$scope.delWorker = function(date, slot){
			delete $scope.shiftplan.day[date].shift[slot];
			console.log($scope.shiftplan);
		}

		$scope.changeMonth = function(month){
			var newMonth = moment([$scope.shiftplan.year, $scope.shiftplan.month]).add(month, 'months');
			$scope.shiftplan.year = newMonth.year();
			$scope.shiftplan.month = newMonth.month();
			$scope.showMonth($scope.shiftplan.year, $scope.shiftplan.month);
			console.log(month);
		}


		$scope.update = function() {
			$.post("create",
				{
					'plan':$scope.shiftplan,
					'name':$scope.plan.name,
					'pattern':$scope.planid,
					'month': ""+$scope.shiftplan.year+$scope.shiftplan.month
				},
				function(result){
					$(".content").html("<pre>"+result+'</pre>');
				});
		};

	});