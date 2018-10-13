<?php
require_once 'db_connect.php';

create_table_engine ();

function insert_engine($name) {
	global $db;
	$uuid = getGUID ();
	$query = "INSERT INTO engine (uuid, name)
		VALUES ('" . $uuid . "', '" . $name . "')";

	$result = $db->query ( $query );

	if ($result) {
		 //echo "New record created successfully<br>";
		 return true;
	} else {
		 //echo "Error: " . $query . "<br>" . $db->error . "<br><br>";
		 return false;
	}
}

function get_engine($uuid) {
	global $db;
	$query = "SELECT * FROM engine WHERE uuid = '" . $uuid . "'";
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

function get_engine_from_name($name) {
	global $db;
	$query = "SELECT * FROM engine WHERE name = '" . $name . "'";
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

function get_engines() {
	global $db;
	$data = array ();
	$query = "SELECT * FROM engine ORDER BY name";
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

function create_table_engine() {
	global $db;
	$query = "CREATE TABLE engine (
                          uuid CHARACTER(36) NOT NULL,
						  name VARCHAR(32) NOT NULL,
                          PRIMARY KEY  (uuid)
                          )";

	$result = $db->query ( $query );

	if ($result) {
		insert_engine( "Geschäftszimmer" );
		insert_engine ( "Löschzug 1/2" );
		insert_engine ( "Löschzug 3" );
		insert_engine ( "Löschzug 4" );
		insert_engine ( "Löschzug 5" );
		insert_engine ( "Löschzug 6" );
		insert_engine ( "Löschzug 7" );
		insert_engine ( "Löschzug 8" );
		insert_engine ( "Löschzug 9" );
		return true;
	} else {
		return false;
	}
}