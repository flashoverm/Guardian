<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_user.php';
require_once '../resources/library/db_eventtypes.php';
require_once '../resources/library/db_event.php';
require_once '../resources/library/mail_controller.php';


// Pass variables (as an array) to template
$variables = array (
    'title' => "Übersicht Wachen",
    'secured' => true,
);

if (isset ( $_POST ['delete'] )) {
	$delete_event_uuid = trim ( $_POST ['delete'] );
	mail_delete_event ( $delete_event_uuid );
	if(delete_event ( $delete_event_uuid )){
		$variables ['successMessage'] = "Wache gelöscht";
	} else {
		$variables ['alertMessage'] = "Wache konnte nicht gelöscht werden";
	}
}

$events = get_events ($_SESSION ['guardian_userid']);
$pastEvents = get_past_events($_SESSION ['guardian_userid']);
$variables ['events'] = $events;
$variables ['pastEvents'] = $pastEvents;


renderLayoutWithContentFile ( "eventOverview_template.php", $variables );
?>