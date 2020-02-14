<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_report.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";

$eventtypes = get_eventtypes ();

// Pass variables (as an array) to template
$variables = array (
    'title' => "Export Wachberichte",
    'secured' => true,
    'eventtypes' => $eventtypes,
	'right' => EVENTMANAGER,
);

$type = -1;
$from = date('Y-m-01');
$to = date('Y-m-t');

if(isset($_SESSION ['guardian_userid'])){
    $user = $_SESSION ['guardian_userid'];
    $usersEngine = get_engine(get_engine_of_user($user));
        
    if($usersEngine->isadministration == true){
        $variables ['reports'] = get_reports();
    } else {
        $reports = get_filtered_reports($usersEngine->uuid);
        $variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die Ihrem Zug zugewiesen wurden";
    }
    
    if(isset($_POST['from'])){
        $type = $_POST['type'];
        $from = $_POST['from'];
        $to = $_POST['to'];
    }
    
    $reports = filter_reports($reports, $type, $from, $to);
    
    
    $variables ['type'] = $type;
    $variables ['from'] = $from;
    $variables ['to'] = $to;
    $variables ['reports'] = $reports;
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "reportExport_template.php", $variables );

?>