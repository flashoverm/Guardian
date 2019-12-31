<?php require_once 'reportTable.php';?>

<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th>Freigabe durch Wachbeauftragten</th>
				<td>
					<?php if(!$report->managerApproved){ 
						echo "Bericht wurde nicht vom zuständigen Wachbeauftragten überprüft";
					} else {
						echo "Bericht wurde nicht überprüft";
					} ?>
				</td>
			</tr>
			<tr>
				<th>EMS-Eintrag</th>
				<td>
					<?php if(!$report->emsEntry){ 
						echo "Bericht nicht in EMS angelegt";
					} else {
						echo "Bericht in EMS angelegt";
					} ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<form action='' method='post'>
	<a href='<?=$config["urls"]["guardianapp_home"] ?>/reports' class='btn btn-outline-primary'>Zurück</a>
	<div class="dropdown float-right">
	  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Berichts-Optionen</button>
	  <div class="dropdown-menu">
	    <a class="dropdown-item" target="_blank" href="<?= $config["urls"]["guardianapp_home"] . "/reports/file/". $report->uuid; ?>">PDF anzeigen</a>
	    <a class="dropdown-item" target="_blank" href="<?= $config["urls"]["guardianapp_home"] . "/reports/file/". $report->uuid . "&force=true"; ?>">PDF neu erzeugen</a>
    	<div class="dropdown-divider"></div>
	
	<?php if(!$report->emsEntry){ ?>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#confirmEms<?= $report->uuid ?>'>Bericht in EMS angelegt</a>
	<?php } else { ?>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#removeEms<?= $report->uuid ?>'>Bericht in EMS entfernt</a>
	<?php } ?>
		<div class="dropdown-divider"></div>
	<?php if(!$report->managerApproved){ ?>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#managerApprove<?= $report->uuid ?>'>Bericht freigeben</a>
	<?php } else { ?>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#managerApproveRemove<?= $report->uuid ?>'>Freigabe entfernen</a>
	<?php } ?>
		<div class="dropdown-divider"></div>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#confirmDelete<?= $report->uuid; ?>'>Löschen</a>
		</div>
	</div>
		
	<div class='modal' id='managerApproveRemove<?= $report->uuid ?>'>
	  <div class='modal-dialog'>
	    <div class='modal-content'>

	      <div class='modal-header'>
	        <h4 class='modal-title'>Freigabe für diesen Bericht entfernen?</h4>
	        <button type='button' class='close' data-dismiss='modal'>&times;</button>
	      </div>

	      <div class='modal-footer'>
	      	<input type='submit' name='managerApproveRemove' value='Ja' class='btn btn-primary' onClick='showLoader()'/>
	      	<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
	      </div>

	    </div>
	  </div>
	</div>	
	
	<div class='modal' id='managerApprove<?= $report->uuid ?>'>
	  <div class='modal-dialog'>
	    <div class='modal-content'>
	
	      <div class='modal-header'>
	        <h4 class='modal-title'>Bericht für Abrechnung freigeben?</h4>
	        <button type='button' class='close' data-dismiss='modal'>&times;</button>
	      </div>
	
	      <div class='modal-footer'>
	      	<input type='submit' name='managerApprove' value='Ja' class='btn btn-primary' onClick='showLoader()'/>
	      	<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
	      </div>
	
	    </div>
	  </div>
	</div>
	
	<div class='modal' id='confirmEms<?= $report->uuid ?>'>
	  <div class='modal-dialog'>
	    <div class='modal-content'>

	      <div class='modal-header'>
	        <h4 class='modal-title'>Wurde die Wache in EMS angelegt?</h4>
	        <button type='button' class='close' data-dismiss='modal'>&times;</button>
	      </div>

	      <div class='modal-footer'>
	      	<input type='submit' name='emsEntry' value='Ja' class='btn btn-primary' onClick='showLoader()'/>
	      	<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
	      </div>

	    </div>
	  </div>
	</div>
	
	<div class='modal' id='removeEms<?= $report->uuid ?>'>
	  <div class='modal-dialog'>
	    <div class='modal-content'>

	      <div class='modal-header'>
	        <h4 class='modal-title'>Wurde der Eintrag dieser Wache aus EMS entfernt/nicht angelegt?</h4>
	        <button type='button' class='close' data-dismiss='modal'>&times;</button>
	      </div>

	      <div class='modal-footer'>
	      	<input type='submit' name='emsEntryRemoved' value='Ja' class='btn btn-primary' onClick='showLoader()'/>
	      	<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
	      </div>

	    </div>
	  </div>
	</div>
	<div class="modal" id="confirmDelete<?= $report->uuid; ?>">
	  <div class="modal-dialog">
	    <div class="modal-content">
	
	      <div class="modal-header">
	        <h4 class="modal-title">Bericht wirklich löschen?</h4>
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	      </div>
	
	      <div class="modal-footer">
	      	<input type="submit" name="delete" value="Löschen" class="btn btn-primary" onClick="showLoader()"/>
	      	<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Abbrechen</button>
	      </div>
	
	    </div>
	  </div>
	</div> 
						
</form>	

