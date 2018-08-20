<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_user.php';
require_once '../resources/library/db_eventtypes.php';
require_once '../resources/library/db_event.php';
require_once '../resources/library/mail_controller.php';

if (isset ( $_POST ['delete'] )) {
	$delete_event_uuid = trim ( $_POST ['delete'] );
	mail_delete_event ( $delete_event_uuid );
	delete_event ( $delete_event_uuid );
	// if ok
	$variables ['successMessage'] = "Wache gelöscht";
}

$events = get_events ();

// Pass variables (as an array) to template
$variables = array (
		'title' => "Übersicht Wachen",
		'secured' => true,
		'events' => $events
);

renderLayoutWithContentFile ( "eventOverview_template.php", $variables );

?>