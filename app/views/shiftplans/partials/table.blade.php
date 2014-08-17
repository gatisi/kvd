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
		<button ng-click="goTo('view/calendar')">Calendar</button>
	</div>
</div>


<div class="table-responsive">
<table class="table table-bordered">
	<tr>
		<td></td>
		<td ng-repeat="th in view.table.thData track by $index" colspan="<%th.colSpan%>"><%$index+1%></td>
	</tr>
	<tr>
		<td ></td>
		<td ng-repeat="slot in view.table.data track by $index" class="<%slot.class%>">
			<div class="shift-time "><small><%slot.start%>-<%slot.end%><small></div>
		</td>
	</tr>
	<tr ng-repeat="contact_id in plan.workers track by $index">
		<td ><%contacts[contact_id].first_name%> <%contacts[contact_id].last_name%></td>
		<td ng-repeat="slot in view.table.data track by $index" class="<%slot.class%>">
			<div ng-if="shiftplan.day[slot.day].shift[slot.slot].worker.id==contact_id">x</div>
			
		</td>
	</tr>
</table>
</div>