<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_eventtypes.php';
require_once '../resources/library/db_event.php';
require_once '../resources/library/mail_controller.php';

$eventtypes = get_eventtypes ();

// Pass variables (as an array) to template
$variables = array (
		'title' => 'Wache anlegen',
		'secured' => true,
		'eventtypes' => $eventtypes,
);

if (isset ( $_POST ['title'] ) and isset ( $_POST ['type'] ) and isset ( $_POST ['staff1'] )) {

	$date = trim ( $_POST ['date'] );
	$start = trim ( $_POST ['start'] );
	$end = trim ( $_POST ['end'] );
	$type = trim ( $_POST ['type'] );
	$title = trim ( $_POST ['title'] );
	$comment = "";
	$informOther = false;

	$manager = $_SESSION ['userid'];

	if (isset ( $_POST ['comment'] )) {
		$comment = trim ( $_POST ['comment'] );
	}
	if(isset($_POST ['informOther'])){
	    $informOther = true;
	}
	
    $event_uuid = insert_event ( $date, $start, $end, $type, $title, $comment, !$informOther, $manager );
	$position = 1;
	while ( isset ( $_POST ["staff" . $position] ) ) {
		$staff = trim ( $_POST ["staff" . $position] );
		$position += 1;
		insert_staff ( $event_uuid, $staff );
	}
	mail_insert_event ( $event_uuid, $manager, $informOther);
	// if ok
	$variables ['successMessage'] = "Wache angelegt";
}


renderLayoutWithContentFile ( "eventCreate_template.php", $variables );
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
		input.placeholder="Funktionsbezeichnung eingeben";
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

