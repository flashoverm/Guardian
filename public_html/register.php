<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';

// Pass variables (as an array) to template
$variables = array (
		'title' => "Als Wachbeauftragter registrieren",
		'secured' => false
);

if ($config ["settings"] ["selfregistration"]) {
	$variables ['showFormular'] = true;
} else {
	$variables ['showFormular'] = false;
	$variables ['alertMessage'] = "Selbstregistrierung deaktiviert - <a href=\"login.php\" class=\"alert-link\">Zum Login</a>";
}

if (isset ( $_POST ['email'] ) && isset ( $_POST ['password'] ) && isset ( $_POST ['password2'] ) && isset ( $_POST ['engine'] ) && isset ( $_POST ['firstname'] ) && isset ( $_POST ['lastname'] )) {

	$error = false;
	$firstname = $_POST ['firstname'];
	$lastname = $_POST ['lastname'];
	$email = $_POST ['email'];
	$password = $_POST ['password'];
	$password2 = $_POST ['password2'];
	$engine = $_POST ['engine'];

	if (strlen ( $password ) == 0) {
		showAlert ( 'Bitte Passwort angeben' );
		$error = true;
	}
	if ($password != $password2) {
		showAlert ( 'Die Passwörter müssen übereinstimmen' );
		$error = true;
	}
	if (! $error) {
		if (email_in_use ( $email )) {
			$variables ['alertMessage'] = "Diese E-Mail-Adresse ist bereits vergeben";
			$error = true;
		}
	}
	if (! $error) {
		if ($config ["settings"] ["autoadmin"]) {
			$result = insert_admin ( $firstname, $lastname, $email, $password, $engine );
		} else {
			$result = insert_manager ( $firstname, $lastname, $email, $password, $engine );
		}

		if ($result) {
			header ( "Location: login.php" ); // redirects
		} else {
			$variables ['alertMessage'] = "Ein unbekannter Fehler ist aufgetreten";
		}
	}
}
$engines = get_engines ();
$variables ['engines'] = $engines;

renderLayoutWithContentFile ( "register_template.php", $variables );
?>