<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once LIBRARY_PATH . "/db_user.php";

session_start ();

$isAdmin = isset ( $_SESSION ['guardian_userid'] ) && is_admin ( $_SESSION ['guardian_userid'] ) ;

if(!$isAdmin){
	http_response_code(401);
} else {
	if (isset($_GET['uuid'])) {
		
		$privileges = get_users_privileges($_GET['uuid']);
		if($privileges){
			$string = "[";
			foreach($privileges as $privilege){
				$string .= "\n\t\"" . $privilege . "\",";
			}
			$string = substr($string, 0, -1);
			$string .= "\n]";
			echo $string;
			header('Content-Type: text/plain');
			//header('Content-Type: application/json');
			
		} else {
			http_response_code(404);
		}
	} else {
		http_response_code(400);
	}
	
}

?>