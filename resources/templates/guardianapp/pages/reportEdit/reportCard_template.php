<?php 
function createUnitCard($number, $unit = null) {
?>

<div id="unit<?= $number ?>" class="card" <?php if(!isset($unit)) { echo 'style="display:none;"'; } ?>>
	<div class="card-header">
		<h5 class="mb-0">
			<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?= $number ?>">
			<?php if(isset($unit)){ 
			    
			    echo  $unit->unit; 
			} ?>
			</button>
		</h5>
	</div>
    <div class="collapse unittemplateBody" id="collapse<?= $number ?>" data-parent="#unitlist">
    	<div class="card-body">
    		<div class="row form-group">
    			<div class="col-sm">
    				<label>Datum:</label>
        			<input class="form-control bg-white" id="unit<?= $number ?>date" name="unit<?= $number ?>date" 
        				type="date" disabled required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}"
        				<?php if(isset($unit)){ echo "value=" . $unit->date; } ?>
        				>
        		</div>
        		<div class="col-sm">
        			<label>Wachbeginn:</label>
        			<input class="form-control  bg-white" id="unit<?= $number ?>start" name="unit<?= $number ?>start" 
        				type="time" disabled required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])"
        				<?php if(isset($unit)){ echo "value=" . $unit->beginn; } ?>
        				>
        		</div>
        		<div class="col-sm">
        			<label>Ende:</label>
        			<input class="form-control bg-white" id="unit<?= $number ?>end" name="unit<?= $number ?>end" 
        				type="time" disabled required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])"
        				<?php if(isset($unit)){ echo "value=" . $unit->end; } ?>
        				>
        		</div>
        	</div>
        	<input id="unit<?= $number ?>unit" name="unit<?= $number ?>unit" type="hidden" 
        	   <?php if(isset($unit)){ echo "value=" . $unit->unit; } ?>
        		>
        	<input id="unit<?= $number ?>km" name="unit<?= $number ?>km" type="hidden"
        	   <?php if(isset($unit)){ echo "value=" . $unit->km; } ?>
        		>
        	<label>Personal:</label>
        	<div class="personalContainer">
        		<?php 
        		createUnitStaff($number,0);
        		if(isset ($unit)){
        		    $j = 0;
            		foreach($unit->staffList as $staff){
            		    $j++;
            		    createUnitStaff($number, $j, $staff);
            		}
        		}
            	?>

            </div>
        	<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addUnitModal" onclick="initializeModalEdit(<?= $number ?>)">Bearbeiten</button>
    		<button type="button" class="btn btn-outline-primary btn-sm" onclick="removeUnit(<?= $number ?>)">Entfernen</button>
    	</div>
    </div>
</div>

<?php 
}

function createUnitStaff($unitNumber, $staffNumber, $staff = null) {
    global $staffpositions, $engines;
?>
<div class="row form-group unitpersonaltemplate" <?php if(!isset($staff)) { echo 'style="display:none;"'; } ?>>
	<div class="col-sm">
		<select class="form-control bg-white" id="unit<?= $unitNumber ?>function<?= $staffNumber ?>" 
			name="unit<?= $unitNumber ?>function<?= $staffNumber ?>" required="required" id="positionfunction" disabled>
			<option value="" disabled selected>Funktion auswählen</option>
			<?php foreach ( $staffpositions as $option ) : ?>
			<option value="<?=  $option->uuid; ?>"
				<?php if(isset($staff) && $staff->position == $option->uuid){ echo "selected"; } ?>>
				<?= $option->position; ?>
			</option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="col-sm">
		<input class="form-control bg-white" id="unit<?= $unitNumber ?>name<?= $staffNumber ?>" 
			name="unit<?= $unitNumber ?>name<?= $staffNumber ?>" type="text"" disabled 
			<?php if(isset($staff)){ echo "value=" . $staff->name; } ?>
        	>
	</div>
	<div class="col-sm">
		<select class="form-control bg-white" id="unit<?= $unitNumber ?>engine<?= $staffNumber ?>" 
			name="unit<?= $unitNumber ?>engine<?= $staffNumber ?>" required="required" id="positionengine" disabled>
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) : ?>
			<option value="<?=  $option->uuid; ?>"
				<?php if(isset($staff) && $staff->engine == $option->uuid){ echo "selected"; } ?>>
				<?= $option->name; ?>
			</option>
			<?php endforeach; ?>
		</select>
	</div>
</div>
<?php
}
?>
