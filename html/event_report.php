<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once LIBRARY_PATH . '/db_engines.php';
require_once LIBRARY_PATH . '/db_eventtypes.php';
require_once LIBRARY_PATH . '/db_staffpositions.php';
require_once LIBRARY_PATH . '/mail_controller.php';

require_once LIBRARY_PATH . '/class/EventReport.php';
require_once LIBRARY_PATH . '/class/ReportUnit.php';
require_once LIBRARY_PATH . '/class/ReportUnitStaff.php';


// Pass variables (as an array) to template
$variables = array (
    'title' => 'Wachbericht erstellen',
    'secured' => false
);

if ($config ["settings"] ["reportfunction"]) {
    $variables ['showFormular'] = true;
    
    $eventtypes = get_eventtypes ();
    $staffpositions = get_staffpositions();
    $engines = get_engines();
    
    
    $variables ['engines'] = $engines;
    $variables ['eventtypes'] = $eventtypes;
    $variables ['staffpositions'] = $staffpositions;
    
    
} else {
    $variables ['showFormular'] = false;
    $variables ['alertMessage'] = "Funktion \"Wachbericht erstellen\" deaktiviert - <a href=\"login.php\" class=\"alert-link\">Zur Startseite</a>";
}

if (isset ( $_POST ['title'] ) and isset ( $_POST ['creator'] )) {
    
    $date = trim ( $_POST ['date'] );
    $beginn = trim ( $_POST ['start'] );
    $end = trim ( $_POST ['end'] );
    $type = trim ( $_POST ['type'] );
    
    if(isset( $_POST ['typeOther'] )){
    	$type = trim( $_POST ['typeOther'] );
    }
    
    $title = trim ( $_POST ['title'] );
    $engine = trim ($_POST ['engine']);
    $noIncidents = false;
    $ilsEntry = false;
    $report = "";
    $creator = trim ($_POST ['creator']);
    
    if(isset($_POST ['noIncidents'])){
        $noIncidents = true;
    }
    if(isset($_POST ['ilsEntry'])){
        $ilsEntry = true;
    }
    if (isset ( $_POST ['report'] )) {
    	$report = trim ( $_POST ['report'] );
    }
    
    $eventReport = new EventReport($date, $beginn, $end, $type, $title, $engine, $noIncidents, $report, $creator, $ilsEntry);

    $unitCount = 1;
    while ( isset ( $_POST ["unit" . $unitCount . "unit"] ) ) {
    	$unitdate = trim ( $_POST ['unit' . $unitCount . 'date'] );
    	$unitbeginn = trim ( $_POST ['unit' . $unitCount . 'start'] );
    	$unitend = trim ( $_POST ['unit' . $unitCount . 'end'] );
    	$unitname = trim ( $_POST ['unit' . $unitCount . 'unit'] );
    	$unitkm = trim ( $_POST ['unit' . $unitCount . 'km'] );
    	
    	$unit = new ReportUnit($unitname, $unitdate, $unitbeginn, $unitend);
    	if($unitkm != ""){
    		$unit->setKM($unitkm);
    	}
    	
        $position = 1;
        while ( isset ( $_POST ["unit" . $unitCount . "function" . $position] ) ) {
        	$function = trim ( $_POST ["unit" . $unitCount . "function" . $position] );
        	$name = trim ( $_POST ["unit" . $unitCount . "name" . $position] );
        	$engine = trim ( $_POST ["unit" . $unitCount . "engine" . $position] );
        	
        	$unit->addStaff(new ReportUnitStaff($function, $name, $engine));
        	
        	$position += 1;
        }
        
        $eventReport->addUnit($unit);
        $unitCount += 1;
    }
    
    if(mail_send_report ($eventReport)){
    	$variables ['successMessage'] = "Bericht versendet";
    } else {
    	$variables ['alertMessage'] = "Bericht konnte nicht versendet werden";
    }

}

renderLayoutWithContentFile ( "eventReport/eventReport_template.php", $variables );
?>

<script type='text/javascript'>
	var currentPosition = 1;
	
	function addStaff(){
		currentPosition += 1;
		var container = document.getElementById("staffContainer");
		var input = document.createElement("input");
		input.className ="form-control";
		input.type = "text";
		input.name = "staff" + currentPosition;
		input.id = "staff" + currentPosition;
		input.required = "required";
		input.placeholder="Funktionsbezeichnung eingeben";
		container.appendChild(input);
	}
	
	function removeLast(){
		if(currentPosition != 1){
			var lastStaffRow = document.getElementById("staff"+currentPosition);
			lastStaffRow.parentNode.removeChild(lastStaffRow);
			currentPosition -= 1;
		}
	}
</script>
