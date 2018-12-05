<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_event.php';
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';
require_once '../resources/library/mail_controller.php';

// Pass variables (as an array) to template
$variables = array (
    'title' => 'Wache/Position nicht vorhanden',
    'secured' => false,
    'showFormular' => false
);

if (isset ( $_GET ['staffid'] ) and isset ( $_GET ['id'] )) {

	$staffUUID = trim ( $_GET ['staffid'] );
	$engines = get_engines ();
	$eventUUID = trim ( $_GET ['id'] );
	
	$event = get_event($eventUUID);
	$staffposition = get_events_staffposition($staffUUID);
	
	if(isset($event) and isset($staffposition)) {
	    $variables ['showFormular'] = true;
	    
    	$variables ['title'] = "In " . get_eventtype($event->type)->type . " eintragen";
    	$variables ['engines'] = $engines;
    	$variables ['eventUUID'] = $eventUUID;
    	$variables ['staffUUID'] = $staffUUID;
    	$variables ['subtitle'] = date($config ["formats"] ["date"], strtotime($event->date)) 
    	. " - " . date($config ["formats"] ["time"], strtotime($event->start_time)) . " als " . $staffposition->position;
    
    	if (isset ( $_POST ['firstname'] ) and isset ( $_POST ['lastname'] ) && isset ( $_POST ['email'] ) && isset ( $_POST ['engine'] )) {
    
    		$firstname = trim ( $_POST ['firstname'] );
    		$lastname = trim ( $_POST ['lastname'] );
    		$email = trim ( $_POST ['email'] );
    		$engineUUID = trim ( $_POST ['engine'] );
    
    		$sendMail = true;
    		if(isset($_POST ['noMail'])){
    			$sendMail = false;
    		}
    		
    		$user_uuid = insert_user ( $firstname, $lastname, $email, $engineUUID );
    		if($user_uuid){
    			if(add_staff_user ( $staffUUID, $user_uuid )){
    				mail_subscribe_staff_user ( $eventUUID, $email, $engineUUID, $sendMail);
    				$variables ['successMessage'] = "Als Wachteilnehmer eingetragen - <a href=\"event_details.php?id=" . $eventUUID . "\" class=\"alert-link\">Zurück</a>";
    				$variables ['showFormular'] = false;
    				header ( "Location: event_details.php?id=".$eventUUID); // redirects
    			} else {
    				$variables ['alertMessage'] = "Eintragen fehlgeschlagen";
    			}
    		} else {
    			$variables ['alertMessage'] = "Eintragen fehlgeschlagen";
    		}
    	}
	}
} else {
    $variables ['alertMessage'] = "Fehlende Parameter";
}

renderLayoutWithContentFile ( "eventSubscribe_template.php", $variables );
?>