<?php
require_once realpath(dirname(__FILE__) . "/../resources/config.php");
require_once LIBRARY_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/mail_controller.php";

if (! isset($_GET['id'])) {

    // Pass variables (as an array) to template
    $variables = array(
        'title' => 'Wache kann nicht angezeigt werden',
        'secured' => false,
        'showFormular' => false,
        'alertMessage' => "Wache kann nicht angezeigt werden"
    );
} else {
    $uuid = trim($_GET['id']);
    $event = get_event($uuid);
    
    if($event){
    	
        $isCreator = false;
        $otherEngine = null;
    	if (isset($_SESSION['guardian_userid'])) {
    		$isCreator = (strcmp($event->creator, $_SESSION['guardian_userid']) == 0);
    		
    		if(strcmp(get_user($_SESSION['guardian_userid'])->engine, $event->engine) != 0){
    		    $otherEngine = get_engine($event->engine);
    		 
    		}
    	}
    	
    	// Pass variables (as an array) to template
    	$variables = array(
    			'title' => get_eventtype($event->type)->type,
    			'secured' => false,
    			'showFormular' => true,
    	        'isCreator' => $isCreator,
    	        'otherEngine' => $otherEngine
    	);
    	
    	if($event->type_other != null){
    		$variables['subtitle'] = $event->type_other;
    	}
    	
    	if (isset($_POST['staffid'])) {
    		$staff_uuid = trim($_POST['staffid']);
    		mail_remove_staff_user($staff_uuid, $uuid);
    		if(remove_staff_user($staff_uuid)){
    			$variables['successMessage'] = "Personal-Eintrag entfernt";
    		} else {
    			$variables['alertMessage'] = "Eintrag konnte nicht entfernt werden";
    		}	
    	}
    	
    	if (isset($_POST['publish']) && $event->engine != NULL) {
    		if(publish_event($uuid) ){
    		    mail_publish_event($uuid, $_SESSION['guardian_userid']);
    			$variables['successMessage'] = "Wache veröffentlich - Wachbeauftragte informiert";
    			$event = get_event($uuid);
    		} else {
    			$variables['alertMessage'] = "Wache konnte nicht veröffentlicht werden";
    		}
    	}
    	
    	$staff = get_staff($uuid);
    	$variables['event'] = $event;
    	$variables['staff'] = $staff;
    } else {
    	// Pass variables (as an array) to template
    	$variables = array(
    			'title' => 'Wache nicht gefunden',
    			'secured' => false,
    			'showFormular' => false,
    			'alertMessage' => "Wache nicht gefunden"
    	);
    }
}
renderLayoutWithContentFile("eventDetails_template.php", $variables);
?>