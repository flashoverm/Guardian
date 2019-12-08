<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_import.php";
require_once LIBRARY_PATH . "/db_engines.php";

$engines = get_engines();

// Pass variables (as an array) to template
$variables = array (
		'title' => "Daten-Import",
		'secured' => true,
		'right' => EVENTADMIN,
		'engines' => $engines,
);

if(isset($_POST['engine'])){
	
	$error = import_user($_FILES['import']['tmp_name'], $_POST['engine']);
	if($error){
		$variables['alertMessage'] = $error;
	} else {
		$variables ['successMessage'] = "Benutzer importiert";
	}
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "userImport_template.php", $variables );
