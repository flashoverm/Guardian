<?php
require_once 'inc/page_head.php';
require_once 'inc/db_event.php';
require_once 'inc/db_user.php';
require_once 'inc/mail_controller.php';

session_start();

if(!isset($_GET['id'])) {
	?>
	<div class="jumbotron text-center">
		<h1>Wache kann nicht angezeigt werden</h1>
	</div>
	<div class="container">
	<?php
	showAltert("Wache kann nicht angezeigt werden");
} else {
	$uuid = trim($_GET['id']);
	$event = get_event($uuid);
	$staff = get_staff($uuid);
	
	$isManager = (strcmp($event->manager, $_SESSION['userid']) == 0);
	
	if(isset($_POST['staffid'])){
		$staff_uuid = trim($_POST['staffid']);
		mail_remove_staff_user($staff_uuid, $uuid);
		remove_staff_user($staff_uuid);
	}
?>
<div class="jumbotron text-center">
	<h1><?php echo $event->type; ?></h1>
</div>
<div class="container">
<?php
	if($isManager){
		showInfo("Du bist Verwalter dieser Wache");
	}
?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th>Datum</th>
					<th>Beginn</th>
					<th>Ende</th>
				</tr>
				<tr>
					<td><?php echo $event->date; ?></td>
					<td><?php echo $event->start_time; ?></td>
					<td><?php echo $event->end_time; ?></td>
				</tr>
				<tr><th colspan="3"><?php echo $event->title; ?></th></tr>
				<tr><td colspan="3"><?php echo $event->comment; ?></td></tr>
				<tr>
					<th>Position</th>
					<th>Personal</th>
					<th></th>
				</tr>
				<?php
				foreach ($staff as $entry) {
					if($entry->user != NULL){
						$user = get_user($entry->user);
						$engine = get_engine($user->engine);
						$name = $user->firstname." ".$user->lastname." (".$engine->name.")";
					}
				?>
					<tr>
						<td><?php echo $entry->position; ?></td>
						<td><?php if($entry->user != NULL){echo $name; }?></td> 
						<td><?php 
							if($entry->user == NULL){
								echo "<form method=\"post\" action=\"event_subscribe.php?id=".$uuid."&staffid=".$entry->uuid."\">
										<input type=\"submit\" value=\"Eintragen\" class=\"btn btn-primary btn-sm\"/>
									</form>";
							}
							if($entry->user != NULL and $isManager){
								echo "<form method=\"post\" action=\"event_details.php?id=".$uuid."\">
										<input type=\"hidden\" name=\"staffid\" id=\"staffid\" value=\"".$entry->uuid."\"/>
										<input type=\"submit\" value=\"Entfernen\" class=\"btn btn-outline-primary btn-sm\"/>
									</form>";
							}
						?></td>				
					</tr>
				<?php
				}
				?>
				<tr><td colspan="3"><?php echo "/guardian/event_details.php?id=".$event->uuid; ?></td></tr>
			</tbody>
		</table>
	</div>
<?php
}
?>
</div>
<footer>
	<div class="container">
		<?php
		if(isset($_SESSION['userid'])) {
			echo "<a href='event_overview.php' class=\"btn btn-outline-primary\">Zurück</a>";
		}
		?>
	</div>
<?php require_once 'inc/page_end.php'; ?>