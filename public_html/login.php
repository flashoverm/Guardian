<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";

if(isset ($_SESSION ['userid'])){
	header ( "Location: event_overview.php" ); // redirects
}

if (isset ( $_POST ['email'] ) && isset ( $_POST ['password'] )) {

	$email = trim ( $_POST ['email'] );
	$password = trim ( $_POST ['password'] );

	if (login_enabled ( $email )) {
		$uuid = check_password ( $email, $password );
		if ($uuid) {
			$_SESSION ['userid'] = $uuid;
			header ( "Location: event_overview.php" ); // redirects
		}
	}
	showAlert ( "E-Mail oder Passwort ungültig" );
}

// Pass variables (as an array) to template
$variables = array (
		'title' => "Guardian</h1><h5>Wachverwaltung der Freiwilligen Feuerwehr der Stadt Landshut</h5>",
		'secured' => false
);

renderLayoutWithContentFile ( "login_template.php", $variables );
?>