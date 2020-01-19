<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . '/db_engines.php';
require_once LIBRARY_PATH . '/db_eventtypes.php';
require_once LIBRARY_PATH . '/db_staffpositions.php';
require_once LIBRARY_PATH . '/db_report.php';
require_once LIBRARY_PATH . '/mail_controller.php';
require_once LIBRARY_PATH . '/file_create_report.php';

require_once LIBRARY_PATH . '/class/EventReport.php';
require_once LIBRARY_PATH . '/class/ReportUnit.php';
require_once LIBRARY_PATH . '/class/ReportUnitStaff.php';

$eventtypes = get_eventtypes ();
$staffpositions = get_staffpositions();
$engines = get_engines();

// Pass variables (as an array) to template
$variables = array (
		'secured' => false,
		'eventtypes' => $eventtypes,
		'staffpositions' => $staffpositions,
		'engines' => $engines,
);

if(isset($_GET['id'])){
	
	$uuid = trim($_GET['id']);
	$report = get_report_object($uuid);
	$variables['object'] = $report;
	
	$variables['title'] = 'Wachbericht bearbeiten';
	
} else if(isset($_GET['event'])){
	$event = get_event($_GET['event']);
	$variables['object'] = $event;
}



renderLayoutWithContentFile ($config["apps"]["guardian"], "reportEdit/reportEdit_template.php", $variables );

?>