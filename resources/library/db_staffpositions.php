<?php
require_once 'db_connect.php';

create_table_staffposition ();

function insert_staffposition($position, $vehicle) {
	global $db;
	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO staffposition (uuid, position, vehicle) VALUES (?, ?, ?)");
	$statement->bind_param('ssi', $uuid, $position, $vehicle);
		
	$result = $statement->execute();
	
	if ($result) {
		// echo "New record created successfully<br>";
	    return $uuid;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function get_staffposition($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM staffposition WHERE uuid = ?");
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

function get_staffpositions() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM staffposition ORDER BY position");
	
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

function create_table_staffposition() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE staffposition (
                          uuid CHARACTER(36) NOT NULL,
						  position VARCHAR(64) NOT NULL,
                          vehicle  BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
	    insert_staffposition ( "Dienstgrad (LM)", TRUE );
	    insert_staffposition ( "Dienstgrad (HFM)", TRUE );
	    insert_staffposition ( "Maschinist", TRUE );
	    insert_staffposition ( "Atemschutzträger", TRUE );
	    insert_staffposition ( "Wachmann", TRUE );
	    
		return true;
	} else {
		return false;
	}
}