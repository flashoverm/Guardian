<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once LIBRARY_PATH . "/db_staff_template.php";

if (isset($_GET['uuid'])) {

	$template = get_staff_template($_GET['uuid']);
	if($template){
		$string = "[";
		foreach($template as $entry){
			$string .= "\n{\n \"uuid\":\"" . $entry->uuid . "\",\n";
			$string .= "\"position\":\"" . $entry->position . "\"\n";
			$string .= "},";
		}
		$string = substr($string, 0, -1);
		$string .= "\n]";
		echo $string;
		header('Content-Type: text/plain');
		//header('Content-Type: application/json');
		
	} else {
		echo "test";
		//http_response_code(404);
	}
} else {
	http_response_code(400);
}

?>