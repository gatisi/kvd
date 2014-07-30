	var createShiftplanApp = angular.module('createShiftplanApp', ['ngRoute'], function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%');
		$interpolateProvider.endSymbol('%>');
	});


	createShiftplanApp.config(['$routeProvider',
		function($routeProvider) {
			$routeProvider.
			when('/name', {templateUrl: 'partials/name', controller: 'nameController'}).			
			when('/workers', {templateUrl: 'partials/workers', controller: 'workersController'}).
			when('/pattern', {templateUrl: 'partials/pattern', controller: 'patternController'}).

			otherwise({redirectTo: '/name'});
		}]);

	createShiftplanApp.factory( 'newPlan', function() {

		var newplan = JSON.parse(localStorage.getItem('newplan')) || {
			name:"",
			workers:[],
			shifts:[[], [], [], [], [], [], []]
		};
		return{
			getObject: function(){ return newplan; },
			setObject: function(updated){ 
				newplan=updated;
				//localStorage.setItem('newplan', JSON.stringify(newplan));
			 }
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
		//console.log($scope.shiftplan);
		contactsFactory.getcontacts().success(function(data){
			$scope.contacts=data;
		});
		$scope.addWorker = function(id){
			$scope.shiftplan.workers.push(id);
		}
		$scope.removeWorker = function(id){
			$scope.shiftplan.workers.splice($scope.shiftplan.workers.indexOf(id), 1);
			//console.log($scope.shiftplan.workers);
		}
		$scope.update = function() {
			newPlan.setObject($scope.shiftplan);
			$location.path( "/pattern" );
		};
	});

	createShiftplanApp.controller('patternController', function ($scope, $location, newPlan) {
		$scope.shiftplan = newPlan.getObject();
		if(!$scope.shiftplan.shifts){$scope.shiftplan.shifts = [];};
		$scope.weekdays =     ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
		$scope.newShift = {};
		$scope.selecteddays = [ false, false, false, false, false, false, false ];
		$scope.newShift.start = "9:00";
		$scope.newShift.end = "17:00";
		$scope.newShift.workers = 1;
		$scope.update = function() {
			$.post("create",
				$scope.shiftplan,
				function(result){
					$(".content").html("<pre>"+result+'</pre>');
				});
		};
		$scope.clearModal = function(){
			$scope.selecteddays = [ false, false, false, false, false, false, false ];
		}
		$scope.addshift = function(day){
			$scope.selecteddays[day]=true;
			$('#newShift').modal('show');
		};
		$scope.saveNewShift = function(){
			angular.forEach($scope.selecteddays, function(set, day) {
				if(set){
					$scope.shiftplan.shifts[day].push(
						$scope.newShift
						);
				}
			});
			//console.log($scope.shiftplan.shifts);
			$scope.newShift = {};
			$scope.newShift.start = "9:00";
			$scope.newShift.end = "17:00";
			$scope.newShift.workers = 1;
		};
		$scope.deleteShift = function(day, shift){
			$scope.shiftplan.shifts[day].splice(shift, 1);
		};

	});