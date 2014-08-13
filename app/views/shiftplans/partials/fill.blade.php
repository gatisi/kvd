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

<div class="row cpanel">
	<div class="btn-group">
		<button type="button" class="btn btn-danger btn-lg" 
				ng-click="selectWish(-1)" 
				ng-class="{'active':selected.wish<0}">
			<span class="glyphicon glyphicon glyphicon-thumbs-down"></span>
			Don't wish to work
		</button>
		<button type="button" class="btn btn-info btn-lg" 
				ng-click="selectWish(0)" 
				ng-class="{'active':selected.wish == 0}">
			<span class="glyphicon glyphicon glyphicon-minus"></span>
			Don't care
		</button>
		<button type="button" class="btn btn-success btn-lg" 
				ng-click="selectWish(1)" 
				ng-class="{'active':selected.wish > 0}">
			<span class="glyphicon glyphicon glyphicon-thumbs-up"></span>
			Wish to work
		</button>
	</div>
</div>

<div class="row t-row" ng-repeat="week in month track by $index" >
	<div class="col-md-<%ofset%>" ng-if="$index == 0"></div>
	<div ng-repeat="day in week track by $index" ng-class="{'col-md-2': day.weekday<6, 'col-md-1': day.weekday>5}" class="t-cell">
		<div><p class="bg-info text-center"><strong><%day.date%></strong></p></div> 

		<div ng-if="plan.pattern[day.weekday-1]" ng-repeat="shift in plan.pattern[day.weekday-1] track by $index">

			<button type="button" class="btn btn-xs btn-block" 
					ng-model="added" 
					ng-if="!shiftplan.day[day.date].shift[$index]" 
					ng-click="setWish(day.date, $index)"
					ng-class="{'btn-info':!wishlist[day.date][$index],
							   'btn-danger':wishlist[day.date][$index]==-1,
							   'btn-success':wishlist[day.date][$index]==1,		
							   }">
				<span class="glyphicon glyphicon glyphicon-minus" ng-if="!wishlist[day.date][$index]"></span>
				<span class="glyphicon glyphicon glyphicon-thumbs-down" ng-if="wishlist[day.date][$index]==-1"></span>
				<span class="glyphicon glyphicon glyphicon-thumbs-up" ng-if="wishlist[day.date][$index]==1"></span>
				<%shift.start%> - <%shift.end%>
			</button>

			<div class="btn btn-success btn-sm btn-block" 
						ng-if="shiftplan.day[day.date].shift[$index]" 
						ng-click="setWorker(day.date, shift.start, shift.end, $index)" 
						ng-class="{'btn-info':shiftplan.day[day.date].shift[$index].worker.id==selectedWorker.id}">
				<%shiftplan.day[day.date].shift[$index].worker.first_name%>
				<br>
				<small><%shift.start%> - <%shift.end%></small>
			</div>

		</div>
	</div>
</div>

<button type="button" class="btn btn-default btn-lg" ng-click="update()">
	<span class="glyphicon glyphicon-ok"></span> Save
</button>
