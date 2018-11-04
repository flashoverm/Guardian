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
				<th class="text-center">Vorname</th>
				<th class="text-center">Nachname</th>
				<th class="text-center">Löschzug</th>
				<th class="text-center">E-Mail</th>
				<th class="text-center">Anmeldung</th>
				<th class="text-center"></th>
				<th class="text-center"></th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach ( $manager as $row ) {
			?>
					<tr>
				<td class="text-center"><?= $row->firstname; ?></td>
				<td class="text-center"><?= $row->lastname; ?></td>
				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
				<td class="text-center"><?= $row->email; ?></td>
				<td class="text-center">
			<?php
				if ($row->loginenabled) {
					echo "Aktiv";
				} else {
					echo "Deaktiviert";
				}
			?>
				</td>
				<td class="text-center">
					<form method="post" action="">
						<input type="hidden" name="resetpw" id="resetpw" value="<?=$row->uuid?>" />
						<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#confirmReset">Passwort zurücksetzen</button>
						
						<div class="modal" id="confirmReset">
						  <div class="modal-dialog">
						    <div class="modal-content">
						
						      <div class="modal-header">
						        <h4 class="modal-title">Passwort wirklich zurücksetzen?</h4>
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						      </div>
						
						      <div class="modal-footer">
						      	<input type="submit" value="Passwort zurücksetzen" class="btn btn-primary" onClick="showLoader()"/>
						      	<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Abbrechen</button>
						      </div>
						
						    </div>
						  </div>
						</div>
					</form>
				</td>
				<td class="text-center">
					<form method="post" action="">
			<?php
			if($row->uuid != $_SESSION ['guardian_userid']){
				if ($row->loginenabled) {
					echo "<input type=\"hidden\" name=\"disable\" id=\"disable\" action='\manager_overview.php' value='" . $row->uuid . "'/>";
					echo "<input type=\"submit\" value=\"Deaktivieren\"  class=\"btn btn-outline-primary btn-sm\"/>";
				} else {
					echo "<input type=\"hidden\" name=\"enable\" id=\"enable\" action='\manager_overview.php' value='" . $row->uuid . "'/>";
					echo "<input type=\"submit\" value=\"Aktivieren\"  class=\"btn btn-primary btn-sm\"/>";
				}
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