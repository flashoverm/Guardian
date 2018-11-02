<div class="modal fade" id="addUnitModal" role="dialog">
	<div class="modal-dialog modal-lg">

		<div class="modal-content">
			<form id="addUnitForm">
				<div class="modal-header">
					<h4 class="modal-title">Einheit hinzufügen</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
	
				<div class="modal-body">
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label>Fahrzeug/Station:</label> <input type="text"
									class="form-control" name="unit" id="unit" required="required"
									placeholder="Fahrzeug/Station eingeben">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label>Kilometer (Nur bei Fahrzeug)</label> <input type="number"
									class="form-control" name="km" id="km"
									placeholder="Gefahrene Kilometer">
							</div>
						</div>
					</div>
					<p>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label>Datum:</label> <input type="date" class="form-control" required="required"
									name="unitdate" id="unitdate">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label>Wachbeginn:</label> <input type="time" class="form-control" required="required"
									name="unitstart" id="unitstart">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label>Ende:</label> <input type="time" class="form-control" required="required"
									name="unitend" id="unitend">
							</div>
						</div>
					</div>
					<p>
					<div class="form-group" id="staffContainer">
						<label>Personal:</label>
						<div class="btn-group btn-group-sm" role="group"
							style="float: right">
							<button type="button" class="btn btn-primary"
								onClick="removeLastReportPosition()">&minus;</button>
							<span class="border-right"></span>
							<button type="button" class="btn btn-primary "
								onClick="addReportPosition()">+</button>
						</div>
						<p>
						<div class="row" id="position1">
							<div class="col">
								<div class="form-group">
									<select class="form-control" name="positionfunction" required="required" id="positionfunction">
										<option value="" disabled selected>Funktion auswählen</option>
										<?php foreach ( $staffpositions as $option ) : ?>
										<option value="<?=  $option->position;  //Change to $option->uuid for database usage ?>"><?= $option->position; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<input type="text" placeholder="Name" class="form-control" required="required"
										name="positionname" id="positionname">
								</div>
							</div>
							<div class="col" id="engineselect">
								<div class="form-group">
									<select class="form-control" name="positionengine" required="required"
										id="positionengine">
										<option value="" disabled selected>Löschzug auswählen</option>
										<?php foreach ( $engines as $option ) : ?>
										<option value="<?=  $option->name;  //Change to $option->uuid for database usage ?>"><?= $option->name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" id="addUnit" value="Hinzufügen">
					<button type="button" class="btn btn-default" onClick="clearForm()" data-dismiss="modal">Abbrechen</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type='text/javascript'>

	function initializeModal(){

		var date = document.getElementById("date").value;
		var start = document.getElementById("start").value;
		var end = document.getElementById("end").value;

		var form = document.getElementById("addUnitForm");

		if(date != ""){
			form.querySelector("#unitdate").value = date;
		}
		if(start != ""){
			form.querySelector("#unitstart").value = start;
		}
		if(end != ""){
			form.querySelector("#unitend").value = end;
		}

		//TODO set false
		if(false){
			form.querySelector("#unitdate").value = "2018-10-12";
			form.querySelector("#unitstart").value = "20:00";
			form.querySelector("#unitend").value = "22:00";
			form.querySelector("#unit").value = "Test";
		}
	}

	function processForm(e) {
	    if (e.preventDefault) e.preventDefault();
	
	    /* do what you want with the form */
	
	    // You must return false to prevent the default form behavior
	    addReportUnit();
	    
	    clearForm();
	    return false;
	}
	
	var form = document.getElementById('addUnitForm');
	if (form.attachEvent) {
	    form.attachEvent("submit", processForm);
	} else {
	    form.addEventListener("submit", processForm);
	}

	var reportPositionCount = 1;

	function addReportPosition(){
		reportPositionCount += 1;
		
		var container = document.getElementById("staffContainer");

		var position1 = document.getElementById("position1");
		var newPosition =  position1.cloneNode(true);
		newPosition.id = "position" + reportPositionCount;
		if(newPosition.querySelector("#positionfunction").value != ""){
			newPosition.querySelector("#positionfunction").value = "";
		}
		if(newPosition.querySelector("#positionname").value != ""){
			newPosition.querySelector("#positionname").value = "";
		}
		
		container.appendChild(newPosition);
	}
	
	function removeLastReportPosition(){
		if(reportPositionCount != 1){
			var lastStaffRow = document.getElementById("position"+reportPositionCount);
			lastStaffRow.parentNode.removeChild(lastStaffRow);
			reportPositionCount -= 1;
		}
	}

	function clearForm(){
		var form = document.getElementById("addUnitForm");

		while(reportPositionCount > 1){
			removeLastReportPosition();
		}

		form.reset();
	}
		
</script>