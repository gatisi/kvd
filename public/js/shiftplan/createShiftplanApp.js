	var createShiftplanApp = angular.module('createShiftplanApp', ['ngRoute'], function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%');
		$interpolateProvider.endSymbol('%>');
	});


	createShiftplanApp.config(['$routeProvider',
		function($routeProvider) {
			$routeProvider.
			when('/name', {templateUrl: 'partials/name', controller: 'nameController'}).			
			when('/workers', {templateUrl: 'partials/workers', controller: 'workersController'}).

			otherwise({redirectTo: '/name'});
		}]);

	createShiftplanApp.factory( 'newPlan', function() {
		var object = {};
		return{
			getObject: function(){ return object; },
			setObject: function(updated){ object=updated; }
		}
	});

	createShiftplanApp.factory( 'contactsFactory', function($http, $location) {

		return{
			getcontacts : function() {
				return $http({
					url: window.grafixapp.usersURL+'/contacts',
					method: 'GET'
				})
			}
		}
	});

	createShiftplanApp.controller('nameController', function ($scope, $location, newPlan) {
		$scope.shiftplan = newPlan.getObject();
		$scope.update = function() {
			newPlan.setObject($scope.shiftplan);
			$location.path( "/workers" );

		};
	});
	createShiftplanApp.controller('workersController', function ($scope, $location, newPlan, contactsFactory) {
		$scope.shiftplan = newPlan.getObject();
		$scope.contacts = [];
		$scope.shiftplan.workers = [];
		console.log($scope.shiftplan);
		contactsFactory.getcontacts().success(function(data){
			$scope.contacts=data;
		});
		$scope.addWorker = function(id){
			$scope.shiftplan.workers.push(id);
			console.log($scope.shiftplan.workers);
		}
		$scope.removeWorker = function(id){
			$scope.shiftplan.workers.splice($scope.shiftplan.workers.indexOf(id), 1);
			console.log($scope.shiftplan.workers);
		}
		$scope.update = function() {
			newPlan.setObject($scope.shiftplan);
			$location.path( "/step3" );
		};
	});