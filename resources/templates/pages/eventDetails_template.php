<?php
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';

if ($isManager) { 
	showInfo ( "Du bist Verwalter dieser Wache" ); 
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
				<td><?= $event->date; ?></td>
				<td><?= $event->start_time; ?></td>
				<td><?php
				if ($event->end_time != 0) {
					echo $event->end_time;
				} else {
					echo " - ";
				}
				?></td>
			</tr>
			<tr>
				<th colspan="3"><?= $event->title; ?></th>
			</tr>
			<tr>
				<td colspan="3"><?= $event->comment; ?></td>
			</tr>
			<tr>
				<th>Funktion</th>
				<th>Personal</th>
				<th></th>
			</tr>
				<?php
				foreach ( $staff as $entry ) {
					if ($entry->user != NULL) {
						$user = get_user ( $entry->user );
						$engine = get_engine ( $user->engine );
						$name = $user->firstname . " " . $user->lastname . " (" . $engine->name . ")";
					}
					?>
					<tr>
				<td><?= $entry->position; ?></td>
				<td><?php if($entry->user != NULL){echo $name; }?></td>
				<td><?php
					if ($entry->user == NULL) {
						echo "<form method=\"post\" action=\"event_subscribe.php?id=" . $event->uuid . "&staffid=" . $entry->uuid . "\">
										<input type=\"submit\" value=\"Eintragen\" class=\"btn btn-primary btn-sm\"/>
									</form>";
					}
					if ($entry->user != NULL and $isManager) {
						echo "<form method=\"post\" action=\"event_details.php?id=" . $event->uuid . "\">
										<input type=\"hidden\" name=\"staffid\" id=\"staffid\" value=\"" . $entry->uuid . "\"/>
										<input type=\"submit\" value=\"Entfernen\" class=\"btn btn-outline-primary btn-sm\"/>
									</form>";
					}
					?></td>
			</tr>
				<?php
				}
				?>
				<tr>
				<td colspan="3"><?= "/guardian/event_details.php?id=".$event->uuid; ?></td>
			</tr>
		</tbody>
	</table>
	<?php
	if($loggedIn){
		echo "<a href='event_overview.php' class='btn btn-primary'>Zurück</a>";
	}
	?>
</div>