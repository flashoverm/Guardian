<?php
require_once 'db_connect.php';

create_table_staffpositions ();

function insert_staffposition($position, $vehicle) {
	global $db;
	$uuid = getGUID ();
	
	if($vehicle){
	    $query = "INSERT INTO staffpositions (uuid, position, vehicle)
		VALUES ('" . $uuid . "', '" . $position . "', TRUE)";
	} else {
	    $query = "INSERT INTO staffpositions (uuid, position, vehicle)
		VALUES ('" . $uuid . "', '" . $position . "', FALSE)";
	}


	$result = $db->query ( $query );

	if ($result) {
		// echo "New record created successfully<br>";
	    return $uuid;
	} else {
		echo "Error: " . $query . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function get_staffposition($uuid) {
	global $db;
	$query = "SELECT * FROM staffposition WHERE uuid = '" . $uuid . "'";
	$result = $db->query ( $query );
	if ($result) {
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
	$query = "SELECT * FROM staffpositions ORDER BY position";
	$result = $db->query ( $query );
	if ($result) {
		if (mysqli_num_rows ( $result )) {
			while ( $date = $result->fetch_object () ) {
				$data [] = $date;
			}
			$result->free ();
		}
	}
	return $data;
}

function create_table_staffpositions() {
	global $db;
	$query = "CREATE TABLE staffpositions (
                          uuid CHARACTER(36) NOT NULL,
						  position VARCHAR(64) NOT NULL,
                          vehicle  BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid)
                          )";

	$result = $db->query ( $query );

	if ($result) {
		// echo "Table created<br>";
	    insert_staffposition ( "Gruppenführer", TRUE );
	    insert_staffposition ( "Maschinist", TRUE );
	    insert_staffposition ( "Atemschutzträger", TRUE );
	    insert_staffposition ( "Feuerwehrmann/-frau", TRUE );
	    insert_staffposition ( "Wachhabender", FALSE );
	    insert_staffposition ( "Wachmann/-frau", FALSE );
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}