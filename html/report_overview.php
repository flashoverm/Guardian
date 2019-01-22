<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_report.php';
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_eventtypes.php';

// Pass variables (as an array) to template
$variables = array (
    'title' => "Übersicht Wachberichte",
    'secured' => true,
);

$user = $_SESSION ['guardian_userid'];
$usersEngine = get_engine(get_engine_of_user($user));

if($usersEngine->name == 'Geschäftszimmer'){
    $variables ['reports'] = get_reports();
} else {
    $variables ['reports'] = get_filtered_reports($usersEngine->uuid);
    $variables ['infoMessage'] = "Es werden nur Wachbericht angezeigt, die Ihrem Zug zugewiesen wurden";
    
}



renderLayoutWithContentFile ( "reportOverview_template.php", $variables );

?>