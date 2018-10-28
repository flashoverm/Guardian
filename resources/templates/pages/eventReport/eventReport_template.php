<?php include('eventReportUnit_template.php'); ?>

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
		<label>Typ:</label> <select class="form-control" name="type" id="type" onchange="showHideTypeOther()">
				<?php foreach ( $eventtypes as $type ) : ?>
					<option value="<?= $type->type;  //Change to $type->uuid for database usage	?>"><?= $type->type; ?></option>
				<?php endforeach; ?>
			</select>
	</div>
	
	<div class="form-group" id="groupTypeOther" style=>
		<label>Sonstiger Wachtyp:</label> <input type="text" required="required"
			class="form-control" name="typeOther" id="typeOther"
			placeholder="Wachtyp eingeben">
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
			<?php foreach ( $engines as $option ) : ?>
			<option value="<?=  $option->name; //Change to $option->uuid for database usage	?> "><?= $option->name; ?></option>
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

	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUnitModal" onClick="initializeModal()">Fahrzeug/Station hinzufügen</button><p>

	<div id="unitlist">
	</div>
	<p>
	<div id="submitPlaceholder">
	</div>

</form>

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
		var unitdate = new Date(form.querySelector("#unitdate").value).toLocaleDateString("de-DE");
		var unitstart = form.querySelector("#unitstart").value;
		var unitend = form.querySelector("#unitend").value;
		
		var rowHead = document.createElement("div");
		rowHead.className = "row";

		cardBody.appendChild(rowHead);

		appendInput(rowHead, "unit"+reportUnitCount+"date", unitdate, "Datum:", false);
		appendInput(rowHead, "unit"+reportUnitCount+"start", unitstart, "Beginn:", false);
		appendInput(rowHead, "unit"+reportUnitCount+"end", unitend, "Ende:", false);

		var unit = document.getElementById("unit").value;
		var km = document.getElementById("km").value;

		appendInput(rowHead, "unit"+reportUnitCount+"unit", unit, null, true);
		appendInput(rowHead, "unit"+reportUnitCount+"km", km, null, true);	
		
		var label = document.createElement("label");
		label.innerHTML = "Personal:";
		cardBody.appendChild(label);
		
		//var unit = form.querySelector("unit");
		//var km = form.querySelector("km");
		
		for (i = 1; i <= reportPositionCount; i++) {
			var rowBody = document.createElement("div");
			rowBody.className = "row";
			
			var position = form.querySelector("#position" + i);
			var posFunction = position.querySelector("#positionfunction").value;
			var posName = position.querySelector("#positionname").value;
			var posEngine = position.querySelector("#positionengine").value;
			appendInput(rowBody, "unit"+reportUnitCount+"function"+i, posFunction, null, false);
			appendInput(rowBody, "unit"+reportUnitCount+"name"+i, posName, null, false);
			appendInput(rowBody, "unit"+reportUnitCount+"engine"+i, posEngine, null, false);
			cardBody.appendChild(rowBody);
		}

		/*
		var edit = document.createElement("button");
		edit.id = "unit"+reportUnitCount+"edit;
		edit.type = "button";
		edit.className = "btn btn-primary btn-sm";
		edit.setAttribute("data-toogle", "modal");
		edit.setAttribute("data-target", "#addUnitModal");
		edit.appendChild(document.createTextNode("Bearbeiten"));
		cardBody.appendChild(edit);
		*/	
		var remove = document.createElement("button");
		remove.id = "unit"+reportUnitCount+"delete"
		remove.type = "button";
		remove.className = "btn btn-outline-primary btn-sm";
		remove.appendChild(document.createTextNode("Entfernen"));
		cardBody.appendChild(remove);
	}

	function appendInput(parent, name, value, labeltext, hidden){
		var col = document.createElement("div");
		col.className = "col";

		var formgroup = document.createElement("div");
		formgroup.className = "form-group";

		if(labeltext != null){
			var label = document.createElement("label");
			label.innerHTML = labeltext;
			formgroup.appendChild(label);
		}
		
		var input = document.createElement("input");
		input.className = "form-control border-0 bg-white";
		input.id = name;
		input.name = name;
		input.type = "text";
		input.readOnly = true;
		input.value = value;
		if(hidden){
			input.type = "hidden";
		}

		formgroup.appendChild(input);
		col.appendChild(formgroup);

		parent.appendChild(col);
	}

	function showHideTypeOther(){
		var type = document.getElementById("type");
		var selectedType = type.options[type.selectedIndex].value;

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