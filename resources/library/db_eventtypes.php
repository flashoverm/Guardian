<?php
require_once 'db_connect.php';

create_table_eventtype ();

function insert_eventtype($type) {
	global $db;
	$uuid = getGUID ();
	$query = "INSERT INTO eventtype (uuid, type)
		VALUES ('" . $uuid . "', '" . $type . "')";

	$result = $db->query ( $query );

	if ($result) {
		// echo "New record created successfully<br>";
	    return $uuid;
	} else {
		echo "Error: " . $query . "<br>" . $db->error . "<br><br>";
		return false;
	}
}

function get_eventtype($uuid) {
	global $db;
	$query = "SELECT * FROM eventtype WHERE uuid = '" . $uuid . "'";
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

function get_eventtypes() {
	global $db;
	$data = array ();
	$query = "SELECT * FROM eventtype ORDER BY type";
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

function create_table_eventtype() {
	global $db;
	$query = "CREATE TABLE eventtype (
                          uuid CHARACTER(36) NOT NULL,
						  type VARCHAR(64) NOT NULL,
                          PRIMARY KEY  (uuid)
                          )";

	$result = $db->query ( $query );

	if ($result) {
		// echo "Table created<br>";
		insert_eventtype ( "Theaterwache" );
		insert_eventtype ( "Residenzwache" );
		insert_eventtype ( "Rathauswache" );
		insert_eventtype ( "Wache Sparkassenarena" );
		insert_eventtype ( "Burgwache" );
		insert_eventtype ( "Wache Grieserwiese" );
		insert_eventtype ( "Sonstige Wache (siehe Anmerkungen)" );
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}