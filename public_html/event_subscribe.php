<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_event.php';
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';
require_once '../resources/library/mail_controller.php';

// Pass variables (as an array) to template
$variables = array (
    'title' => 'In Wache eintragen',
    'secured' => false,
);

if (isset ( $_GET ['staffid'] ) and isset ( $_GET ['id'] )) {

	$staffUUID = trim ( $_GET ['staffid'] );
	$engines = get_engines ();
	$eventUUID = trim ( $_GET ['id'] );
	
	$variables ['engines'] = $engines;
	$variables ['eventUUID'] = $eventUUID;
	$variables ['staffUUID'] = $staffUUID;

	if (isset ( $_POST ['firstname'] ) and isset ( $_POST ['lastname'] ) && isset ( $_POST ['email'] ) && isset ( $_POST ['engine'] )) {

		$firstname = trim ( $_POST ['firstname'] );
		$lastname = trim ( $_POST ['lastname'] );
		$email = trim ( $_POST ['email'] );
		$engineUUID = trim ( $_POST ['engine'] );

		$user_uuid = insert_user ( $firstname, $lastname, $email, $engineUUID );
		add_staff_user ( $staffUUID, $user_uuid );
		mail_subscribe_staff_user ( $eventUUID, $email, $engineUUID );
		// TODO if ok
		$variables ['successMessage'] = "Als Wachteilnehmer eingetragen - <a href=\"event_details.php?id=" . $eventUUID . "\" class=\"alert-link\">Zurück</a>";
		$variables ['showFormular'] = false;
	}
} else {
    $variables ['showFormular'] = false;
    $variables ['alertMessage'] = "Fehlende Paramter";
}

renderLayoutWithContentFile ( "eventSubscribe_template.php", $variables );
?>