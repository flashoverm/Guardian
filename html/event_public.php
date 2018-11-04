<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_user.php';
require_once '../resources/library/db_eventtypes.php';
require_once '../resources/library/db_event.php';
require_once '../resources/library/mail_controller.php';


// Pass variables (as an array) to template
$variables = array (
    'title' => "Öffentliche Wachen",
    'secured' => false,
);

if($config["settings"]["publicevents"]){
	
	$events = get_public_events();
	$variables ['events'] = $events;
} else {
	$variables ['alertMessage'] = "Öffentliche Wachen deaktiviert - <a href=\"login.php\" class=\"alert-link\">Zum Login</a>";
}


renderLayoutWithContentFile ( "eventPublic_template.php", $variables );
?>