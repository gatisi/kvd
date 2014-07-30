<div ng-controller="patternController">


	<div class="row">
		<div ng-repeat="day in weekdays" id="day<%$index%>"  ng-class="{'col-md-2': $index<5, 'col-md-1': $index>4}">
			<h4><%day%></h4>
			<div ng-repeat="s in shiftplan.shifts[$index] track by $index">
				<button type="button" class="btn btn-default btn-xs pull-right" ng-click="deleteShift($parent.$index, $index)">
					<span class="glyphicon glyphicon-minus"></span>
				</button>
				<div ><%s.start%> - <%s.end%></div>
				<div ><%s.workers%> workers</div>
			</div>
			<button class="btn btn-default btn-s btn-block " ng-click="addshift($index)">
				<span class="glyphicon glyphicon-plus"></span>
			</button>
		</div>
	</div>

		<button type="submit" class="btn btn-default" ng-click="update(shiftplan)"
		ng-disabled="form.$invalid">Next</button>


	<!-- Modal -->

	<div class="modal fade" id="newShift" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" ng-click="clearModal()" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Modal title</h4>
				</div>
				<div class="modal-body">

					<form name="form" role="form" novalidate>
						<div class="row">

							<div class="col-md-8">
								<div class="form-group">
									<label for="start">Begining of shift</label>
									<div class="input-group bootstrap-timepicker">
										<input id="timepicker1" type="text" class="form-control" name="start" ng-model="newShift.start">
										<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
									</div>
								</div>					
								<div class="form-group">
									<label for="end">End of shift</label>
									<div class="input-group bootstrap-timepicker">
										<input id="timepicker2" type="text" class="form-control" ng-model="newShift.end" name="end">
										<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
									</div>
								</div>
								<div class="form-group">
									<label for="end">Workers needed</label>
									<div class="input-group bootstrap-timepicker">
										<input type="number" class="form-control" ng-model="newShift.workers" value="1">
									</div>
								</div>
							</div>


							<div class="col-md-4">
								<div class="checkbox" ng-repeat="day in weekdays">
									<label>
										<input type="checkbox" ng-model="selecteddays[$index]"> <%day%>
									</label>
								</div>
							</div>


						</div>
					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" ng-click="clearModal()" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" ng-click="saveNewShift()">Save changes</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#timepicker1').timepicker({showMeridian: false});
	$('#timepicker2').timepicker({showMeridian: false});
</script>