<?php
if(!isset($events) ){
	//Disabled
} else if ( ! count ( $events )) {
	showInfo ( "Es sind keine öffentlichen Wachen vorhanden" );
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
					<form method="post"
						action="<?= "event_details.php?id=".$row->uuid ?>">
						<input type="submit" value="Details"
							class="btn btn-primary btn-sm" />
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