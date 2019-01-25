<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_engines.php";

if (isset ( $_SESSION ['guardian_userid'] )) {
	header ( "Location: " . $config["urls"]["html"] . "/event_overview.php" ); // redirects	
}

// Pass variables (as an array) to template
$variables = array (
		'title' => "Wachverwaltung",
		'subtitle' => "der Freiwilligen Feuerwehr der Stadt Landshut",
		'secured' => false
);

if (isset ( $_POST ['email'] ) && isset ( $_POST ['password'] )) {

    $email = strtolower(trim ( $_POST ['email'] ));
	$password = trim ( $_POST ['password'] );

	if (login_enabled ( $email )) {
		$uuid = check_password ( $email, $password );
		if ($uuid) {
			$_SESSION ['guardian_userid'] = $uuid;
			$_SESSION ['guardian_usermail'] = $email;
			$_SESSION ['guardian_engine'] = get_engine(get_engine_of_user($uuid))->name;
			
			header ( "Location: " . $config["urls"]["html"] . "/event_overview.php" ); // redirects
		}
	}
	$variables ['alertMessage'] = "E-Mail oder Passwort ungültig";
}

renderLayoutWithContentFile ( "login_template.php", $variables );
?>