<div class="modal fade" id="addModal" role="dialog">
	<div class="modal-dialog">

		<div class="modal-content">
		
			<div class="modal-header">
				<h4 class="modal-title">Fahrzeug/Station hinzufügen</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label>Fahrzeug/Station:</label> <input type="text" required="required"
								class="form-control" name="stationname" id="stationname"
								placeholder="Fahrzeug/Station eingeben">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>KM:</label> <input type="text" required="required" size="4"
								class="form-control" name="km" id="km"
								placeholder="Gefahrene Kilometer">
						</div>
					</div>
				</div>
				<p>				
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label>Datum:</label> <input type="date" required="required"
								class="form-control" name="date" id="date">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>Beginn:</label> <input type="time" required="required"
								class="form-control" name="start" id="start">
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label>Ende:</label> <input type="time" required="required"
								class="form-control" name="end" id="end">
						</div>
					</div>
				</div>
				<p>
				<div class="form-group" id="staffContainer">
					<label>Wachpersonal:</label>
					<div class="btn-group btn-group-sm" role="group" style="float: right">
			  			<button type="button" class="btn btn-primary" onClick="removeLast()">&minus;</button>
			  			<span class="border-right"></span>
			  			<button type="button" class="btn btn-primary " onClick="addStaff()">+</button>
					</div>
					<p>
					<div class="row" id="position1">
						<div class="col">
							<div class="form-group">
								<input type="text" required="required" placeholder="Position"
									class="form-control" name="position" id="position">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<input type="text" required="required" placeholder="Name"
									class="form-control" name="name" id="name">
							</div>
						</div>
						<div class="col">
					<div class="form-group">
						<select class="form-control" name="engine" required="required">
							<option value="" disabled selected>Löschzug</option>
							<?php foreach ( $engines as $option ) : ?>
							<option value="<?=  $option->uuid; ?>"><?= $option->name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
						</div>
					</div>
				</div>

				<p>Some text in the modal.</p>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal" id="addStation">Hinzufügen</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
			</div>
		</div>

	</div>
</div>

<script type='text/javascript'>
	var reportPositionCount = 1;
	
	function addPosition(){
		reportPositionCount += 1;
		var container = document.getElementById("staffContainer");
		var input = document.createElement("input");
		input.className ="form-control";
		input.type = "text";
		input.name = "staff" + reportPositionCount;
		input.id = "staff" + reportPositionCount;
		input.required = "required";
		input.placeholder="Funktionsbezeichnung eingeben";
		container.appendChild(input);
	}
	
	function removeLastPosition(){
		if(reportPositionCount != 1){
			var lastStaffRow = document.getElementById("staff"+reportPositionCount);
			lastStaffRow.parentNode.removeChild(lastStaffRow);
			reportPositionCount -= 1;
		}
	}
</script>