
<form id="eventForm" action="" method="post">
	<?php
	$staffId = 0;
	if(isset($event) ){
		echo "<input type='hidden' name='eventid' id='eventid' value='" . $event->uuid . "'/>";
	}
	?>
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Datum:</label> <input type="date" required="required" 
				placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ" class="form-control" 
				name="date" id="date" 
				<?php
				if(isset($event) ){
					echo "value='" . $event->date . "'";
				}?>
				required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Wachbeginn:</label> <input type="time" required="required" 
				placeholder="--:--" title="--:--" class="form-control" 
				<?php
				if(isset($event) ){
					echo "value='" . substr($event->start_time, 0, strlen($event->start_time)-3) . "'";
				}?>
				name="start" id="start" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Ende (optional):</label> <input type="time"
				placeholder="--:--" title="--:--" class="form-control" 
				<?php
				if(isset($event) && $event->end_time != null){
					//echo "value='" . substr($event->end_time, 0, strlen($event->end_time)-3) . "'";
				}?>
				name="end" id="end" pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
	</div>
	<div class="form-group">
		<label>Typ:</label> <select class="form-control" name="type" id="type" onchange="showHideTypeOtherCreate()">
				<?php foreach ( $eventtypes as $type ) :
				if(isset($event) && $type->uuid == $event->type) {?>
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
			if(isset($event) && $event->type_other != null){
			    echo "value='" . $event->type_other . "'";
			}?>
			placeholder="Wachtyp eingeben">
	</div>
	
	<div class="form-group">
		<label>Titel (optional):</label> <input type="text"
			class="form-control" name="title" id="title"
			<?php
			if(isset($event) && $event->title != null){
				echo "value='" . $event->title . "'";
			}?>
			placeholder="Titel eingeben">
	</div>
		
	<div class="form-group">
		<label>Zuständiger Löschzug</label> 
		<select
			class="form-control" name="engine" required="required"
			data-toggle="tooltip" data-placement="top" title="Dieser Zug soll die Wache besetzen">
			<?php foreach ( $engines as $option ) : 
			if(isset($event) && $option->uuid == $event->engine){
			    ?>
			   	<option selected="selected" value="<?=  $option->uuid;	?> "><?= $option->name; ?></option>
			    <?php 
			}else if(!isset($event) && $option->uuid == $usersEngine){
				?>
			   	<option selected="selected" value="<?=  $option->uuid;	?> "><?= $option->name; ?></option>
			    <?php 
			}else{
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
			if(isset($event) && $event->comment != null){
				echo $event->comment;
			}?></textarea>
	</div>

	
	<div class="form-group">
		<label>Benötigtes Wachpersonal:</label>
		<div class="table-responsive">
			<table class="table table-bordered">
				<tbody id="staffContainer">
					<tr>
						<th>Funktion</th>
						<!-- if event is set, display column "Personal" -->
						<?php
						if(isset($event) ){
							echo "<th>Personal</th>";
						}?>
						<th class="py-0 text-center align-middle" style="width:  8%">
							<button type="button" class="btn btn-sm btn-primary" onClick="eventAddStaff()">+</button>
						</th>
					</tr>
					
					
					<tr id="staffEntryTemplate" style="display:none;">
						<td class="p-0">
								<select class="select-cornered" name="">
									<option value="" disabled selected>Funktion auswählen</option>
									<?php foreach ( $staffpositions as $option ) : 
									?>
										<option value="<?=  $option->uuid; ?>"><?= $option->position; ?></option>
									<?php
									endforeach; 
						            ?>
								</select>
						</td>
						<?php
						if(isset($event) ){
							echo "<td class='py-0 align-middle'></td>";
						}?>
						<td class="p-0 text-center align-middle" style="width:  8%">
							<button type="button" class="btn btn-sm btn-primary">X</button>
						</td>
					</tr>
					
					<?php
					$staffId = 0;
					if(isset($staff)){
						foreach ( $staff as $entry ) {
							$staffId = $staffId +1;
							if ($entry->user != NULL) {
								$user = get_user ( $entry->user );
								$engine = get_engine ( $user->engine );
								$name = $user->firstname . " " . $user->lastname . " (" . $engine->name . ")";
							}
							?>
						<tr id="staffEntry<?= $staffId; ?>">
							<td class="p-0">
									<select class="select-cornered" name="<?= $entry->uuid; ?>" required="required" id="<?= $entry->uuid; ?>">
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
							</td>
							<td class='py-0 align-middle'>
								<?php if($entry->user != NULL){ echo $name; }?>
							</td>
							<td class="p-0 text-center align-middle" style="width:  8%">								
								<?php
								if($entry->user != NULL){
                                ?>								 
									<button type="button" class="btn btn-sm btn-primary" data-toggle='modal' data-target='#confirmDeleteStaff<?= $entry->uuid ?>'>X</button>
									<div class='modal' id='confirmDeleteStaff<?= $entry->uuid ?>'>
    								  <div class='modal-dialog'>
    								    <div class='modal-content'>
    				    
    								      <div class='modal-header'>
    								        <h4 class='modal-title'>Mit Entfernen dieses Eintrags wird auch die Person ausgetragen!</h4>
    								        <button type='button' class='close' data-dismiss='modal'>&times;</button>
    								      </div>
    				    
    								      <div class='modal-footer'>
    								      	<input type='submit' value='Fortfahren' class='btn btn-primary' data-dismiss='modal' onClick='eventRemoveLastStaff(<?= $staffId; ?>)'/>
    								      	<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
    								      </div>
    				    
    								    </div>
    								  </div>
    								</div>
								<?php 
								} else {
								    echo '<button type="button" class="btn btn-sm btn-primary" onClick="eventRemoveLastStaff(' . $staffId . ')">X</button>';
								}?>
							</td>
						</tr>
						<?php
						}
					} else if($staffId == 0){
						$staffId = 1;
						?>
						<tr id="staffEntry1">
							<td class="p-0">
									<select class="select-cornered" name="staff1" required="required" id="staff1">
										<option value="" disabled selected>Funktion auswählen</option>
										<?php foreach ( $staffpositions as $option ) : 
										?>
											<option value="<?=  $option->uuid; ?>"><?= $option->position; ?></option>
										<?php
										endforeach; 
							            ?>
									</select>
							</td>
							<?php
							if(isset($event) ){
								echo "<td class='py-0 align-middle'></td>";
							}?>
							<td class="p-0 text-center align-middle">
								<button type="button" class="btn btn-sm btn-primary" onClick="eventRemoveLastStaff(1)">X</button>
							</td>
						</tr>
						<?php 
					}
					?>	
					
				</tbody>
			</table>
			<input type="hidden" id="positionCount" name="positionCount" value="<?= $staffId; ?>">
			<div id="staffAlert" class="alert alert-danger alert-dismissible">
  				<a class="close" onClick="setStaffAlert(false)" > &times; </a>Es muss mindestens eine Funktion eingetragen werden!
			</div>
		</div>	
	</div>

		<?php
		if(isset($event)){
			echo "	<div class='form-check'>
						<input type='checkbox' class='form-check-input' id='inform' name='inform'> 
						<label for='inform'>Personal über Änderungen informieren (Entferntes Personal wird immer informiert!)</label>
					</div>";
		} else {
			echo "	<div class='form-check'>
						<input type='checkbox' class='form-check-input' id='publish' name='publish'>
						<label for='publish'>Veröffentlichen (E-Mail an alle Wachbeauftragen)</label>
					</div>";
		}
		if(isset($event)){
			echo '<a class="btn btn-outline-primary" href=' . $config["urls"]["html"] . '/events/' . $event->uuid . ">Zurück</a>";
		}
		?>
				
	<input type="submit" class="btn btn-primary"
		<?php
		if(isset($event)){
			echo " value='Aktualisieren' ";
		    //echo " Aktualisieren";
		}else{
			echo " value='Anlegen' ";
			//echo " Anlegen";
		}?>
		>
</form>


<script type='text/javascript'>

	var absolutCount = <?= $staffId; ?>;
	var positionCount = <?= $staffId; ?>;
	
	showHideTypeOtherCreate();
	setStaffAlert(false);
	
	var form = document.getElementById('eventForm');
	if (form.attachEvent) {
	    form.attachEvent("submit", processForm);
	} else {
	    form.addEventListener("submit", processForm);
	}


	function setStaffAlert(visible) {
	  var x = document.getElementById("staffAlert");
	  /*
	  if (x.style.display === "none") {
	    x.style.display = "block";
	  } else {
	    x.style.display = "none";
	  }
	  */
	  
	  if(visible){
		  x.style.display = "block";
	  } else {
		  x.style.display = "none";
	  }
	  
	} 

    function processForm(e) {
        if(absolutCount < 1){
    	    if (e.preventDefault) e.preventDefault();
			
        	setStaffAlert(true)
            
        } else {
            setStaffAlert(false);
            showLoader();
        	document.forms["eventForm"].submit();
        }
        return false;
    }
    
	if(!isDateSupported()){
		var dateElement = document.getElementById("date");
		var date = new Date(dateElement.value);		
		var dateString = ('0' + date.getDate()).slice(-2) + '.'
        + ('0' + (date.getMonth()+1)).slice(-2) + '.'
        + date.getFullYear();

        dateElement.value = dateString;
	}
		
	function eventAddStaff(){
		positionCount += 1;

		var thisPositionCount = positionCount;
		
		var container = document.getElementById("staffContainer");

		var template = document.getElementById("staffEntryTemplate");
		
		var newPosition =  template.cloneNode(true);
		
		newPosition.id = "staffEntry" + positionCount;
		newPosition.style.display = null;
		
		var select = newPosition.getElementsByTagName("select");
		select[0].id = "staff" + positionCount;
		select[0].name = "staff" + positionCount;
		select[0].required = true;
		
		var removeButton = newPosition.getElementsByTagName("button");
		removeButton[0].onclick = function(){
				eventRemoveLastStaff(thisPositionCount);
			};

		var positionCountInput = document.getElementById("positionCount");
		positionCountInput.value = positionCount;

		absolutCount = absolutCount +1;

		setStaffAlert(false);
		
		container.appendChild(newPosition);
	}
	
	function eventRemoveLastStaff(id){			
		var staffElement = document.getElementById("staffEntry"+id);
		staffElement.parentNode.removeChild(staffElement);
		absolutCount = absolutCount -1;
		
		if(absolutCount < 1){
			setStaffAlert(true)
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