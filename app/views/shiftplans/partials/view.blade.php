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

<div class="viewSelector">
	<div class="btn-group">
		<button ng-click="goTo('view/table')">Table</button>
	</div>
</div>

<div class="row t-row" ng-repeat="week in month track by $index" >
	<div class="col-md-<%ofset%>" ng-if="$index == 0"></div>
	<div ng-repeat="day in week track by $index" ng-class="{'col-md-2': day.weekday<6, 'col-md-1': day.weekday>5}" class="t-cell">
		<div><p class="bg-info text-center"><strong><%day.date%></strong></p></div> 

		<div ng-if="plan.pattern[day.weekday-1]" ng-repeat="shift in plan.pattern[day.weekday-1] track by $index">

			<button type="button" class="btn btn-default btn-xs btn-block" 
					ng-if="!shiftplan.day[day.date].shift[$index]" >
				Not set
				<br>
				<%shift.start%> - <%shift.end%>
			</button>

			<div class="btn btn-success btn-sm btn-block" 
						ng-if="shiftplan.day[day.date].shift[$index]" 
						ng-class="{'btn-info':shiftplan.day[day.date].shift[$index].worker.id==user.id}">
				<%shiftplan.day[day.date].shift[$index].worker.first_name%>
				<br>
				<small><%shift.start%> - <%shift.end%></small>
			</div>

		</div>
	</div>
</div>

<button type="button" class="btn btn-default btn-lg">
	<span class="glyphicon glyphicon-ok"></span> Save
</button>
