<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_user.php';
require_once '../resources/library/db_eventtypes.php';
require_once '../resources/library/db_event.php';
require_once '../resources/library/mail_controller.php';


// Pass variables (as an array) to template
$variables = array (
    'title' => "Admin-Übersicht Wachen",
    'secured' => true,
);

if(isset($_SESSION ['guardian_userid']) && is_admin($_SESSION ['guardian_userid'])){
        
    $events = get_all_active_events ();
    $pastEvents = get_all_past_events();
    $variables ['events'] = $events;
    $variables ['pastEvents'] = $pastEvents;
}


renderLayoutWithContentFile ( "eventAdmin_template.php", $variables );
?>