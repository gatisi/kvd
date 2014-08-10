<div ng-controller="listController">
		<table class="table table-hover">
			<tr>
				<th>Name</th>
			</tr>
			<tr ng-repeat="plan in list track by $index" ng-click="manage(plan.id)">
				<td>
				<%plan.name%>
				</td> 		
			</tr>
		</table>
</div>