<?php

if (! count ( $reports )) {
    showInfo ( "Es sind keine Wachberichte angelegt" );
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
				<th class="text-center">Zuständig</th>
				<th class="text-center">Vorkomnisse</th>
				<th class="text-center">Bericht</th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach ( $reports as $row ) {
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
				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
				<td class="text-center">
					<?php
					if($row->noIncidents){
					    echo " keine ";
					} else {
					    echo " siehe Bericht ";
					}
					?>
				</td>
				<td class="text-center">
					<form method="post"
						action="<?= "report_details.php?id=".$row->uuid ?>">
						<input type="submit" value="Bericht"
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