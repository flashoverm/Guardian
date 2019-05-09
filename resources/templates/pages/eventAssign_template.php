<form onsubmit="showLoader()"
	action="<?= $config["urls"]["html"] . "/events/".$eventUUID."/assign/".$staffUUID ?>"
	method="post">
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
	
	<?php
	if (isset ( $eventUUID )) {
		echo "<a href='" . $config["urls"]["html"] . "/events/" . $eventUUID . "' class=\"btn btn-outline-primary\">Zurück</a>";
	}
	?>
	<input type="submit" value="Einteilen" class="btn btn-primary">

</form>
