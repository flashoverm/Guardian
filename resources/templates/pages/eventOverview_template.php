<?php
if (! count ( $events )) {
	showInfo ( "Es sind keine Wachen offen" );
} else {
?>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Datum</th>
				<th>Wachbeginn</th>
				<th>Ende</th>
				<th>Typ</th>
				<th>Titel</th>
				<th>Belegung</th>
				<th>Öffentlich</th>
				<th>Details</th>
				<th>Löschen</th>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $events as $row ) {
		?>
				<tr>
				<td><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td align="center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
				<td>
	<?php
		if ($row->end_time != 0) {
		    echo date($config ["formats"] ["time"], strtotime($row->end_time));
		} else {
			echo " - ";
		}
		?></td>
				<td><?= get_eventtype($row->type)->type; ?></td>
				<td><?= $row->title; ?></td>
				<td align="center">
					<?php 
					if(is_event_full($row->uuid)){
					    echo '<font color="green">' . get_occupancy($row->uuid) . '</font>';
					} else {
					    echo '<font color="red">' . get_occupancy($row->uuid) . '</font>';
					}
				    ?>
				</td>
				<td align="center">
					<?php
					if($row->engine == NULL){
					    echo " X ";
					} else {
					    echo " - ";
					}
					?>
				</td>
				<td>
					<form method="post"
						action="<?= "event_details.php?id=".$row->uuid ?>">
						<input type="submit" value="Details"
							class="btn btn-primary btn-sm" />
					</form>
				</td>
				<td>
					<form method="post" action="">
						<input type="hidden" name="delete" id="delete" value="<?= $row->uuid ?>" />
						<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDelete">Löschen</button>
						
						<div class="modal" id="confirmDelete">
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