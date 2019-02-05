<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => "Übersicht Wachbeauftragte",
		'secured' => true,
);

if (isset ( $_POST ['disable'] )) {
	$delete_manager_uuid = trim ( $_POST ['disable'] );
	if($delete_manager_uuid == $_SESSION ['guardian_userid']){
		$variables ['alertMessage'] = "Eigenes Konto kann nicht deaktiviert werden";
	} else if(deactivate_manager ( $delete_manager_uuid )) {
		$variables ['successMessage'] = "Wachbeauftragter deaktiviert";	
	} else {
		$variables ['alertMessage'] = "Deaktivieren des Wachbeauftragten fehlgeschlagen";
	}
}
if (isset ( $_POST ['enable'] )) {
	$delete_manager_uuid = trim ( $_POST ['enable'] );
	if($delete_manager_uuid == $_SESSION ['guardian_userid']){
		$variables ['alertMessage'] = "Eigenes Konto kann nicht aktiviert werden";
	} else if(reactivate_manager ( $delete_manager_uuid )){
		$variables ['successMessage'] = "Wachbeauftragter aktiviert";
	} else {
		$variables ['alertMessage'] = "Aktivieren des Wachbeauftragten fehlgeschlagen";
	}
}

if (isset ( $_POST ['resetpw'] )) {
	$resetpw_manager_uuid = trim ( $_POST ['resetpw'] );
	$password = reset_password ( $resetpw_manager_uuid );
	if($password){
		mail_reset_password ( $resetpw_manager_uuid, $password );
		$variables ['successMessage'] = "Passwort zurückgesetzt";
	} else {
		$variables ['alertMessage'] = "Passwort konnte nicht zurückgesetzt werden";
	}
}
$manager = get_all_manager ();
$variables ['manager'] = $manager;

renderLayoutWithContentFile ( "managerOverview_template.php", $variables );

?>