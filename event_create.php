<?php
require_once 'page_head.php';
require_once 'inc/secured_page.php';
require_once 'inc/db_eventtypes.php';
require_once 'inc/db_event.php';
require_once 'inc/mail_controller.php';
?>
<div class="jumbotron text-center">
	<h1>Wache anlegen</h1>
</div>
<div class="container">
<?php
if (isset($_POST['title']) and isset($_POST['type']) and isset($_POST['staff1']) ) {
	
	$date = trim($_POST['date']);
	$start = trim($_POST['start']);
	$end = trim($_POST['end']);
	$type = trim($_POST['type']);
	$title = trim($_POST['title']);
	$comment;
	$manager = $_SESSION['userid'];

	if(isset($_POST['comment'])){
		$comment = trim($_POST['comment']);
	} else {
		$comment = "";
	}
	
	$error = false;
	if(strlen($date) == 0) {
        showAlert('Bitte Datum auswählen');
        $error = true;
    }
	if(strlen($start) == 0) {
        showAlert('Bitte Start-Zeit auswählen');
        $error = true;
    }
	if(strlen($type) == 0) {
        showAlert('Bitte Typ eingeben');
        $error = true;
    }
	if(strlen($title) == 0) {
        showAlert('Bitte Titel eingeben');
        $error = true;
    }
	$position = 1;
	while(isset($_POST["staff".$position])){
		$staff = trim($_POST["staff".$position]);
		if(strlen($staff) == 0) {
			showAlert('Bitte Positionsbezeichnung '.$position.' eingeben');
			$error = true;
		}
		$position += 1;
	}
	if(!$error){
		$event_uuid = insert_event($date, $start, $end, $type, $title, $comment, $manager);
		$position = 1;
		while(isset($_POST["staff".$position])){
			$staff = trim($_POST["staff".$position]);
			$position += 1;
			insert_staff($event_uuid, $staff);
		}
		mail_insert_event($event_uuid, $manager);
		//if ok
		showSuccess("Wache angelegt");
	}
}
$eventtypes = get_eventtypes();

?>
<script type='text/javascript'>
	var currentPosition = 1;
	function addStaff(){
		currentPosition += 1;
		var container = document.getElementById("staffContainer");
		var input = document.createElement("input");
		input.className ="form-control";
		input.type = "text";
		input.name = "staff" + currentPosition;
		input.id = "staff" + currentPosition;
		input.required = "required";
		input.placeholder="Positionsbezeichnung eingeben";
		container.appendChild(input);
	}
	function removeLast(){
		if(currentPosition != 1){
			var lastStaffRow = document.getElementById("staff"+currentPosition);
			lastStaffRow.parentNode.removeChild(lastStaffRow);
			currentPosition -= 1;
		}
	}
</script>

	<form action="" method="post">
		<div class="form-group">
			<label>Datum:</label>
			<input type="date" required="required" class="form-control" name="date"id="date">
		</div>
		<div class="form-group">
			<label>Start:</label>
			<input type="time" required="required" class="form-control" name="start"id="start">
		</div>
		<div class="form-group">
			<label>Ende (Optional):</label>
			<input type="time" class="form-control" name="end"id="end">
		</div>
		<div class="form-group">
			<label >Typ:</label>
			<select class="form-control" name="type">
				<?php foreach ( $eventtypes as $type ) : ?>
					<option value="<?php echo $type->uuid; ?>"><?php echo $type->type; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label>Titel:</label>
			<input type="text" required="required" class="form-control" name="title"id="title" placeholder="Titel eingeben">
		</div>	
		<div class="form-group">
			<label >Anmerkungen:</label>
			<textarea class="form-control" name="comment" id="comment" placeholder="Anmerkungen"></textarea>
		</div>				
		<div class="form-group" id="staffContainer">
			<label >Positionen:</label>
			<button type="button" style="float:right" class="btn btn-primary btn-sm" onClick="removeLast()">&minus;</button>
			<a style="float:right">&nbsp;</a>
			<button type="button" style="float:right" class="btn btn-primary btn-sm" onClick="addStaff()">+</button>
			<input class="form-control" type="text" required="required" name="staff1" id="staff1" placeholder="Positionsbezeichnung eingeben">
		</div>			
		<input type="submit" value="Anlegen" class="btn btn-primary"><br>
		<input type="hidden" name="action" value="save">
	</form>
</div>
<footer>
	<div class="container">
		<a href='event_overview.php' class="btn btn-outline-primary">Zurück</a>
	</div>
<?php require_once 'inc/page_end.php'; ?>