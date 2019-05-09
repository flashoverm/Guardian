<?php
require_once LIBRARY_PATH . "/db_connect.php";

create_table_eventtype ();

function insert_eventtype($type, $isseries) {
	global $db;
	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO eventtype (uuid, type, isseries) VALUES (?, ?, ?)");
	$statement->bind_param('ssi', $uuid, $type, $isseries);
		
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

function get_eventtype_from_name($name) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM eventtype WHERE type = ?");
	$statement->bind_param('s', $name);
	
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
                          isseries BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
		// echo "Table created<br>";	    
	    
	    insert_eventtype ( "Theaterwache", true );
	    insert_eventtype ( "Theaterwache Schüler", true  );
		insert_eventtype ( "Theaterwache Prantlgarten", true  );
		insert_eventtype ( "Residenzwache", false );
		insert_eventtype ( "Rathauswache", false );
		insert_eventtype ( "Wache Sparkassenarena", false );
		insert_eventtype ( "Burgwache", false );
		insert_eventtype ( "Dultwache", true  );
		insert_eventtype ( "Wache Niederbayern-Schau", true  );
		insert_eventtype ( "Wache Landshuter Hochzeit", true  );
		insert_eventtype ( "Sonstige Wache", false );
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}