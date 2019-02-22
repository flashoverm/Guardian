
<form onsubmit="showLoader()" action="" method="post">
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Datum:</label> <input type="date" required="required" 
				placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ" class="form-control" 
				name="date" id="date" value='<?= $event->date; ?>' 
				required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Wachbeginn:</label> <input type="time" required="required" 
				placeholder="--:--" title="--:--" class="form-control" 
				value='<?= $event->start_time; ?>' 
				name="start" id="start" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Ende:</label> <input type="time" required="required" 
				placeholder="--:--" title="--:--" class="form-control" 
				value='<?= $event->end_time; ?>' 
				name="end" id="end" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
	</div>
	<div class="form-group">
		<label>Typ:</label> <select class="form-control" name="type" id="type" onchange="showHideTypeOtherCreate()">
				<?php foreach ( $eventtypes as $type ) :
				if($type->uuid == $event->type) {?>
					<option value="<?= $type->uuid; ?>" selected><?= $type->type; ?></option>
				<?php } else {?>
					<option value="<?= $type->uuid; ?>"><?= $type->type; ?></option>
				<?php }
				endforeach; ?>
			</select>
	</div>
	<div class="form-group" id="groupTypeOther">
		<label>Sonstiger Wachtyp:</label> <input type="text" required="required"
			class="form-control" name="typeOther" id="typeOther"
			<?php
			if($event->type_other != null){?>
			    value='<?= $event->type_other; ?>'";
			<?php } ?>
			placeholder="Wachtyp eingeben">
	</div>
	
	
	<div class="form-group">
		<label>Titel:</label> <input type="text"
			class="form-control" name="title" id="title"
			<?php
			if($event->title != null){?>
			    value='<?= $event->title; ?>'";
			<?php } ?>
			placeholder="Titel eingeben">
	</div>
		
	<div class="form-group">
		<label>Zuständiger Löschzug</label> 
		<select
			class="form-control" name="engine" required="required"
			data-toggle="tooltip" data-placement="top" title="Dieser Zug soll die Wache besetzen">
			<?php foreach ( $engines as $option ) : 
			if($option->uuid == $event->engine){
			    ?>
			   	<option selected="selected" value="<?=  $option->uuid;	?> "><?= $option->name; ?></option>
			    <?php 
			} else {
			    ?>
			   <option value="<?=  $option->uuid;	?> "><?= $option->name; ?></option>
			    <?php
			}
			?>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="form-group">
		<label>Anmerkungen:</label>
		<textarea class="form-control" name="comment" id="comment"
			placeholder="Anmerkungen"><?php
			if($event->comment != null){
			    echo $event->comment;
			} ?></textarea>
	</div>
	
	<div class="form-group" id="staffContainer">
		<label>Benötigtes Wachpersonal:</label>
		<div class="table-responsive">
		<table class="table table-bordered">
			<tbody>
			<tr>
				<th>Funktion</th>
				<th>Personal</th>
				<th></th>
			</tr>
				<?php
				foreach ( $staff as $entry ) {
					if ($entry->user != NULL) {
						$user = get_user ( $entry->user );
						$engine = get_engine ( $user->engine );
						$name = $user->firstname . " " . $user->lastname . " (" . $engine->name . ")";
					}
					?>
			<tr>
				<td><?= get_staffposition($entry->position)->position; ?></td>
				<td><?php if($entry->user != NULL){echo $name; }?></td>
				<td><?php
					if ($entry->user != NULL) {
						echo "<form method='post' action='" . $config["urls"]["html"] . "/events/" . $event->uuid . "'>
								<input type='hidden' name='staffid' id='staffid' value='" . $entry->uuid . "'/>
								<button type='button' class='btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#confirmUnscribe" . $entry->uuid ."'>Austragen</button>
								
								<div class='modal' id='confirmUnscribe" . $entry->uuid . "'>
								  <div class='modal-dialog'>
								    <div class='modal-content'>
								
								      <div class='modal-header'>
								        <h4 class='modal-title'>Personal wirklich austragen?</h4>
								        <button type='button' class='close' data-dismiss='modal'>&times;</button>
								      </div>
								
								      <div class='modal-footer'>
								      	<input type='submit' value='Austragen' class='btn btn-primary' onClick='showLoader()'/>
								      	<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
								      </div>
								
								    </div>
								  </div>
								</div> 
							</form>";
					
					}
					?></td>
			</tr>
				<?php
				}
				?>
		</tbody>
		</table>
			
		<div class="btn-group btn-group-sm" role="group" style="float: right">
  			<button type="button" class="btn btn-primary" onClick="eventRemoveLastStaff()">&minus;</button>
  			<span class="border-right"></span>
  			<button type="button" class="btn btn-primary " onClick="eventAddStaff()">+</button>
		</div>
		
		
		
		<?php 
		$staffId = 0;
		foreach ( $staff as $entry ) :
		  $staffId = $staffId +1;
		?>
		<select class="form-control" name="staff<?= $staffId; ?>" required="required" id="staff<?= $staffId; ?>">
			<option value="" disabled selected>Funktion auswählen</option>
			<?php foreach ( $staffpositions as $option ) : 
			if($option->uuid == $entry->position){
			    ?>
			    <option selected value="<?=  $option->uuid; ?>"><?= $option->position; ?></option>
			    <?php 
			} else {
			    ?>
			<option value="<?=  $option->uuid; ?>"><?= $option->position; ?></option>
			    <?php
			}
            endforeach; 
            ?>
		</select>
		<?php endforeach; ?>
			
	</div>
	<div class="form-check">
		<input type="checkbox" class="form-check-input" id="inform" name="inform"> 
		<label for="inform">Eingeschriebenes Personal über Änderungen informieren</label>
	</div>
	<input type="submit" value="Aktualisieren" class="btn btn-primary"><br> <input
		type="hidden" name="action" value="update">
</form>


<script type='text/javascript'>
	var createPositionCount = <?= $staffId; ?>;
	
	showHideTypeOtherCreate();

	
	function eventAddStaff(){
		createPositionCount += 1;
		
		var container = document.getElementById("staffContainer");

		var position1 = document.getElementById("staff1");
		var newPosition =  position1.cloneNode(true);
		newPosition.id = "staff" + createPositionCount;
		newPosition.name = "staff" + createPositionCount;
		
		container.appendChild(newPosition);
	}
	
	function eventRemoveLastStaff(){
		if(createPositionCount != 1){
			var lastStaffRow = document.getElementById("staff"+createPositionCount);
			lastStaffRow.parentNode.removeChild(lastStaffRow);
			createPositionCount -= 1;
		}
	}

	function showHideTypeOtherCreate(){
		var type = document.getElementById("type");
		var selectedType = type.options[type.selectedIndex].text;

	    var groupTypeOther = document.getElementById("groupTypeOther");
	    var typeOther = document.getElementById("typeOther");
		
		if(selectedType == "Sonstige Wache"){
			typeOther.setAttribute("required", "");
			groupTypeOther.style.display = "block";
		} else {
			typeOther.removeAttribute("required");
			groupTypeOther.style.display = "none";
		}
	}
</script>