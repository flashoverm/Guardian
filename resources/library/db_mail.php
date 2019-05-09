<?php
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_connect.php";


function get_manager_except_engine_and_creator($engine_uuid, $creator_uuid){
	global $db;
	$data = array ();
		
	$statement = $db->prepare("SELECT * FROM user WHERE ismanager = TRUE AND NOT engine = ? AND NOT uuid = ?");
	$statement->bind_param('ss', $engine_uuid, $creator_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			while ( $date = $result->fetch_object () ) {
				$data [] = $date;
			}
			$result->free ();
		}
	}
	return $data;
}