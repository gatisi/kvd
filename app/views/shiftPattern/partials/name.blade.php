<div ng-controller="nameController">
	<form name="form" role="form" novalidate>
		<div class="form-group">
		<label for="shiftplanName">Name for the new Shift Plan</label>
			<input type="text" class="form-control" id="shiftplanName" placeholder="" ng-model="shiftpattern.name" name="shiftplanName" required>
		</div>

		<button type="submit" class="btn btn-default" ng-click="update(shiftplan)"
		ng-disabled="form.$invalid || isUnchanged(shiftplan)">Next</button>
	</form>
</div>