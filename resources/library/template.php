<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . '/db_user.php';

session_start ();

function renderLayoutWithContentFile($contentFile, $variables = array()) {
	global $config;
	$contentFileFullPath = TEMPLATES_PATH . "/pages/" . $contentFile;

	// making sure passed in variables are in scope of the template
	// each key in the $variables array will become a variable
	if (count ( $variables ) > 0) {
		foreach ( $variables as $key => $value ) {
			if (strlen ( $key ) > 0) {
				${$key} = $value;
			}
		}
	}

	$loggedIn = isset ( $_SESSION ['guardian_userid'] );
	$isAdmin = $loggedIn && is_admin ( $_SESSION ['guardian_userid'] );

	require_once (TEMPLATES_PATH . "/header.php");

	echo "<div class=\"container\" id=\"container\">\n" . "\t<div id=\"content\">\n";

	if ($secured && ! $loggedIn) {
		showAlert ( 'Bitte zuerst <a href="' . $config["urls"]["html"] . '/login" class="alert-link">einloggen</a>' );
	} else {	
		
		if(isset($alertMessage)){
			showAlert($alertMessage);
		}
		
		if(isset($successMessage)){
			showSuccess($successMessage);
		}
		
		if(isset($infoMessage)){
			showInfo($infoMessage);
		}
		
		if (file_exists ( $contentFileFullPath )) {
			if(!isset($showFormular) || $showFormular){
				require_once ($contentFileFullPath);
			}
		} else {
			echo "Requested template not existing";
		}
		
	}

	// close content div
	echo "\t</div>\n";

	// close container div
	echo "</div>\n";

	require_once (TEMPLATES_PATH . "/footer.php");
}

function showAlert($message) {
	echo "<div class=\"alert alert-danger\" role=\"alert\">" . $message . "</div>";
}

function showSuccess($message) {
	echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
}

function showInfo($message) {
	echo "<div class=\"alert alert-secondary\" role=\"alert\">" . $message . "</div>";
}
?>