<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . '/util.php';
require_once LIBRARY_PATH . '/db_user.php';

session_start ();

function renderLayoutWithContentFile($app, $contentFile, $variables = array()) {
	global $config;
	
	$contentFileFullPath = TEMPLATES_PATH . "/" . $app .  "/pages/" . $contentFile;
	
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
	    goToLogin();
	} else {	
	    
	    if(isset($right) && !userHasRight($right)){
	        showAlert("Sie haben keine Berechtigung diese Seite anzuzeigen");
	        $showFormular = false;
	    }
		
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

?>