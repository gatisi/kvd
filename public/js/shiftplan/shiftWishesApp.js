var shiftWishesApp = angular.module('shiftWishesApp', ['ngRoute'], function($interpolateProvider) {
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
});

shiftWishesApp.config(['$routeProvider',
	function($routeProvider) {
		$routeProvider.
		when('/list', {templateUrl: 'partials/list', controller: 'listController'}).
		when('/plan', {templateUrl: 'partials/plan', controller: 'planController'}).			

		otherwise({redirectTo: '/list'});
	}]);

shiftWishesApp.factory( 'listFactory', function($http, $location) {
	var planid = false;
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
		setplanid : function(id){
			planid = id;
		},
		getplanid : function(){
			return planid;
		}
	}
});

shiftWishesApp.controller('listController', function ($scope, $location, listFactory) {
	$scope.list = {};
	listFactory.getlist().success(function(data){
		$scope.list = data;
	});
	$scope.manage = function (id) {
		listFactory.setplanid(id);
		$location.path( "/plan" );
	}
});

