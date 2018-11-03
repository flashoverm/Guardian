<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_eventtypes.php';
require_once '../resources/library/db_event.php';
require_once '../resources/library/db_staffpositions.php';
require_once '../resources/library/mail_controller.php';

$eventtypes = get_eventtypes ();
$staffpositions = get_staffpositions();

// Pass variables (as an array) to template
$variables = array (
		'title' => 'Wache anlegen',
		'secured' => true,
		'eventtypes' => $eventtypes,
        'staffpositions' => $staffpositions
);

if (isset ( $_POST ['type'] ) and isset ( $_POST ['staff1'] )) {

	$date = trim ( $_POST ['date'] );
	$start = trim ( $_POST ['start'] );
	$end = trim ( $_POST ['end'] );
	$type = trim ( $_POST ['type'] );
	
	$typeOther = null;
	if(isset( $_POST ['typeOther'] ) && !empty( $_POST ['typeOther'] ) ){
		$typeOther = trim( $_POST ['typeOther'] );
	}
	
	$title = trim ( $_POST ['title'] );
	if(empty ($title)){
	    $title = null;
	}
	
	$comment = "";
	$informOther = false;

	$manager = $_SESSION ['guardian_userid'];

	if (isset ( $_POST ['comment'] )) {
		$comment = trim ( $_POST ['comment'] );
	}
	if(isset($_POST ['informOther'])){
	    $informOther = true;
	}
	
	$event_uuid = insert_event ( $date, $start, $end, $type, $typeOther, $title, $comment, !$informOther, $manager );
    if($event_uuid){
    	$position = 1;
    	while ( isset ( $_POST ["staff" . $position] ) ) {
    		$staffPosition = trim ( $_POST ["staff" . $position] );
    		$position += 1;
    		insert_staff ( $event_uuid, $staffPosition );
    	}
    	if(mail_insert_event ( $event_uuid, $manager, $informOther)){
    		$variables ['successMessage'] = "Wache angelegt";
    		
    		header ( "Location: event_details.php?id=" . $event_uuid ); // redirects
    	} else {
    		$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
    	}
    } else {
    	$variables ['alertMessage'] = "Wache konnte nicht angelegt werden";
    }
}


renderLayoutWithContentFile ( "eventCreate_template.php", $variables );
?>