<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_user.php';
require_once '../resources/library/mail_controller.php';

// Pass variables (as an array) to template
$variables = array (
		'title' => "Übersicht Wachbeauftragte",
		'secured' => true,
);

if (isset ( $_POST ['disable'] )) {
	$delete_manager_uuid = trim ( $_POST ['disable'] );
	deactivate_manager ( $delete_manager_uuid );
	// if ok
	$variables ['successMessage'] = "Wachbeauftragter deaktiviert";
}
if (isset ( $_POST ['enable'] )) {
	$delete_manager_uuid = trim ( $_POST ['enable'] );
	reactivate_manager ( $delete_manager_uuid );
	// if ok
	$variables ['successMessage'] = "Wachbeauftragter aktiviert";
}

if (isset ( $_POST ['resetpw'] )) {
	$resetpw_manager_uuid = trim ( $_POST ['resetpw'] );
	$password = reset_password ( $resetpw_manager_uuid );
	mail_reset_password ( $resetpw_manager_uuid, $password );
	// if ok
	$variables ['successMessage'] = "Passwort zurückgesetzt und per E-Mail zugestellt";
}
$manager = get_manager ();
$variables ['manager'] = $manager;

renderLayoutWithContentFile ( "managerOverview_template.php", $variables );

?>