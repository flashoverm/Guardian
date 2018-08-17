<?php
require_once '../resources/templates/header.php';
require_once '../resources/library/secured_page.php';
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';
require_once '../resources/library/mail_controller.php';
?>

<div class="jumbotron text-center">
	<h1>Übersicht Wachbeauftragte</h1>
</div>
<div class="container">

<?php
if (! $enable_self_registration) {
	$showFormular = false;
	showAlert ( "Kein Administrator angemeldet - <a href=\"event_overview.php\" class=\"alert-link\">ZurÃ¼ck</a>" );
} else {
	$showFormular = true;
}

if ($showFormular) {
	if (isset ( $_POST ['disable'] )) {
		$delete_manager_uuid = trim ( $_POST ['disable'] );
		deactivate_manager ( $delete_manager_uuid );
		// if ok
		showSuccess ( "Wachbeauftragter deaktiviert" );
	}
	if (isset ( $_POST ['enable'] )) {
		$delete_manager_uuid = trim ( $_POST ['enable'] );
		reactivate_manager ( $delete_manager_uuid );
		// if ok
		showSuccess ( "Wachbeauftragter aktiviert" );
	}

	if (isset ( $_POST ['resetpw'] )) {
		$resetpw_manager_uuid = trim ( $_POST ['resetpw'] );
		$password = reset_password ( $resetpw_manager_uuid );
		mail_reset_password ( $resetpw_manager_uuid, $password );
		// if ok
		showSuccess ( "Passwort zurückgesetzt und per E-Mail zugestellt" );
	}
	$data = get_manager ();
	if (! count ( $data )) {
		showInfo ( "Es sind keine Wachbeauftragten angelegt" );
	} else {
?>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Vorname</th>
					<th>Nachname</th>
					<th>LÃ¶schzug</th>
					<th>E-Mail</th>
					<th>Anmeldung</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
	<?php
		foreach ( $data as $row ) {
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
								value="Passwort zurÃ¼cksetzen" class="btn btn-primary btn-sm" />
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
	</div>
</div>

<?php
	}
}
?>

<footer>
	<div class="container">
		<a href='event_overview.php' class="btn btn-outline-primary">Zurück</a>
		
		<?php
		if ($showFormular) {
			echo "<a href='manager_create.php' class=\"btn btn-primary\">Wachbeauftragten anlegen</a>";
		}
		?>
		
		<a href='change_password.php' class="btn btn-outline-primary">Passwort
			Ã¤ndern</a> <a href='logout.php' class="btn btn-outline-primary">Abmelden</a>
	</div>
	
<?php require_once '../resources/templates/footer.php'; ?>