<div class="modal fade" id="addUnitModal" role="dialog">
	<div class="modal-dialog modal-lg">

		<div class="modal-content">
			<form id="addUnitForm">
				<div class="modal-header">
					<h4 class="modal-title">Einheit hinzufügen</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
	
				<div class="modal-body">
					<div class="row" id="vehiclerow">
						<div class="col">
							<div class="form-group">
								<label>Fahrzeug:</label> <input type="text"
									class="form-control" name="unit" id="unit" required="required"
									placeholder="Fahrzeug eingeben">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label>Kilometer:</label> <input type="number" required="required"
									class="form-control" name="km" id="km"
									placeholder="Gefahrene Kilometer">
							</div>
						</div>
					</div>
					</p>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label>Datum:</label> <input type="date" class="form-control" required="required" 
								placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ"
								name="unitdate" id="unitdate" 
								required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label>Wachbeginn:</label> <input type="time" class="form-control" required="required"
								placeholder="--:--" title="--:--"
								name="unitstart" id="unitstart" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label>Ende:</label> <input type="time" class="form-control" required="required" 
								placeholder="--:--" title="--:--"
								name="unitend" id="unitend" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
							</div>
						</div>
					</div>
					</p>
					<div class="form-group mb-0" id="staffContainer">
						<label>Personal:</label>
						<div class="btn-group btn-group-sm" role="group"
							style="float: right">
							<button type="button" class="btn btn-primary"
								onClick="removeLastReportStaffPosition()">&minus;</button>
							<span class="border-right"></span>
							<button type="button" class="btn btn-primary "
								onClick="addReportStaffPosition()">+</button>
						</div>
						</p>
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
					<p class="text-right my-0 mx-0"><sub><em>* Mehr Personal kann mit + eingefügt werden</em></sub></p>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="unitNo" value="">
					<input type="submit" class="btn btn-primary" id="addUnit" value="Hinzufügen">
					<button type="button" class="btn btn-default" onClick="clearUnitForm()" data-dismiss="modal">Abbrechen</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type='text/javascript'>

    var form = document.getElementById('addUnitForm');
    if (form.attachEvent) {
        form.attachEvent("submit", processForm);
    } else {
        form.addEventListener("submit", processForm);
    }
    
    var reportPositionCount = 1;
    var reportEngine = "";
    var stationString = "Stationäre Wache"

    
	function initializeModal(){

		initializeModalVehicle();
		
		var vehicleRow = document.getElementById("vehiclerow");
		var unit = document.getElementById("unit");
		var km = document.getElementById("km");
		

		unit.value = stationString;
		unit.disabled = true;
		km.disabled = true;
		vehicleRow.style.display = 'none';	
	}

	function initializeModalVehicle(){

		var vehicleRow = document.getElementById("vehiclerow");
		var unit = document.getElementById("unit");
		var km = document.getElementById("km");
		

		unit.value = '';
		unit.disabled = false;
		km.disabled = false;
		vehicleRow.style.display = 'flex';	
		
		var date = document.getElementById("date").value;
		var start = document.getElementById("start").value;
		var end = document.getElementById("end").value;
		reportEngine = document.getElementById("engine").selectedIndex;

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
		form.querySelector("#positionengine").selectedIndex = reportEngine;
	}

	function initializeModalEdit(unitnumber){

		var unit = document.getElementById("unit" + unitnumber + "unit");
		var km = document.getElementById("unit" + unitnumber + "km");
		var unitDate = document.getElementById("unit" + unitnumber + "date");
		var unitStart = document.getElementById("unit" + unitnumber + "start");
		var unitEnd = document.getElementById("unit" + unitnumber + "end");
		
		var modalVehicleRow = document.getElementById("vehiclerow");
		var modalUnit = document.getElementById("unit");
		var modalKm = document.getElementById("km");
		var modalUnitDate = document.getElementById("unitdate");
		var modalUnitStart = document.getElementById("unitstart");
		var modalUnitEnd = document.getElementById("unitend");

		modalUnitDate.value = unitDate.value;
		modalUnitStart.value = unitStart.value;
		modalUnitEnd.value = unitEnd.value;

		if(km.value || unit.value == stationString){
			modalUnit.value = unit.value;
			modalUnit.disabled = false;
			modalKm.disabled = false;
			modalKm.value = km.value;
			modalVehicleRow.style.display = 'flex';	
		} else {
			modalUnit.value = stationString;
			modalUnit.disabled = true;
			modalKm.disabled = true;
			modalKm.value = '';
			modalVehicleRow.style.display = 'none';
		}

		var positionNo = 1;
		addExistingStaffPosition(unitnumber, positionNo);

		while(positionfunction = document.getElementById("unit" + unitnumber + "function" + (positionNo+1)) ) {
			positionNo ++;
			addReportStaffPosition();
			
			addExistingStaffPosition(unitnumber, positionNo);
		}

		var unitNo = document.getElementById("unitNo");
		unitNo.value = unitnumber;

		var addButton = document.getElementById("addUnit");
		addButton.value = "Aktualisieren";
	}

	function addExistingStaffPosition(unitnumber, positionNo) {
		var positionfunction = positionfunction = document.getElementById("unit" + unitnumber + "function" + positionNo);
		var positionname = document.getElementById("unit" + unitnumber + "name" + positionNo);
		var positionengine = document.getElementById("unit" + unitnumber + "engine" + positionNo);

		var position = form.querySelector("#position" + positionNo);
		position.querySelector("#positionname").value = positionname.value;
		position.querySelector("#positionfunction").selectedIndex = positionfunction.selectedIndex;
		position.querySelector("#positionengine").selectedIndex = positionengine.selectedIndex;
	}

	function processForm(e) {
	    if (e.preventDefault) e.preventDefault();
		
		addUnit();
  
		clearUnitForm();
	    
	    $('#addUnitModal').modal('hide');
	    return false;
	}

</script>