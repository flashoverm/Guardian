<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_staffpositions.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";

$eventtypes = get_eventtypes ();
$staffpositions = get_staffpositions();
$engines = get_engines();

// Pass variables (as an array) to template
$variables = array (
		'title' => 'Wache anlegen',
		'secured' => true,
		'eventtypes' => $eventtypes,
        'staffpositions' => $staffpositions,
        'engines' => $engines,
);


if(isset($_SESSION ['guardian_userid'])){
    $user = $_SESSION ['guardian_userid'];
    $usersEngine = get_engine_of_user($user);
    
    $variables ['usersEngine'] = $usersEngine;
}

if (isset ( $_POST ['type'] ) and isset ( $_POST ['staff1'] )) {

	$date = trim ( $_POST ['date'] );
	$start = trim ( $_POST ['start'] );
	$end = trim ( $_POST ['end'] );
	$type = trim ( $_POST ['type'] );
	$engine = $_POST ['engine'];
	
	if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1]).(0[1-9]|1[0-2]).[0-9]{4}$/", $date)) {
	    //European date format -> change to yyyy-mm-dd
	    $date = date_create_from_format('d.m.Y', $date)->format('Y-m-d');
	}
	
	$typeOther = null;
	if(isset( $_POST ['typeOther'] ) && !empty( $_POST ['typeOther'] ) ){
		$typeOther = trim( $_POST ['typeOther'] );
	}
	
	$title = trim ( $_POST ['title'] );
	if(empty ($title)){
	    $title = null;
	}
	
	$comment = "";
	$publish = false;

	$creator = $_SESSION ['guardian_userid'];
	
	if (isset ( $_POST ['comment'] )) {
		$comment = trim ( $_POST ['comment'] );
	}
	if(isset($_POST ['publish'])){
	    $publish = true;
	}
	
	$event_uuid = insert_event ( $date, $start, $end, $type, $typeOther, $title, $comment, $engine, $creator, $publish);
    if($event_uuid){
    	$position = 1;
    	while ( isset ( $_POST ["staff" . $position] ) ) {
    		$staffPosition = trim ( $_POST ["staff" . $position] );
    		$position += 1;
    		insert_staff ( $event_uuid, $staffPosition );
    	}
    	if(mail_insert_event ( $event_uuid, $creator, $publish)){
    		$variables ['successMessage'] = "Wache angelegt";
    		    		
    		header ( "Location: " . $config["urls"]["html"] . "/event_details.php?id=" . $event_uuid ); // redirects
    	} else {
    		$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
    	}
    } else {
    	$variables ['alertMessage'] = "Wache konnte nicht angelegt werden";
    }

}


renderLayoutWithContentFile ( "eventCreate_template.php", $variables );
?>