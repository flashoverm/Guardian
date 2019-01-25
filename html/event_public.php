<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_user.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => "Öffentliche Wachen",
    'secured' => false,
);

if($config["settings"]["publicevents"]){
	
	$events = get_public_events();
	$variables ['events'] = $events;
} else {
	$variables ['alertMessage'] = "Öffentliche Wachen deaktiviert - <a href=\"" . $config["urls"]["html"] . "/login.php\" class=\"alert-link\">Zum Login</a>";
}


renderLayoutWithContentFile ( "eventPublic_template.php", $variables );
?>