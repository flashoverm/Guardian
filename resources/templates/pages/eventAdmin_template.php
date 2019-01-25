<?php
if (!$isAdmin) {
    showAlert ( "Kein Administrator angemeldet - <a href=\"event_overview.php\" class=\"alert-link\">Zurück</a>" );
} else if (!isset($events) || ! count ( $events ) ) {
    showInfo ( "Es sind keine Wachen offen" );
} else {
    ?>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center">Datum</th>
				<th class="text-center">Wachbeginn</th>
				<th class="text-center">Ende</th>
				<th class="text-center">Typ</th>
				<th class="text-center">Titel</th>
				<th class="text-center">Belegung</th>
				<th class="text-center">Öffentlich</th>
				<th class="text-center">Zugewiesen</th>
				<th class="text-center">Details</th>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $events as $row ) {
		?>
				<tr>
				<td class="text-center"><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
				<td class="text-center">
	<?php
		if ($row->end_time != 0) {
		    echo date($config ["formats"] ["time"], strtotime($row->end_time));
		} else {
			echo " - ";
		}
		?></td>
				<td class="text-center"><?= get_eventtype($row->type)->type; ?></td>
				<td class="text-center"><?= $row->title; ?></td>
				<td class="text-center">
					<?php 
					if(is_event_full($row->uuid)){
					    echo '<font color="green">' . get_occupancy($row->uuid) . '</font>';
					} else {
					    echo '<font color="red">' . get_occupancy($row->uuid) . '</font>';
					}
				    ?>
				</td>
				<td class="text-center">
					<?php
					if($row->published){
					    echo " X ";
					} else {
					    echo " - ";
					}
					?>
				</td>
				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= "event_details.php?id=".$row->uuid ?>">Details</a>
				</td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
</div>
</p>
<?php 
}
if ( isset($pastEvents) && count ( $pastEvents )) {
?>
<button class="btn btn-outline-primary my-2" type="button" data-toggle="collapse" data-target="#pastevents">
    Vergangene Wachen
</button>

<div class="table-responsive collapse" id="pastevents">
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center">Datum</th>
				<th class="text-center">Wachbeginn</th>
				<th class="text-center">Ende</th>
				<th class="text-center">Typ</th>
				<th class="text-center">Titel</th>
				<th class="text-center">Öffentlich</th>
				<th class="text-center">Zugewiesen</th>
				<th class="text-center">Details</th>
				<th class="text-center">Löschen</th>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $pastEvents as $row ) {
		?>
				<tr>
				<td class="text-center"><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
				<td class="text-center">
	<?php
		if ($row->end_time != 0) {
		    echo date($config ["formats"] ["time"], strtotime($row->end_time));
		} else {
			echo " - ";
		}
		?></td>
				<td class="text-center"><?= get_eventtype($row->type)->type; ?></td>
				<td class="text-center"><?= $row->title; ?></td>
				<td class="text-center">
					<?php
					if($row->published){
					    echo " X ";
					} else {
					    echo " - ";
					}
					?>
				</td>
				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= "event_details.php?id=".$row->uuid ?>">Details</a>
				</td>
				<td class="text-center">
					<form method="post" action="">
						<input type="hidden" name="delete" id="delete" value="<?= $row->uuid ?>" />
						<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDelete<?= $row->uuid; ?>">Löschen</button>
						
						<div class="modal" id="confirmDelete<?= $row->uuid; ?>">
						  <div class="modal-dialog">
						    <div class="modal-content">
						
						      <div class="modal-header">
						        <h4 class="modal-title">Wache wirklich löschen?</h4>
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						      </div>
						
						      <div class="modal-footer">
						      	<input type="submit" value="Löschen" class="btn btn-primary" onClick="showLoader()"/>
						      	<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Abbrechen</button>
						      </div>
						
						    </div>
						  </div>
						</div> 
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
?>

</div>