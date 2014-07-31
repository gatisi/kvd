	var createShiftpatternApp = angular.module('createShiftpatternApp', ['ngRoute'], function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%');
		$interpolateProvider.endSymbol('%>');
	});


	createShiftpatternApp.config(['$routeProvider',
		function($routeProvider) {
			$routeProvider.
			when('/name', {templateUrl: 'partials/name', controller: 'nameController'}).			
			when('/workers', {templateUrl: 'partials/workers', controller: 'workersController'}).
			when('/pattern', {templateUrl: 'partials/pattern', controller: 'patternController'}).

			otherwise({redirectTo: '/name'});
		}]);

	createShiftpatternApp.factory( 'newPlan', function() {

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

	createShiftpatternApp.factory( 'contactsFactory', function($http, $location) {

		return{
			getcontacts : function() {
				return $http({
					url: window.grafixapp.usersURL+'/contacts',
					method: 'GET'
				})
			}
		}
	});

	createShiftpatternApp.controller('nameController', function ($scope, $location, newPlan) {
		$scope.shiftpattern = newPlan.getObject();
		$scope.update = function() {
			newPlan.setObject($scope.shiftpattern);
			$location.path( "/workers" );

		};
	});
	createShiftpatternApp.controller('workersController', function ($scope, $location, newPlan, contactsFactory) {
		$scope.shiftpattern = newPlan.getObject();
		$scope.contacts = [];
		//console.log($scope.shiftpattern);
		contactsFactory.getcontacts().success(function(data){
			$scope.contacts=data;
		});
		$scope.addWorker = function(id){
			$scope.shiftpattern.workers.push(id);
		}
		$scope.removeWorker = function(id){
			$scope.shiftpattern.workers.splice($scope.shiftpattern.workers.indexOf(id), 1);
			//console.log($scope.shiftpattern.workers);
		}
		$scope.update = function() {
			newPlan.setObject($scope.shiftpattern);
			$location.path( "/pattern" );
		};
	});

	createShiftpatternApp.controller('patternController', function ($scope, $location, newPlan) {
		$scope.shiftpattern = newPlan.getObject();
		if(!$scope.shiftpattern.shifts){$scope.shiftpattern.shifts = [];};
		$scope.weekdays =     ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
		$scope.newShift = {};
		$scope.selecteddays = [ false, false, false, false, false, false, false ];
		$scope.newShift.start = "9:00";
		$scope.newShift.end = "17:00";
		$scope.newShift.workers = 1;
		$scope.update = function() {
			$.post("create",
				$scope.shiftpattern,
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
			console.log($scope.selecteddays);
			angular.forEach($scope.selecteddays, function(set, day) {
				if(set){
					$scope.shiftpattern.shifts[day].push(
						$scope.newShift
						);
				}
			});
			//console.log($scope.shiftpattern.shifts);
			$scope.newShift = {};
			$scope.newShift.start = "9:00";
			$scope.newShift.end = "17:00";
			$scope.newShift.workers = 1;
		};
		$scope.deleteShift = function(day, shift){
			$scope.shiftpattern.shifts[day].splice(shift, 1);
		};

	});