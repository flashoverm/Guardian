
<form action="" method="post">
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Datum:</label> <input type="date" required="required"
					class="form-control" name="date" id="date">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Beginn:</label> <input type="time" required="required"
					class="form-control" name="start" id="start">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Ende:</label> <input type="time" required="required"
					class="form-control" name="end" id="end">
			</div>
		</div>
	</div>
	<div class="form-group">
		<label>Typ:</label> <select class="form-control" name="type">
				<?php foreach ( $eventtypes as $type ) : ?>
					<option value="<?= $type->uuid; ?>"><?= $type->type; ?></option>
				<?php endforeach; ?>
			</select>
	</div>
	<div class="form-group">
		<label>Titel:</label> <input type="text" required="required"
			class="form-control" name="title" id="title"
			placeholder="Titel eingeben">
	</div>
	<div class="form-group">
		<label>Anmerkungen:</label>
		<textarea class="form-control" name="comment" id="comment"
			placeholder="Anmerkungen"></textarea>
	</div>
	<div class="form-group" id="staffContainer">
		<label>Benötigtes Wachpersonal:</label>
		<div class="btn-group btn-group-sm" role="group" style="float: right">
  			<button type="button" class="btn btn-primary" onClick="eventRemoveLastStaff()">&minus;</button>
  			<span class="border-right"></span>
  			<button type="button" class="btn btn-primary " onClick="eventAddStaff()">+</button>
		</div>
			
		<input class="form-control" type="text" required="required"
			name="staff1" id="staff1" placeholder="Funktionsbezeichnung eingeben">
	</div>
	<div class="form-check">
		<input type="checkbox" class="form-check-input" id="informOther" name="informOther"> 
		<label for="informOther">Veröffentlichen (Andere Züge informieren)</label>
	</div>
	<input type="submit" value="Anlegen" class="btn btn-primary"><br> <input
		type="hidden" name="action" value="save">
</form>


<script type='text/javascript'>
	var createPositionCount = 1;
	
	function eventAddStaff(){
		createPositionCount += 1;
		var container = document.getElementById("staffContainer");
		var input = document.createElement("input");
		input.className ="form-control";
		input.type = "text";
		input.name = "staff" + createPositionCount;
		input.id = "staff" + createPositionCount;
		input.required = "required";
		input.placeholder="Funktionsbezeichnung eingeben";
		container.appendChild(input);
	}
	
	function eventRemoveLastStaff(){
		if(createPositionCount != 1){
			var lastStaffRow = document.getElementById("staff"+createPositionCount);
			lastStaffRow.parentNode.removeChild(lastStaffRow);
			createPositionCount -= 1;
		}
	}
</script>