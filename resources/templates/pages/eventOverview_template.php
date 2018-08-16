<?php
if ($deleted) {
	showSuccess ( "Wache gelöscht" );
}

if (! count ( $data )) {
	showInfo ( "Es sind keine Wachen offen" );
} else {
?>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Datum</th>
				<th>Start</th>
				<th>Ende</th>
				<th>Typ</th>
				<th>Titel</th>
				<th>Details</th>
				<th>Löschen</th>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $data as $row ) {
		?>
				<tr>
				<td><?= $row->date; ?></td>
				<td><?= $row->start_time; ?></td>
				<td>
	<?php
		if ($row->end_time != 0) {
			echo $row->end_time;
		} else {
			echo " - ";
		}
		?></td>
				<td><?= get_eventtype($row->type)->type; ?></td>
				<td><?= $row->title; ?></td>
				<td>
					<form method="post"
						action="<?= "event_details.php?id=".$row->uuid ?>">
						<input type="submit" value="Details"
							class="btn btn-primary btn-sm" />
					</form>
				</td>
				<td>
					<form method="post" action="">
						<input type="hidden" name="delete" id="delete"
							value="<?= $row->uuid ?>" /> <input type="submit" value="Löschen"
							class="btn btn-outline-primary btn-sm" />
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