
<form action="event_report.php" method="post">
	<div class="form-group">
		<label>Datum:</label> <input type="date" required="required"
			class="form-control" name="date" id="date">
	</div>
	<div class="form-group">
		<label>Beginn:</label> <input type="time" required="required"
			class="form-control" name="start" id="start">
	</div>
	<div class="form-group">
		<label>Ende:</label> <input type="time" required="required"
			class="form-control" name="end" id="end">
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
		<label>Zuständiger Löschzug/Geschäftszimmer:</label> <select class="form-control"
			name="engine" required="required">
			<option value="" disabled selected>Bitte auswählen</option>
			<option value="<?=$config ["backoffice"]; ?>">Geschäftszimmer</option>
			<?php foreach ( $engines as $option ) : ?>
			<option value="<?=  $option->uuid; ?>"><?= $option->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-check">
		<input type="checkbox" class="form-check-input" id="noIncidents"> 
		<label for="noIncidents">Keine Vorkomnisse</label>
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
		
	<div class="form-group" id="staffContainer">
		<label>Benötigtes Wachpersonal:</label>
		<button type="button" style="float: right"
			class="btn btn-primary btn-sm" onClick="removeLast()">&minus;</button>
		<a style="float: right">&nbsp;</a>
		<button type="button" style="float: right"
			class="btn btn-primary btn-sm" onClick="addStaff()">+</button>
		<input class="form-control" type="text" required="required"
			name="staff1" id="staff1" placeholder="Funktionsbezeichnung eingeben">
	</div>
	<input type="submit" value="Anlegen" class="btn btn-primary"><br> <input
		type="hidden" name="action" value="save">
</form>