<?php require_once 'reportTable.php';?>

<form action='<?=$config["urls"]["guardianapp_home"]?>/reports/<?=$report->uuid?>' method='post'>
	<a href='<?=$config["urls"]["guardianapp_home"] ?>/reports' class='btn btn-outline-primary'>Zurück</a>
	<div class="float-right">
		<a class="btn btn-primary" target="_blank" href="<?= $config["urls"]["guardianapp_home"] . "/reports/file/". $report->uuid . "&force=true"; ?>">PDF neu erzeugen</a>
    	<a class="btn btn-primary" target="_blank" href="<?= $config["urls"]["guardianapp_home"] . "/reports/file/". $report->uuid; ?>">PDF anzeigen</a>
		<?php
		if(!$report->emsEntry){
			echo "<button type='button' class='btn btn-outline-primary' disabled='disabled' >Bericht nicht in EMS</button>";
		} else {
			?>
			<input type='hidden' name='emsEntryRemoved' id='emsEntryRemoved' value='<?= $report->uuid ?>'/>
			<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#removeEms<?= $report->uuid ?>'>EMS-Eintrag entfernen</button>
		
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
		</div>   
		
		<?php 
	}
	?>
	
