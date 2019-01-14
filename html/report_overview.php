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

$reports = get_reports();
$variables ['reports'] = $reports;

renderLayoutWithContentFile ( "reportOverview_template.php", $variables );

?>