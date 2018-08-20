<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';
require_once '../resources/library/mail_controller.php';

if (isset ( $_POST ['email'] ) && isset ( $_POST ['engine'] ) && isset ( $_POST ['firstname'] ) && isset ( $_POST ['lastname'] )) {

	$firstname = $_POST ['firstname'];
	$lastname = $_POST ['lastname'];
	$email = $_POST ['email'];
	$engine = $_POST ['engine'];

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
		if (email_in_use ( $email )) {
			showError ( 'Diese E-Mail-Adresse ist bereits vergeben' );
			$error = true;
		}
	}

	if (! $error) {
		$password = random_password ();
		$result = insert_manager ( $firstname, $lastname, $email, $password, $engine );

		if ($result) {
			mail_add_manager ( $email, $password );
			$showFormular = false;
			showSuccess ( 'Wachbeauftragter erfolgreich angelegt - <a href="manager_overview.php" class="alert-link">Zurück zur Übersicht</a>' );
		} else {
			showError ( 'Beim Abspeichern ist leider ein Fehler aufgetreten' );
		}
	}
}

$engines = get_engines ();

// Pass variables (as an array) to template
$variables = array (
'title' => "Wachbeauftragten anlegen",
'secured' => true,
'engines' => $engines
);

renderLayoutWithContentFile ( "managerCreate_template.php", $variables );