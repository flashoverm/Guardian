
<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th colspan="3">
					<?= get_eventtype($report->type)->type ?>
					<?php
					if(get_eventtype($report->type)->type == "Sonstige Wache"){
						echo ": " . $report->type_other;
					}
					?>
				</th>
			</tr>
			<tr>
				<th colspan="1">Titel</th>
				<td colspan="2"><?= $report->title ?></td>
			</tr>
			<tr>
				<th colspan="1">Datum</td>
				<th colspan="1">Wachbeginn</td>
				<th colspan="1">Ende</td>
			</tr>
			<tr>
				<td colspan="1"><?= date($config ["formats"] ["date"], strtotime($report->date)); ?></td>
				<td colspan="1"><?= date($config ["formats"] ["time"], strtotime($report->start_time)); ?></td>
				<td colspan="1"><?= date($config ["formats"] ["time"], strtotime($report->end_time)); ?></td>
			</tr>
			<?php if($report->ilsEntry){
				echo '<tr><td colspan="3">Wache durch ILS angelegt</td></tr>';
			}
			?>
			<tr>
				<th colspan="1">Vorkommnisse</td>
				<?php if($report->noIncidents){
					echo '<td colspan="2">Keine</td>';
				} else {
					echo '<td colspan="2">Siehe Bericht</td>';
				}
			?>
			</tr>
			<tr>
				<th colspan="3">Bericht</td>
			</tr>
			<tr>
				<td colspan="3"><?= $report->report; ?></td>
			</tr>
		</tbody>
	</table>
</div>
	<?php 
	foreach ( $units as $entry ) {
	?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tr>
			<?php 
			if($entry->unit != null && ! $entry->unit == "Stationäre Wache"){
			?>	
				<th colspan="2"><?= $entry->unit ?></th>
				<td><?= $entry->km ?> km</th>
			</tr>
			<?php 
			} else {
			?>
				<th colspan="3"><?= $entry->unit ?></th>
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
	</div>
	<?php } ?>
<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th>Zuständiger Löschzug</th>
				<td><?= get_engine($report->engine)->name ?></td>
			</tr>
			<tr>
				<th>Ersteller</th>
				<td><?= $report->creator ?></td>
			</tr>
		</tbody>
	</table>
</div>
<form action='<?=$config["urls"]["guardianapp_home"]?>/reports/<?=$report->uuid?>' method='post'>
	<a href='<?=$config["urls"]["guardianapp_home"] ?>/reports' class='btn btn-outline-primary'>Zurück</a>
	<?php
	if(!$report->emsEntry){
		echo "<button type='button' class='btn btn-outline-primary float-right' disabled='disabled' >Bericht nicht in EMS</button>";
	} else {
		?>
		<input type='hidden' name='emsEntryRemoved' id='emsEntryRemoved' value='<?= $report->uuid ?>'/>
		<button type='button' class='btn btn-primary float-right' data-toggle='modal' data-target='#removeEms<?= $report->uuid ?>'>EMS-Eintrag entfernen</button>
	
		<div class='modal' id='removeEms<?= $report->uuid ?>'>
		  <div class='modal-dialog'>
		    <div class='modal-content'>

		      <div class='modal-header'>
		        <h4 class='modal-title'>EMS-Eintrag entfernt?</h4>
		        <button type='button' class='close' data-dismiss='modal'>&times;</button>
		      </div>

		      <div class='modal-footer'>
		      	<input type='submit' value='Ja' class='btn btn-primary' onClick='showLoader()'/>
		      	<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
		      </div>

		    </div>
		  </div>
		</div>
		<?php 
	}
	?>
	
