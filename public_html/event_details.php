<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_eventtypes.php';
require_once '../resources/library/db_event.php';
require_once '../resources/library/db_user.php';
require_once '../resources/library/mail_controller.php';

if (! isset ( $_GET ['id'] )) {

	// Pass variables (as an array) to template
	$variables = array (
			'title' => 'Wache kann nicht angezeigt werden',
			'secured' => false,
			'showFormular' => false,
			'alertMessage' => "Wache kann nicht angezeigt werden - <a href=\"event_overview.php\" class=\"alert-link\">Zurück</a>"
	);
} else {
	$uuid = trim ( $_GET ['id'] );
	$event = get_event ( $uuid );
	$staff = get_staff ( $uuid );

	if (isset ( $_POST ['staffid'] )) {
		$staff_uuid = trim ( $_POST ['staffid'] );
		mail_remove_staff_user ( $staff_uuid, $uuid );
		remove_staff_user ( $staff_uuid );
		// if ok
		$variables ['successMessage'] = "Eintrag entfernt" ;
		$event = get_event ( $uuid );
	}

	$isManager = false;
	if(isset($_SESSION ['userid'])){
		$isManager = (strcmp ( $event->manager, $_SESSION ['userid'] ) == 0);
	}

	// Pass variables (as an array) to template
	$variables = array (
			'title' => get_eventtype ( $event->type )->type,
			'secured' => false,
			'showFormular' => true,
			'isManager' => $isManager,
			'event' => $event,
			'staff' => $staff
	);
}
renderLayoutWithContentFile ( "eventDetails_template.php", $variables );
?>