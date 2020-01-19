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

if (isset($_POST) && isset($_POST ['start'])) {
       
    $date = trim ( $_POST ['date'] );
    $beginn = trim ( $_POST ['start'] );
    $end = trim ( $_POST ['end'] );
    $type = trim ( $_POST ['type'] );
    
    $typeOther = null;
    if(isset( $_POST ['typeOther'] ) && !empty( $_POST ['typeOther'] ) ){
        $typeOther = trim( $_POST ['typeOther'] );
    }
    $title = trim ( $_POST ['title'] );
    if(empty ($title)){
        $title = null;
    }
    $engine = trim ($_POST ['engine']);
    $noIncidents = false;
    if(isset($_POST ['noIncidents'])){
        $noIncidents = true;
    }
    $ilsEntry = false;
    if(isset($_POST ['ilsEntry'])){
        $ilsEntry = true;
    }
    $report = "";
    if (isset ( $_POST ['report'] )) {
        $report = trim ( $_POST ['report'] );
    }
    $creator = trim ($_POST ['creator']);
        

    $eventReport = new EventReport($date, $beginn, $end, $type, $typeOther, 
        $title, $engine, $noIncidents, $report, $creator, $ilsEntry);
    
    $unitCount = 1;
    while ( isset ( $_POST ["unit" . $unitCount . "unit"] ) ) {
        $unitdate = trim ( $_POST ['unit' . $unitCount . 'date' . "field"] );
        $unitbeginn = trim ( $_POST ['unit' . $unitCount . 'start' . "field"] );
        $unitend = trim ( $_POST ['unit' . $unitCount . 'end' . "field"] );
        $unitname = trim ( $_POST ['unit' . $unitCount . 'unit'] );
        
        $unit = new ReportUnit($unitname, $unitdate, $unitbeginn, $unitend);
        if(isset ( $_POST ['unit' . $unitCount . 'km'] ) && $_POST ['unit' . $unitCount . 'km'] != ""){
            $unitkm = trim ( $_POST ['unit' . $unitCount . 'km'] );
            $unit->setKM($unitkm);
        }
        
        $position = 1;
        while ( isset ( $_POST ["unit" . $unitCount . "function" . $position . "field"] ) ) {
            $function = trim ( $_POST ["unit" . $unitCount . "function" . $position . "field"] );
            $name = trim ( $_POST ["unit" . $unitCount . "name" . $position . "field"] );
            $engineUnit = trim ( $_POST ["unit" . $unitCount . "engine" . $position . "field"] );

            $unit->addStaff(new ReportUnitStaff($function, $name, $engineUnit));
            
            $position += 1;
        }
        
        $eventReport->addUnit($unit);
        $unitCount += 1;
    }
    
    
    if(isset($_GET['id'])){
        //Update
        update_report($eventReport);
        
        
    } else {
        insert_report($eventReport);
        
        //Insert
    }
    
        
    echo $eventReport->toHTML();
}


renderLayoutWithContentFile ($config["apps"]["guardian"], "reportEdit/reportEdit_template.php", $variables );

?>