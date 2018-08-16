
<?php
require_once (realpath ( dirname ( __FILE__ ) . "/../resources/config.php" ));
require_once (LIBRARY_PATH . "/template.php");


/*
 * Logic of the page
 */


// Pass variables (as an array) to template
$variables = array (
		'title' => "Home",
		'secured' => false,
);

renderLayoutWithContentFile ("exampleContent.php", $variables );

?>