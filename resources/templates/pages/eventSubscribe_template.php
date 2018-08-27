<form
	action="<?= "event_subscribe.php?id=".$eventUUID."&staffid=".$staffUUID ?>"
	method="post">
	<legend>Für Wache eintragen</legend>
	<div class="form-group">
		<label>Vorname:</label> <input type="text" class="form-control"
			required="required" name="firstname" id="firstname"
			placeholder="Vorname eingeben">
	</div>
	<div class="form-group">
		<label>Nachname:</label> <input type="text" class="form-control"
			required="required" name="lastname" id="lastname"
			placeholder="Nachname eingeben">
	</div>
	<div class="form-group">
		<label>E-Mail:</label> <input type="email" class="form-control"
			required="required" name="email" id="email"
			placeholder="E-Mail eingeben">
	</div>
	<div class="form-group">
		<label>Löschzug:</label> 
		<select class="form-control" name="engine" required="required">
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) : ?>
			<option value="<?=  $option->uuid; ?>"><?= $option->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<input type="submit" value="Eintragen" class="btn btn-primary">
<?php 
if (isset ( $eventUUID )) {
	echo "<a href='event_details.php?id=" . $eventUUID . "' class=\"btn btn-outline-primary\">Zurück</a>";
}
?>
</form>