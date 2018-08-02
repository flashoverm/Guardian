<?php
require_once 'inc/page_head.php';
require_once 'inc/secured_page.php';
require_once 'inc/db_user.php';
require_once 'inc/mail_controller.php';
?>
<div class="jumbotron text-center">
	<h1>Übersicht Wachbeauftragte</h1>
</div>
<div class="container">
<?php
$data = get_manager();
if (!count($data)) {
    showInfo("Es sind keine Wachbeauftragten angelegt");
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
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($data as $row) {
					$engine = get_engine($row->engine);
				?>
					<tr>
						<td><?php echo $row->firstname; ?></td>
						<td><?php echo $row->lastname; ?></td>
						<td><?php echo $engine->name; ?></td>
						<td><?php echo $row->email; ?></td>
						<td><?php 
									if($row->loginenabled){
										echo "Aktiv"; 
									} else {
										echo "Deaktiviert"; 
									}
							?>
						</td>
						<td>
							<form method="post" action="">
								<input type="hidden" name="resetpw" id="resetpw" value="<?=$row->uuid?>"/>
								<input type="submit" value="Passwort zurücksetzen" class="btn btn-primary btn-sm"/>
							</form>
						</td>
						<td>
							<form method="post" action="">
								<?php 
									if($row->loginenabled){
										echo "<input type=\"hidden\" name=\"disable\" id=\"disable\" value='".$row->uuid."'/>"; 
										echo "<input type=\"submit\" value=\"Deaktivieren\"  class=\"btn btn-outline-primary btn-sm\"/>";
									} else {
										echo "<input type=\"hidden\" name=\"enable\" id=\"enable\" value='".$row->uuid."'/>"; 
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

if(isset($_POST['disable'])) {
	$delete_manager_uuid = trim($_POST['disable']);
	showSuccess("Wachbeauftragter deaktiviert");
	deactivate_manager($delete_manager_uuid);
}
if(isset($_POST['enable'])) {
	$delete_manager_uuid = trim($_POST['enable']);
	showSuccess("Wachbeauftragter aktiviert");
	reactivate_manager($delete_manager_uuid);
}

if(isset($_POST['resetpw'])) {
	$resetpw_manager_uuid = trim($_POST['resetpw']);
	showSuccess("Passwort zurückgesetzt und per E-Mail zugestellt");
	$password = reset_password($resetpw_manager_uuid);
	mail_reset_password($resetpw_manager_uuid, $password);
}

?>
<footer>
	<div class="container">
		<a href='event_overview.php' class="btn btn-outline-primary">Zurück</a>
		<a href='manager_create.php' class="btn btn-primary">Wachbeauftragten anlegen</a>
		<a href='change_password.php' class="btn btn-outline-primary">Passwort ändern</a>
		<a href='logout.php' class="btn btn-outline-primary">Abmelden</a>
	</div>
<?php require_once 'inc/page_end.php'; ?>