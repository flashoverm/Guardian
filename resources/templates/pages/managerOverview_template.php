<?php
require_once '../resources/library/db_engines.php';

if (! $isAdmin) {
	showAlert ( "Kein Administrator angemeldet - <a href=\"event_overview.php\" class=\"alert-link\">Zurück</a>" );
} else {
	if (! count ( $manager )) {
		showInfo ( "Es sind keine Wachbeauftragten angelegt" );
	} else {
		?>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Vorname</th>
				<th>Nachname</th>
				<th>Löschzug</th>
				<th>E-Mail</th>
				<th>Anmeldung</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach ( $manager as $row ) {
			?>
					<tr>
				<td><?= $row->firstname; ?></td>
				<td><?= $row->lastname; ?></td>
				<td><?= get_engine($row->engine)->name; ?></td>
				<td><?= $row->email; ?></td>
				<td>
	<?php
			if ($row->loginenabled) {
				echo "Aktiv";
			} else {
				echo "Deaktiviert";
			}
			?>
						</td>
				<td>
					<form method="post" action="">
						<input type="hidden" name="resetpw" id="resetpw"
							value="<?=$row->uuid?>" /> <input type="submit"
							value="Passwort zurücksetzen" class="btn btn-primary btn-sm" />
					</form>
				</td>
				<td>
					<form method="post" action="">
	<?php
			if ($row->loginenabled) {
				echo "<input type=\"hidden\" name=\"disable\" id=\"disable\" action='\manager_overview.php' value='" . $row->uuid . "'/>";
				echo "<input type=\"submit\" value=\"Deaktivieren\"  class=\"btn btn-outline-primary btn-sm\"/>";
			} else {
				echo "<input type=\"hidden\" name=\"enable\" id=\"enable\" action='\manager_overview.php' value='" . $row->uuid . "'/>";
				echo "<input type=\"submit\" value=\"Aktivieren\"  class=\"btn btn-primary btn-sm\"/>";
			}
			?>
							</form>
				</td>
			</tr>
	<?php
		}
		?>
			</tbody>
	</table>
	<a href='manager_create.php' class="btn btn-primary">Wachbeauftragten anlegen</a>
</div>
<?php
	}
}
?>