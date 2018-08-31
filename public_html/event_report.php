<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once LIBRARY_PATH . "/template.php";
require_once '../resources/library/db_engines.php';
require_once '../resources/library/db_eventtypes.php';


// Pass variables (as an array) to template
$variables = array (
    'title' => 'Wachbericht erstellen',
    'secured' => false
);

if ($config ["settings"] ["reportfunction"]) {
    $variables ['showFormular'] = true;
    
    $eventtypes = get_eventtypes ();
    $engines = get_engines();
    
    $variables ['engines'] = $engines;
    $variables ['eventtypes'] = $eventtypes;
    
} else {
    $variables ['showFormular'] = false;
    $variables ['alertMessage'] = "Funktion \"Wachbericht erstellen\" deaktiviert - <a href=\"login.php\" class=\"alert-link\">Zur Startseite</a>";
}

if (isset ( $_POST ['title'] ) and isset ( $_POST ['type'] )) {
    
    $date = trim ( $_POST ['date'] );
    $start = trim ( $_POST ['start'] );
    $end = trim ( $_POST ['end'] );
    $type = trim ( $_POST ['type'] );
    $title = trim ( $_POST ['title'] );
    $engine = trim ($_POST ['engine']);
    $noIncidents = false;
    $report = "";
    $creator = trim ($_POST ['creator']);
    
    showAlert($date);
    
    if($engine == $config ["backoffice"]){
        
    }
    if(isset($_POST ['noIncidents'])){
        $noIncidents = true;
    }
    if (isset ( $_POST ['report'] )) {
        $comment = trim ( $_POST ['report'] );
    }

    $position = 1;
    while ( isset ( $_POST ["staff" . $position] ) ) {
        $staff = trim ( $_POST ["staff" . $position] );
        if (strlen ( $staff ) == 0) {
            showAlert ( 'Bitte Funktionsbezeichnung ' . $position . ' eingeben' );
            $error = true;
        }
        $position += 1;
    }
    
    $position = 1;
    while ( isset ( $_POST ["staff" . $position] ) ) {
        $staff = trim ( $_POST ["staff" . $position] );
        $position += 1;
    }
    
    mail_send_report ( $event_uuid, $manager, $informOther);
    // if ok
    $variables ['successMessage'] = "Bericht versendet";

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
