<?php
require_once realpath(dirname(__FILE__) . "/../resources/config.php");
require_once LIBRARY_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_report.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_staffpositions.php";
require_once LIBRARY_PATH . "/db_engines.php";

if (! isset($_GET['id'])) {
	
	// Pass variables (as an array) to template
	$variables = array(
			'title' => 'Bericht kann nicht angezeigt werden',
			'secured' => true,
			'showFormular' => false,
			'alertMessage' => "Bericht kann nicht angezeigt werden"
	);
} else {
	$uuid = trim($_GET['id']);
	$report = get_report($uuid);
	$units = get_report_units($uuid);
	
	if($report){
		$variables = array(
				'title' => "Wachbericht",
				'secured' => true,
				'showFormular' => true,
				'report' => $report,
				'units' => $units
		);
	} else {
		$variables = array(
				'title' => 'Bericht nicht gefunden',
				'secured' => true,
				'showFormular' => false,
				'alertMessage' => "Bericht nicht gefunden"
		);
	}
}

renderLayoutWithContentFile("reportDetailsTable_template.php", $variables);
