<?php include('eventReportUnit_template.php'); ?>

<form onsubmit="showLoader()" action="<?=$config["urls"]["html"]?>/reports/new" method="post" >

	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Datum:</label> <input type="date" required="required" 
				placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ"	class="form-control" 
				name="date" id="date" 
				required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Wachbeginn:</label> <input type="time" required="required" 
				placeholder="--:--" title="--:--" class="form-control" 
				name="start" id="start" 
				required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Ende:</label> <input type="time" required="required" 
				placeholder="--:--" title="--:--" class="form-control" 
				name="end" id="end" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label>Typ:</label> <select class="form-control" name="type" id="type" onchange="showHideTypeOther()">
				<?php foreach ( $eventtypes as $type ) : ?>
					<option value="<?= $type->uuid; ?>"><?= $type->type; ?></option>
				<?php endforeach; ?>
			</select>
	</div>
	
	<div class="form-group" id="groupTypeOther" style=>
		<label>Sonstiger Wachtyp:</label> <input type="text" required="required"
			class="form-control" name="typeOther" id="typeOther"
			placeholder="Wachtyp eingeben">
	</div>
		
	<div class="form-group">
		<label>Titel:</label> <input type="text"
			class="form-control" name="title" id="title"
			placeholder="Titel eingeben">
	</div>
	
	<div class="form-group">
		<label>Zuständiger Löschzug/Verwaltung:</label> <select
			class="form-control" name="engine" required="required">
			<option value="" disabled selected>Bitte auswählen</option>
			<?php foreach ( $engines as $option ) : ?>
			<option value="<?=  $option->uuid; ?> "><?= $option->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	
	
	<div class="form-check">
		<input type="checkbox" class="form-check-input" name="ilsEntry" id="ilsEntry"> <label
			for="ilsEntry">Wache durch ILS angelegt</label>
	</div>
	
	<div class="form-check">
		<input type="checkbox" class="form-check-input" name="noIncidents" id="noIncidents"> <label
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
	<p class="h6">Wachpersonal hinzufügen:</p>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUnitModal" onClick="initializeModal()">Ohne Fahrzeug</button>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUnitModal" onClick="initializeModalVehicle()">Mit Fahrzeug</button>
	<p>
	<div id="unitlist">

	</div>
	<p>
	<div id="submitPlaceholder">
	</div>

</form>

<script src="js/date.js"></script>
<script type='text/javascript'>
	
	showHideTypeOther();
	
	var reportUnitCount = 0;
	
	function addReportUnit(){
		reportUnitCount += 1;

		var unit = document.getElementById("unit").value;
		var km = document.getElementById("km").value;

		if(km == ""){
			var headerString = unit;
		} else {
			var headerString = unit + " (" + km + " km)";
		}
		
		addUnitCard(reportUnitCount, headerString);
		
		if(reportUnitCount == 1){
			var div = document.getElementById("submitPlaceholder");
			var input1 = document.createElement("input");
			input1.id = "formSubmit";
			input1.type = "submit";
			input1.value = "Abschicken";
			input1.className ="btn btn-primary";
			div.appendChild(input1);
			var input2 = document.createElement("input");
			input2.id = "formSend";
			input2.type = "hidden";
			input2.value = "send";
			input2.name ="action";
			div.appendChild(input2);			
		}
	}


	function removeLastReportUnit(){
		if(reportUnitCount != 0){
			var lastUnit = document.getElementById("unit"+reportUnitCount);
			lastUnit.parentNode.removeChild(lastUnit);
			reportUnitCount -= 1;

			if(reportUnitCount == 0){
				var formSubmit = document.getElementById("formSubmit");
				var formSend = document.getElementById("formSend");
				formSubmit.parentNode.removeChild(formSubmit);
				formSend.parentNode.removeChild(formSend);
			}
		}
	}

	function addUnitCard(position, title){
		var container = document.getElementById("unitlist");
		
		var card = document.createElement("div");
		card.className ="card";
		card.id = "unit" + position;

		var cardHeader = document.createElement("div");
		cardHeader.className ="card-header";

		var h5 = document.createElement("h5");
		h5.className ="mb-0";

		var button = document.createElement("button");
		button.className = "btn btn-link";
		button.type = "button";
		button.setAttribute("data-toggle", "collapse");
		button.setAttribute("data-target", "#collapse" + position);

		var headername = document.createTextNode(title);
		
		button.appendChild(headername);
		h5.appendChild(button);
		cardHeader.appendChild(h5);
		card.appendChild(cardHeader);

		var collapse = document.createElement("div");
		collapse.className = "collapse";
		collapse.id = "collapse" + position;
		collapse.setAttribute("data-parent", "#unitlist");

		var cardBody = document.createElement("div");
		cardBody.className = "card-body";
		
		addUnitCardBody(cardBody);

		collapse.appendChild(cardBody);
		card.appendChild(collapse);
		
		container.appendChild(card);

		var removeUnit = document.getElementById("unit"+reportUnitCount+"delete");
		removeUnit.onclick = removeLastReportUnit;

		//var editUnit = document.getElementById("unit"+reportUnitCount+"edit");
		//editUnit.onclick = 
		
		button.click();
	}


	function addUnitCardBody(cardBody){
		var form = document.getElementById("addUnitForm");

		var dateReg = /^\d{2}[.]\d{2}[.]\d{4}$/
		var unitdate = form.querySelector("#unitdate").value;
		if(unitdate.match(dateReg)){
			var parts =unitdate.split('.');
			var unitdate = new Date(parts[2], parts[1] - 1, parts[0]); 

			//no support for IE
			//var unitdate = getDateFromFormat(unitdate, "dd.MM.yyyy");
		}
		unitdate = new Date(unitdate).toLocaleDateString("de-DE");
		
		var unitstart = form.querySelector("#unitstart").value;
		var unitend = form.querySelector("#unitend").value;
		
		var row = document.createElement("div");
		row.className = "row form-group";
		cardBody.appendChild(row);

		appendInput(row, "unit"+reportUnitCount+"date", unitdate, "Datum:");
		
		appendInput(row, "unit"+reportUnitCount+"start", unitstart, "Wachbeginn:");
		appendInput(row, "unit"+reportUnitCount+"end", unitend, "Ende:");

		var unit = document.getElementById("unit").value;
		var km = document.getElementById("km").value;

		var unitField = document.createElement("input");
		unitField.id = "unit"+reportUnitCount+"unit";
		unitField.name = "unit"+reportUnitCount+"unit";
		unitField.type = "text";
		unitField.value = unit;
		unitField.type = "hidden";
		cardBody.appendChild(unitField);

		var kmField = document.createElement("input");
		kmField.id = "unit"+reportUnitCount+"km";
		kmField.name = "unit"+reportUnitCount+"km";
		kmField.type = "text";
		kmField.value = km;
		kmField.type = "hidden";
		cardBody.appendChild(unitField);
		
		var label = document.createElement("label");
		label.innerHTML = "Personal:";
		cardBody.appendChild(label);
		
		for (i = 1; i <= reportPositionCount; i++) {
			var rowStaff = document.createElement("div");
			rowStaff.className = "row form-group";
			
			var position = form.querySelector("#position" + i);
			var posFunction = position.querySelector("#positionfunction").value;
			var posName = position.querySelector("#positionname").value;
			var posEngine = position.querySelector("#positionengine").value;
			
			appendInput(rowStaff, "unit"+reportUnitCount+"function"+i, posFunction, null);
			appendInput(rowStaff, "unit"+reportUnitCount+"name"+i, posName, null);
			appendInput(rowStaff, "unit"+reportUnitCount+"engine"+i, posEngine, null);
			
			cardBody.appendChild(rowStaff);
		}
		
		var remove = document.createElement("button");
		remove.id = "unit"+reportUnitCount+"delete"
		remove.type = "button";
		remove.className = "btn btn-outline-primary btn-sm";
		remove.appendChild(document.createTextNode("Entfernen"));
		cardBody.appendChild(remove);
	}

	function appendInput(parent, name, value, labeltext){
		var col = document.createElement("div");
		col.className = "col-sm";
		
		if(labeltext != null){
			var label = document.createElement("label");
			label.innerHTML = labeltext;
			col.appendChild(label);
		}
		
		var input = document.createElement("input");
		input.className = "form-control  bg-white";
		input.id = name;
		input.name = name;
		input.type = "text";
		input.readOnly = true;
		input.value = value;

		col.appendChild(input);

		parent.appendChild(col);
	}

	function showHideTypeOther(){
		var type = document.getElementById("type");
		var selectedType = type.options[type.selectedIndex].text;

	    var groupTypeOther = document.getElementById("groupTypeOther");
	    var typeOther = document.getElementById("typeOther");
		
		if(selectedType == "Sonstige Wache"){
			typeOther.setAttribute("required", "");
			groupTypeOther.style.display = "block";
		} else {
			typeOther.removeAttribute("required");
			groupTypeOther.style.display = "none";
		}
	}

</script>