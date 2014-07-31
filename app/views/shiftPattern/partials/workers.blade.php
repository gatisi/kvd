<div ng-controller="workersController">
	<form name="form" role="form" novalidate>
		<table class="table table-hover">
			<tr>
				<th></th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Email</th>
			</tr>
			<tr ng-repeat="contact in contacts" ng-class="{success:added}">
				<td>
				<button class="btn btn-success" ng-model="added" ng-hide="added" ng-click="added=true; addWorker(contact.id)">Add</button>
				<button class="btn btn-danger" ng-model="added" ng-show="added" ng-click="added=false; removeWorker(contact.id)">Remove</button>
				</td> 	
				<td><%contact.name%><%contact.first_name%></td> 			
				<td><%contact.last_name%></td> 			
				<td><%contact.email%></td> 			
			</tr>
		</table>
		<button type="submit" class="btn btn-default" ng-click="update()">Next</button>
	</form>
	<%aaa%>
</div>