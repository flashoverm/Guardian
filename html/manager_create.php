<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/mail_controller.php";

$engines = get_engines ();

// Pass variables (as an array) to template
$variables = array (
    'title' => "Wachbeauftragten anlegen",
    'secured' => true,
    'engines' => $engines
);

if (isset ( $_POST ['email'] ) && isset ( $_POST ['engine'] ) && isset ( $_POST ['firstname'] ) && isset ( $_POST ['lastname'] )) {

	$firstname = trim($_POST ['firstname']);
	$lastname = trim($_POST ['lastname']);
	$email = strtolower(trim($_POST ['email']));
	$engine = trim($_POST ['engine']);
	
	$error = false;
	if (email_in_use ( $email )) {
	    $variables ['alertMessage'] = 'Diese E-Mail-Adresse ist bereits vergeben';
		$error = true;
	}

	if (! $error) {
		$password = random_password ();
		$result = insert_manager ( $firstname, $lastname, $email, $password, $engine );

		if ($result) {
			mail_add_manager ( $email, $password );
			$variables ['successMessage'] = 'Wachbeauftragter erfolgreich angelegt - <a href="' . $config["urls"]["html"] . '/manager_overview.php" class="alert-link">Zurück zur Übersicht</a>';
		} else {
		    $variables ['alertMessage'] = 'Beim Abspeichern ist leider ein Fehler aufgetreten';
		}
	}
}

renderLayoutWithContentFile ( "managerCreate_template.php", $variables );