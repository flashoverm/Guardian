<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_event.php';
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';
require_once '../resources/library/mail_controller.php';

$showFormular = true;

if (isset ( $_GET ['staffid'] ) and isset ( $_GET ['id'] )) {

	$staffUUID = trim ( $_GET ['staffid'] );
	$engines = get_engines ();
	$eventUUID = trim ( $_GET ['id'] );

	if (isset ( $_POST ['firstname'] ) and isset ( $_POST ['lastname'] ) && isset ( $_POST ['email'] ) && isset ( $_POST ['engine'] )) {

		$firstname = trim ( $_POST ['firstname'] );
		$lastname = trim ( $_POST ['lastname'] );
		$email = trim ( $_POST ['email'] );
		$engineUUID = trim ( $_POST ['engine'] );

		$error = false;
		if (strlen ( $firstname ) == 0) {
			showAlert ( 'Bitte Vorname angeben' );
			$error = true;
		}
		if (strlen ( $lastname ) == 0) {
			showAlert ( 'Bitte Nachname angeben' );
			$error = true;
		}
		if (strlen ( $email ) == 0) {
			showAlert ( 'Bitte E-Mail angeben' );
			$error = true;
		}
		
		if (! $error) {
			$user_uuid = insert_user ( $firstname, $lastname, $email, $engineUUID );
			add_staff_user ( $staffUUID, $user_uuid );
			mail_subscribe_staff_user ( $eventUUID, $email, $engineUUID );
			// TODO if ok
			showSuccess ( "Als Wachteilnehmer eingetragen" );
			$showFormular = false;
		}
		
	}
} else {
	$showFormular = false;
	showAlert ( "Fehlende Paramter" );
}

if ($showFormular) {

	// Pass variables (as an array) to template
	$variables = array (
			'title' => 'In Wache eintragen',
			'secured' => false,
			'engines' => $engines,
			'eventUUID' => $eventUUID,
			'staffUUID' => $staffUUID
	);
	
	renderLayoutWithContentFile ( "eventSubscribe_template.php", $variables );
}
?>