<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
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
		
		if(isset($_SESSION ['guardian_userid'])){
			
			$user = get_user($_SESSION ['guardian_userid']);
			
			if($report->engine == $user->engine || is_administration($user->engine) || is_admin($user->uuid)){
				
				$variables = array(
						'title' => "Wachbericht",
						'secured' => true,
						'showFormular' => true,
						'report' => $report,
						'units' => $units
				);
				
				if(isset($_POST['emsEntry'])){
					if(set_ems_entry($uuid)){
						$variables['successMessage'] = "Bericht aktualisiert";
					} else {
						$variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
					}
					$variables['report'] = get_report($uuid);
				}
				
			} else {
				$variables = array(
						'title' => 'Sie haben keine Zugriffsrechte auf diesen Bericht',
						'secured' => true,
						'showFormular' => false,
						'alertMessage' => "Sie haben keine Zugriffsrechte auf diesen Bericht"
				);
			}
		}

	} else {
		$variables = array(
				'title' => 'Bericht nicht gefunden',
				'secured' => true,
				'showFormular' => false,
				'alertMessage' => "Bericht nicht gefunden"
		);
	}
}

renderLayoutWithContentFile($config["apps"]["guardian"], "reportDetails_template.php", $variables);
