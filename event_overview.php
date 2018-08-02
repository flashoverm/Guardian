<?php
require_once 'inc/page_head.php';
require_once 'inc/secured_page.php';
require_once 'inc/db_user.php';
require_once 'inc/db_event.php';
require_once 'inc/mail_controller.php';

?>
<div class="jumbotron text-center">
	<h1>Übersicht Wachen</h1>
</div>
<div class="container">
<?php
$data = get_events();
if (!count($data)) {
    showInfo("Es sind keine Wachen offen");
} else {
?>
<div class="table-responsive">
	<h2>Übersicht Wachen</h2>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Datum</th>
				<th>Start</th>
				<th>Ende</th>
				<th>Ort</th>
				<th>Typ</th>
				<th>Titel</th>
				<th>Details</th>
				<th>Löschen</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($data as $row) {
			?>
				<tr>
					<td><?php echo $row->date; ?></td>
					<td><?php echo $row->start_time; ?></td>
					<td><?php echo $row->end_time; ?></td>
					<td><?php echo $row->location; ?></td>
					<td><?php echo $row->type; ?></td>
					<td><?php echo $row->title; ?></td>
					<td>
						<form method="post" action="<?= "event_details.php?id=".$row->uuid ?>">
							<input type="submit" value="Details" class="btn btn-primary btn-sm" />
						</form>
					</td>
					<td>
						<form method="post" action="">
							<input type="hidden" name="delete" id="delete" value="<?=$row->uuid?>"/>
							<input type="submit" value="Löschen" class="btn btn-outline-primary btn-sm"/>
						</form>
					</td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>
<?php   
}
if(isset($_POST['delete'])) {
	$delete_event_uuid = trim($_POST['delete']);
	showSuccess("Wache gelöscht");
	mail_delete_event($delete_event_uuid);
	delete_event($delete_event_uuid);
}
?>
</div>
<footer>
	<div class="container">
		<a href='event_create.php' class="btn btn-primary">Wache anlegen</a>
		<a href='change_password.php' class="btn btn-outline-primary">Passwort ändern</a>
		<a href='manager_overview.php' class="btn btn-outline-primary">Administration</a>
		<a href='logout.php' class="btn btn-outline-primary">Abmelden</a>
	</div>
<?php require_once 'inc/page_end.php'; ?>

