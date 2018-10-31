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
				<th>Wachbeginn</th>
				<th>Ende</th>
			</tr>
			<tr>
				<td><?= date($config ["formats"] ["date"], strtotime($event->date)); ?></td>
				<td><?= date($config ["formats"] ["time"], strtotime($event->start_time)); ?></td>
				<td><?php
				if ($event->end_time != 0) {
				    echo date($config ["formats"] ["time"], strtotime($event->end_time));
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
						echo "<form method='post' action='event_subscribe.php?id=" . $event->uuid . "&staffid=" . $entry->uuid . "'>
										<input type='submit' value='Eintragen' class='btn btn-primary btn-sm'/>
									</form>";
					}
					if ($entry->user != NULL and $isManager) {
						echo "<form method='post' action='event_details.php?id=" . $event->uuid . "'>
								<input type='hidden' name='staffid' id='staffid' value='" . $entry->uuid . "'/>
								<button type='button' class='btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#confirmUnscribe'>Austragen</button>
								
								<div class='modal' id='confirmUnscribe'>
								  <div class='modal-dialog'>
								    <div class='modal-content'>
								
								      <div class='modal-header'>
								        <h4 class='modal-title'>Personal wirklich austragen?</h4>
								        <button type='button' class='close' data-dismiss='modal'>&times;</button>
								      </div>
								
								      <div class='modal-footer'>
								      	<input type='submit' value='Austragen' class='btn btn-primary' onClick='showLoader()'/>
								      	<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
								      </div>
								
								    </div>
								  </div>
								</div> 
							</form>";
					
					}
					?></td>
			</tr>
				<?php
				}
				?>
				<tr>
				<td colspan="3"><b>Link:&nbsp;</b> <p id="link"><?= $config ["urls"] ["baseUrl"] . "/event_details.php?id=".$event->uuid; ?></p></td>
			</tr>
		</tbody>
	</table>
	<?php
	if($loggedIn){
	    echo "<form action='event_details.php?id=" . $event->uuid . "' method='post'>
                  <a href='event_overview.php' class='btn btn-primary'>Zurück</a>";
		if($event->engine != NULL and $isManager){
            echo "<input type='hidden' name='publish' id='publish' value='publish'/>
                  <input type='submit' class='btn btn-primary' value='Veröffentlichen' onClick='showLoader()'/>";
		} else {
		    echo "&nbsp;<input type='button' disabled='disabled' class='btn btn-outline-primary' value='Wache ist öffentlich' />";
		}
	    echo "</form>";
	}
	?>
</div>