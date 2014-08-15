var homeApp = angular.module('homeApp', [], function($interpolateProvider) {
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
});

homeApp.factory( 'newPlansFactory', function($http) {

	return{
		getNewPlans : function() {
			return $http({
				url: window.grafixapp.shiftpatternURL+'/new',
				method: 'GET'
			})
		},

		setNewPlan : function(id, reply) {
			return $http({
				url: window.grafixapp.shiftpatternURL+'/acceptnew/'+id+'/'+reply,
				method: 'GET'
			})
		}
	}
});

homeApp.controller('acceptNewPlans', function ($scope, newPlansFactory) {

	$scope.acceptPlan = function(id){
		newPlansFactory.setNewPlan(id, 'accept')
		.success(function(data){
			if(!data.error){
				delete $scope.newPlans[data.id];
			}
		});
	}
	$scope.rejectPlan = function(id){
		newPlansFactory.setNewPlan(id, 'reject')
		.success(function(data){
			if(!data.error){
				delete $scope.newPlans[data.id];
			}
		});
	}


	newPlansFactory.getNewPlans()
	.success(function(data){
		$scope.newPlans = data;
		console.log(data);
	});


});