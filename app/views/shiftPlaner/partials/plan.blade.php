<h2><%plan.name%></h2>
<div class="btn-group">
	<button type="button" class="btn btn-default btn-lg" ng-click="changeMonth(-1)">
		<span class="glyphicon glyphicon-chevron-left"></span>
	</button>
	<button type="button" class="btn btn-default btn-lg">
		<span class="glyphicon glyphicon-calendar"></span>
		<%selected.year%>, <%monthNames[selected.month]%>
	</button>
	<button type="button" class="btn btn-default btn-lg" ng-click="changeMonth(1)">
		<span class="glyphicon glyphicon-chevron-right"></span>
	</button>
</div>
<div class="row">
	<div ng-repeat="worker in plan.workers" ng-if="contacts[worker] && !selectedWorker" class="btn btn-default btn-sm" ng-click="selectWorker(worker)">
		<%contacts[worker].first_name%> 
		<%contacts[worker].last_name%>
		<br>
		<%contacts[worker].email%>
	</div>

	<div ng-model="selectedWorker" ng-if="selectedWorker" ng-click="clearSelectedWorker()" class="btn btn-default btn-lg">
		Selected <br>
		<%selectedWorker.first_name%> 
		<%selectedWorker.last_name%> 
		<%selectedWorker.email%>
		<br>
		Click to change
	</div>
</div>

<div class="row t-row" ng-repeat="week in month track by $index" >
	<div class="col-md-<%ofset%>" ng-if="$index == 0"></div>
	<div ng-repeat="day in week track by $index" ng-class="{'col-md-2': day.weekday<6, 'col-md-1': day.weekday>5}" class="t-cell">
		<%day.date%>

		<div ng-if="plan.pattern[day.weekday-1]" ng-repeat="shift in plan.pattern[day.weekday-1] track by $index">
			<%$index%> <span><%shift.start%> - <%shift.end%></span>

			<button class="btn btn-default btn-xs btn-block" ng-model="added" ng-if="!shiftplan.day[day.date].shift[$index]" ng-click="setWorker(day.date, shift.start, shift.end, $index)">Add</button>
			<div class="btn btn-success btn-sm btn-block" ng-if="shiftplan.day[day.date].shift[$index]" ng-click="setWorker(day.date, shift.start, shift.end, $index)" ng-class="{'btn-info':shiftplan.day[day.date].shift[$index].worker.id==selectedWorker.id}">
				<button type="button" class="close" ng-click="delWorker(day.date, $index)"><span aria-hidden="true">&times;</span></button>
				<%$index%> <%shiftplan.day[day.date].shift[$index].worker.first_name%>
			</div>

		</div>
	</div>
</div>

	<button type="button" class="btn btn-default btn-lg" ng-click="update()">
		<span class="glyphicon glyphicon-ok"></span> Save
	</button>
