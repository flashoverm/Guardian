<form action="event_report.php" method="post" >

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
	
	<div class="form-group">
		<label>Typ:</label> <select class="form-control" name="type">
				<?php foreach ( $eventtypes as $type ) : ?>
					<option value="<?= $type->uuid; ?>"><?= $type->type; ?></option>
				<?php endforeach; ?>
			</select>
	</div>
	
	<div class="form-group">
		<label>Titel:</label> <input type="text" required="required"
			class="form-control" name="title" id="title"
			placeholder="Titel eingeben">
	</div>
	
	<div class="form-group">
		<label>Zuständiger Löschzug/Geschäftszimmer:</label> <select
			class="form-control" name="engine" required="required">
			<option value="" disabled selected>Bitte auswählen</option>
			<option value="<?=$config ["backoffice"]; ?>">Geschäftszimmer</option>
			<?php foreach ( $engines as $option ) : ?>
			<option value="<?=  $option->uuid; ?>"><?= $option->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	
	<div class="form-check">
		<input type="checkbox" class="form-check-input" id="noIncidents"> <label
			for="noIncidents">Keine Vorkomnisse</label>
	</div>
	
	<div class="form-group">
		<label>Bericht:</label>
		<textarea class="form-control" name="report" id="report"
			placeholder="Bericht"></textarea>
	</div>
	
	<div class="form-group">
		<label>Ersteller:</label> <input type="text" required="required"
			class="form-control" name="creator" id="creator"
			placeholder="Namen eintragen">
	</div>

	<?php include('eventReportModal_template.php'); ?>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Fahrzeug/Station hinzufügen</button><p>

	<?php include('vehicleList.php'); ?>
	
	<div id="submitPlaceholder">
	</div>

</form>

<script type='text/javascript'>
	 $("#addStation").on("click",function(){
		addStation();
	 })
	 
	var reportStationCount = 0;
	
	function addStation(){
		reportStationCount += 1;
		
		//var container = document.getElementById("stations");
		/*
		var input = document.createElement("div");
		input.id ="collapse";
		input.type = "text";
		input.name = "staff" + reportStationCount;
		input.id = "staff" + reportStationCount;
		input.required = "required";
		input.placeholder="Funktionsbezeichnung eingeben";
		container.appendChild(input);
		*/
		if(reportStationCount == 1){
			var div = document.getElementById("submitPlaceholder");
			var input1 = document.createElement("input");
			input1.type = "submit";
			input1.value = "Abschicken";
			input1.className ="btn btn-primary";
			div.appendChild(input1);
			var input2 = document.createElement("input");
			input2.type = "hidden";
			input2.value = "send";
			input2.name ="action";
			div.appendChild(input2);			
		}
	}
	
	function removeLast(){
		if(currentPosition != 0){
			var lastStaffRow = document.getElementById("staff"+currentPosition);
			lastStaffRow.parentNode.removeChild(lastStaffRow);
			currentPosition -= 1;
		} else {
			
		}
	}
</script>