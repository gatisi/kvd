<div ng-controller="listController">
		<table class="table table-hover">
			<tr>
				<th>Name</th>
			</tr>
			<tr ng-repeat="plan in list track by $index">
				<td>
				<%plan.name%>
				<button ng-click="view(plan.id)">View</button>		
				<button ng-click="wishes(plan.id)">Wishes</button>		
				</td> 
			</tr>
		</table>
</div>