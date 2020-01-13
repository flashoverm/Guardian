<?php include('reportEditUnit_template.php'); ?>
<form onsubmit="showLoader()" method="post" >
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Datum:</label> <input type="date" required="required" 
				placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ"	class="form-control" 
				name="date" id="date" 
				<?php
				if(isset($object) ){
					echo "value='" . $object->date . "'";
				}?>
				required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Wachbeginn:</label> <input type="time" required="required" 
				placeholder="--:--" title="--:--" class="form-control" 
				name="start" id="start" 
				<?php
				if(isset($object) ){
					echo "value='" . $object->start_time . "'";
				}?>
				required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Ende:</label> <input type="time" required="required" 
				placeholder="--:--" title="--:--" class="form-control" 
				<?php
				if(isset($object) && $object->end_time != null ){
					echo "value='" . $object->end_time . "'";
				}?>
				name="end" id="end" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label>Typ:</label> <select class="form-control" name="type" id="type" onchange="showHideTypeOther()">
			<?php foreach ( $eventtypes as $type ) : 	
			if(isset($object) && $type->uuid == $object->type) {
			    ?>
				<option value="<?= $type->uuid; ?>" selected><?= $type->type; ?></option>
			<?php } else {?>
				<option value="<?= $type->uuid; ?>"><?= $type->type; ?></option>
			<?php }
			endforeach; ?>
		</select>
	</div>
	
	<div class="form-group" id="groupTypeOther" style=>
		<label>Sonstiger Wachtyp:</label> <input type="text" required="required"
			class="form-control" name="typeOther" id="typeOther"
			<?php
			if(isset($object) && $object->type_other != null){
				echo "value='" . $object->type_other . "'";
			}?>
			placeholder="Wachtyp eingeben">
	</div>
		
	<div class="form-group">
		<label>Titel (optional):</label> <input type="text"
			class="form-control" name="title" id="title"
		    <?php
		    if(isset($object) && $object->title != null){
		    	echo "value='" . $object->title . "'";
			}?>
			placeholder="Titel eingeben">
	</div>
	
	<div class="form-group">
		<label>Zuständiger Löschzug/Verwaltung:</label> <select
			class="form-control" name="engine" required="required">
			<option value="" disabled selected>Bitte auswählen</option>
			<?php foreach ( $engines as $option ) :
			if(isset($object) && $option->uuid == $object->engine){
			?>
			<option value="<?=  $option->uuid; ?> " selected><?= $option->name; ?></option>
			<?php 
			}else{
		    ?>
			<option value="<?=  $option->uuid; ?> "><?= $option->name; ?></option>
			<?php } endforeach; ?>
		</select>
	</div>
		
	<div class="form-check">
		<input type="checkbox" class="form-check-input" name="ilsEntry" id="ilsEntry" 
		<?php if(isset($object) && property_exists($object, "ilsEntry") && $object->ilsEntry) { echo "checked"; } ?>
		> <label for="ilsEntry">Wache durch ILS angelegt</label>
	</div>
	
	<div class="form-check">
		<input type="checkbox" class="form-check-input" name="noIncidents" id="noIncidents"
		<?php if(isset($object) && property_exists($object, "noIncidents") && $object->noIncidents) { echo "checked"; } ?>
		> <label for="noIncidents">Keine Vorkomnisse</label>
	</div>
	
	<div class="form-group">
		<label>Bericht:</label>
		<textarea class="form-control" name="report" id="report" placeholder="Bericht"
		><?php if(isset($object) && property_exists($object, "report")) { echo $object->report; } ?></textarea>
	</div>
	
	<div class="form-group">
		<label>Ersteller:</label> <input type="text" required="required"
			class="form-control" name="creator" id="creator"
			<?php
			if(isset($object) && property_exists($object, "creator")){
				echo "value='" . $object->creator . "'";
			}?>
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

<script src="<?=$config["urls"]["baseUrl"] ?>/js/date.js" type="text/javascript"></script>
<script src="<?=$config["urls"]["baseUrl"] ?>/js/reportEdit.js" type="text/javascript"></script>
<script type="text/javascript">

showHideTypeOther();

var reportUnitCount = 0;
var currentPosition = 1;

</script>
