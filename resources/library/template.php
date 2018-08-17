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

	$loggedIn = isset ( $_SESSION ['userid'] );
	$isAdmin = $loggedIn && is_admin ( $_SESSION ['userid'] );

	require_once (TEMPLATES_PATH . "/header.php");

	echo "<div class=\"container\" id=\"container\">\n" . "\t<div id=\"content\">\n";

	if ($secured && ! $loggedIn) {
		showAlert ( 'Bitte zuerst <a href="login.php" class="alert-link">einloggen</a>' );
	} else {
		
		if (file_exists ( $contentFileFullPath )) {
			require_once ($contentFileFullPath);
		} else {
			/*
			 * If the file isn't found the error can be handled in lots of ways.
			 * In this case we will just include an error template.
			 */
			require_once (TEMPLATES_PATH . "/error.php");
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