
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


function addUnit(){

	var form = document.getElementById("addUnitForm");

	var unit = document.getElementById("unit").value;
	var km = document.getElementById("km").value;

	var unitdate = form.querySelector("#unitdate").value;
			
	var unitstart = form.querySelector("#unitstart").value;
	var unitend = form.querySelector("#unitend").value;

	reportUnitCount += 1;

	var template = document.getElementById("unit0");

	var newUnit = template.cloneNode(true);

	newUnit.id = "unit" + reportUnitCount;
	newUnit.style.display = null;	

	var inputs = newUnit.getElementsByTagName("input");
	nameField(inputs[0], "unit" + reportUnitCount + "date");
	inputs[0].value = unitdate;
	nameField(inputs[1], "unit" + reportUnitCount + "start");
	inputs[1].value = unitstart;
	nameField(inputs[2], "unit" + reportUnitCount + "end");
	inputs[2].value = unitend;
	nameField(inputs[3], "unit" + reportUnitCount + "unit");
	inputs[3].value = unit;
	nameField(inputs[4], "unit" + reportUnitCount + "km");
	inputs[4].value = km;
	
	nameField(inputs[5], "unit" + reportUnitCount + "datefield");
	inputs[5].value = unitdate;
	nameField(inputs[6], "unit" + reportUnitCount + "startfield");
	inputs[6].value = unitstart;
	nameField(inputs[7], "unit" + reportUnitCount + "endfield");
	inputs[7].value = unitend;

	var personalContainer = newUnit.getElementsByClassName("personalContainer");
	
	for (i = 1; i <= reportPositionCount; i++) {
		var personalTemplate = newUnit.getElementsByClassName("unitpersonaltemplate");

		var newPersonal = personalTemplate[0].cloneNode(true);
		newPersonal.style.display = null;	

		var position = form.querySelector("#position" + i);
		
		var inputs = newPersonal.getElementsByTagName("input");
		nameField(inputs[0], "unit" + reportUnitCount + "name" + i);
		inputs[0].value = position.querySelector("#positionname").value;

		nameField(inputs[1], "unit" + reportUnitCount + "function" + i + "field");
		inputs[1].value = position.querySelector("#positionfunction").value;
		nameField(inputs[2], "unit" + reportUnitCount + "name" + i + "field");
		inputs[2].value = position.querySelector("#positionname").value;
		nameField(inputs[3], "unit" + reportUnitCount + "engine" + i + "field");
		inputs[3].value = position.querySelector("#positionengine").value;
		
		var selects = newPersonal.getElementsByTagName("select");
		nameField(selects[0], "unit" + reportUnitCount + "function" + i)
		selects[0].selectedIndex = position.querySelector("#positionfunction").selectedIndex;
		nameField(selects[1], "unit" + reportUnitCount + "engine" + i)
		selects[1].selectedIndex = position.querySelector("#positionengine").selectedIndex;
		
		personalContainer[0].appendChild(newPersonal);
	}

	var cardbody = newUnit.getElementsByClassName("unittemplateBody");
	cardbody[0].id = "collapse" + reportUnitCount;

	var buttons = newUnit.getElementsByTagName("button");
	buttons[0].setAttribute("data-target", "#collapse" + reportUnitCount);

	if(km == ""){
		var headerString = unit;
	} else {
		var headerString = unit + " (" + km + " km)";
	}
	buttons[0].appendChild(document.createTextNode(headerString));

	buttons[1].setAttribute("onclick", "initializeModalEdit("+ reportUnitCount +");");
	buttons[2].setAttribute("onclick", "removeUnit("+ reportUnitCount +");");

	var unitNo = document.getElementById("unitNo");
	if(unitNo.value != ""){
		var unitElement = document.getElementById("unit" + unitNo.value);
		insertAfter(newUnit, unitElement);
		unitElement.parentNode.removeChild(unitElement);
	} else {
		var container = document.getElementById("unitlist");
		container.appendChild(newUnit);
	}

	buttons[0].click();
	
	displaySubmitButton();	
}

function displaySubmitButton(){
	
	if(reportUnitCount == 1){
		var submitButton = document.getElementById("submitReport");
		submitButton.style.display = "block";
		var div = document.getElementById("submitPlaceholder");			
	}
}

function removeUnit(number){
	var unit = documentgetElementById("unit" + number);
	unit.parentNode.removeChild(unit);
}

function nameField(field, name){
	field.name = name;
	field.id = name;
}


function addReportStaffPosition(){
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
	newPosition.querySelector("#positionengine").selectedIndex=reportEngine;
	
	container.appendChild(newPosition);
}

function removeLastReportStaffPosition(){
	if(reportPositionCount != 1){
		var lastStaffRow = document.getElementById("position"+reportPositionCount);
		lastStaffRow.parentNode.removeChild(lastStaffRow);
		reportPositionCount -= 1;
	}
}

function clearUnitForm(){
	var form = document.getElementById("addUnitForm");

	while(reportPositionCount > 1){
		removeLastReportStaffPosition();
	}

	form.reset();

	var unit = document.getElementById("unit");
	var km = document.getElementById("km");
	unit.disabled = false;
	km.disabled = false;
	
	var unitNo = document.getElementById("unitNo");
	unitNo.value = "";

	var addButton = document.getElementById("addUnit");
	addButton.value = "Hinzufügen";
}

function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}



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

	if(km.value || unit.value != stationString){
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
}

function addExistingStaffPosition(unitnumber, positionNo) {
	var positionfunction = document.getElementById("unit" + unitnumber + "function" + positionNo);
	var positionname = document.getElementById("unit" + unitnumber + "name" + positionNo);
	var positionengine = document.getElementById("unit" + unitnumber + "engine" + positionNo);

	var position = form.querySelector("#position" + positionNo);
	position.querySelector("#positionname").value = positionname.value;
	position.querySelector("#positionfunction").selectedIndex = positionfunction.selectedIndex;
	position.querySelector("#positionengine").selectedIndex = positionengine.selectedIndex;
}
	