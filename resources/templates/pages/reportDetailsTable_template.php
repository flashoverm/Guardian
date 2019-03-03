
<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th colspan="3"><?= get_eventtype($report->type)->type ?></th>
			</tr>
			<tr>
				<th>Titel</th>
				<td colspan="2"><?= $report->title ?></td>
			</tr>
			<tr>
				<th>Datum</td>
				<th>Wachbeginn</td>
				<th>Ende</td>
			</tr>
			<tr>
				<td><?= date($config ["formats"] ["date"], strtotime($report->date)); ?></td>
				<td><?= date($config ["formats"] ["time"], strtotime($report->start_time)); ?></td>
				<td><?= date($config ["formats"] ["time"], strtotime($report->end_time)); ?></td>
			</tr>
			<tr>
				<th>Zuständiger Löschzug</th>
				<td colspan="2"><?= get_engine($report->engine)->name ?></td>
			</tr>
			<tr>
				<th>Ersteller</th>
				<td colspan="2"><?= $report->creator ?></td>
			</tr>
			<tr>
				<td colspan="3"><?= $report->report; ?></td>
			</tr>
		</tbody>
	</table>
	
	<?php 
	foreach ( $units as $entry ) {
	?>
		<div class="table-responsive">
			<table class="table table-bordered">
				<?php 
				if($entry->unit != null){
				?>	
				<tr>
					<th colspan="2"><?= $entry->unit ?></th>
					<td><?= $entry->km ?> km</th>
				</tr>
				<?php 
				}?>
				<tr>
					<th>Datum (Einheit)</td>
					<th>Wachbeginn (Einheit)</td>
					<th>Ende (Einheit)</td>
				</tr>
				<tr>
					<td><?= date($config ["formats"] ["date"], strtotime($entry->date)); ?></td>
					<td><?= date($config ["formats"] ["time"], strtotime($entry->beginn)); ?></td>
					<td><?= date($config ["formats"] ["time"], strtotime($entry->end)); ?></td>
				</tr>
				<tr>
					<th>Funktion</th>
					<th>Name</th>
					<th>Löschzug</th>
				</tr>
				<?php 
				foreach ( $entry->staffList as $staff ) {
				?>
				<tr>
					<td><?= get_staffposition($staff->position)->position; ?></td>
					<td><?= $staff->name; ?></td>
					<td><?= get_engine($staff->engine)->name; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php } ?>
<p><a href='<?=$config["urls"]["html"] ?>/reports' class='btn btn-outline-primary'>Zurück</a></p>
	
