<?php
require_once 'db_connect.php';

create_table_eventtype ();

function insert_eventtype($type) {
	global $db;
	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO eventtype (uuid, type) VALUES (?, ?)");
	$statement->bind_param('ss', $uuid, $type);
		
	$result = $statement->execute();

	if ($result) {
		// echo "New record created successfully<br>";
	    return $uuid;
	} else {
		//echo "Error: " . $statement . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function get_eventtype($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM eventtype WHERE uuid = ?");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			return $data;
		}
	}
	return false;
}

function get_eventtypes() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM eventtype ORDER BY type");
	
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

function create_table_eventtype() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE eventtype (
                          uuid CHARACTER(36) NOT NULL,
						  type VARCHAR(64) NOT NULL,
                          PRIMARY KEY  (uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
		// echo "Table created<br>";
		insert_eventtype ( "Theaterwache" );
		insert_eventtype ( "Theaterwache Schüler" );
		insert_eventtype ( "Theaterwache Prantlgarten" );
		insert_eventtype ( "Residenzwache" );
		insert_eventtype ( "Rathauswache" );
		insert_eventtype ( "Wache Sparkassenarena" );
		insert_eventtype ( "Burgwache" );
		insert_eventtype ( "Dultwache" );
		insert_eventtype ( "Sonstige Wache" );
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}