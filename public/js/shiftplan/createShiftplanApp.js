	var createShiftplanApp = angular.module('createShiftplanApp', ['ngRoute'], function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%');
		$interpolateProvider.endSymbol('%>');
	});


	createShiftplanApp.config(['$routeProvider',
		function($routeProvider) {
			$routeProvider.
			when('/name', {
				templateUrl: 'partials/name',
				controller: 'nameController'
			}).

			otherwise({
				redirectTo: '/name'
			});
		}]);

	createShiftplanApp.controller('nameController', function ($scope) {
$scope.selectedYear = 'jknj';

	});