<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_user.php';

if (isset ( $_POST ['password_old'] ) && isset ( $_POST ['password'] ) && isset ( $_POST ['password2'] ) && isset ( $_SESSION ['userid'] )) {

	$uuid = $_SESSION ['userid'];
	$password_old = trim ( $_POST ['password_old'] );
	$password = trim ( $_POST ['password'] );
	$password2 = trim ( $_POST ['password2'] );

	$error = false;
	if (strlen ( $password_old ) == 0) {
		showAlert ( 'Bitte aktuelles Passwort eingeben' );
		$error = true;
	}
	if (strlen ( $password ) == 0) {
		showAlert ( 'Bitte neues Passwort eingeben' );
		$error = true;
	}
	if ($password != $password2) {
		showAlert ( 'Die Passwörter müssen übereinstimmen' );
		$error = true;
	}

	if (! $error) {
		$uuid = change_password ( $uuid, $password_old, $password );
		showSuccess ( "Password erfolgreich ge�ndert" );
	}
}

// Pass variables (as an array) to template
$variables = array (
'title' => "Passwort ändern",
'secured' => true,
);

renderLayoutWithContentFile ( "changePassword_template.php", $variables );

?>