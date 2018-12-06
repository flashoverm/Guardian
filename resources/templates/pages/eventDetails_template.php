<?php
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';
require_once '../resources/library/db_staffpositions.php';

if ($isCreator) { 
	showInfo ( "Du bist Ersteller dieser Wache" ); 
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
				<td><?= get_staffposition($entry->position)->position; ?></td>
				<td><?php if($entry->user != NULL){echo $name; }?></td>
				<td><?php
					if ($entry->user == NULL) {
						echo "<form method='post' action='event_subscribe.php?id=" . $event->uuid . "&staffid=" . $entry->uuid . "'>
										<input type='submit' value='Eintragen' class='btn btn-primary btn-sm'/>
									</form>";
					}
					if ($entry->user != NULL and $isCreator) {
						echo "<form method='post' action='event_details.php?id=" . $event->uuid . "'>
								<input type='hidden' name='staffid' id='staffid' value='" . $entry->uuid . "'/>
								<button type='button' class='btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#confirmUnscribe" . $entry->uuid ."'>Austragen</button>
								
								<div class='modal' id='confirmUnscribe" . $entry->uuid . "'>
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
				<td colspan="3">
					<b>Link:&nbsp;</b> 
					<p id="link"><?= $config ["urls"] ["baseUrl"] . "/event_details.php?id=".$event->uuid; ?></p>
					<button id="btnCpy" onClick='copyToClipBoard()' class='btn btn-outline-primary btn-sm'>Link kopieren</button>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
	if($loggedIn){
	    echo "<form action='event_details.php?id=" . $event->uuid . "' method='post'>
                  <a href='event_overview.php' class='btn btn-primary'>Zurück</a>";
	    if(!$event->published and $isCreator){
            echo "	<input type='hidden' name='publish' id='publish' value='publish'/>
				  	<button type='button' class='btn btn-primary float-right' data-toggle='modal' data-target='#confirmPublish" . $event->uuid ."'>Veröffentlichen</button>

					<div class='modal' id='confirmPublish" . $event->uuid ."'>
					  <div class='modal-dialog'>
					    <div class='modal-content'>
			
					      <div class='modal-header'>
					        <h4 class='modal-title'>Wache veröffentlichen <br>(E-Mail an alle Wachbeauftragen)?</h4>
					        <button type='button' class='close' data-dismiss='modal'>&times;</button>
					      </div>
			
					      <div class='modal-footer'>
					      	<input type='submit' value='Veröffentlichen' class='btn btn-primary' onClick='showLoader()'/>
					      	<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
					      </div>
			
					    </div>
					  </div>
					</div>";
           
		} else {
		    echo "&nbsp;<button type='button' class='btn btn-outline-primary float-right' disabled='disabled' >Wache ist öffentlich</button>";
		}
	    echo "</form>";
	}
	?>
</div>
<script>

function copyToClipBoard(){
	link = document.getElementById("link");
	el = document.createElement('textarea');
	el.value = link.childNodes[0].nodeValue;
	el.setAttribute('readonly', '');
	el.style.position = 'absolute';
	el.style.left = '-9999px';
	document.body.appendChild(el);
	el.select();
	document.execCommand('copy');
	document.body.removeChild(el);

	btn = document.getElementById("btnCpy");
	btn.className  = "btn btn-outline-success btn-sm";
	btn.firstChild.nodeValue = "Link kopiert";
}

</script>