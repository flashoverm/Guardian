<?php
require_once 'db_connect.php';

create_table_staffposition ();

function insert_staffposition($position, $vehicle) {
	global $db;
	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO staffposition (uuid, position, vehicle) VALUES (?, ?, ?)");
	
	if($vehicle){
	    $statement->bind_param('ssi', $uuid, $position, true);
	} else {
	    $statement->bind_param('ssi', $uuid, $position, false); 
	}
		
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
		insert_staffposition ( "GF (mind. LM)", TRUE );
	    insert_staffposition ( "GF (mind. HFM)", TRUE );
	    insert_staffposition ( "KF", TRUE );
	    insert_staffposition ( "MA 1", TRUE );
	    insert_staffposition ( "MA 2", TRUE );
	    insert_staffposition ( "MA 3", TRUE );
	    insert_staffposition ( "MA 4", TRUE );
	    insert_staffposition ( "MA 5", TRUE );
	    insert_staffposition ( "MA 6", TRUE );
	    insert_staffposition ( "MA 7", TRUE );
	    insert_staffposition ( "MA 1 AT-Träger", TRUE );
	    insert_staffposition ( "MA 2 AT-Träger", TRUE );
	    insert_staffposition ( "MA 3 AT-Träger", TRUE );
	    insert_staffposition ( "MA 4 AT-Träger", TRUE );
	    insert_staffposition ( "MA 5 AT-Träger", TRUE );
	    insert_staffposition ( "MA 6 AT-Träger", TRUE );
	    insert_staffposition ( "MA 7 AT-Träger", TRUE );
	    
	    insert_staffposition ( "Wachhabender", FALSE );
	    insert_staffposition ( "WM 1", FALSE );
	    insert_staffposition ( "WM 2", FALSE );
	    insert_staffposition ( "WM 3", FALSE );
	    insert_staffposition ( "WM 4", FALSE );
	    insert_staffposition ( "WM 5", FALSE );
	    insert_staffposition ( "WM 6", FALSE );
	    insert_staffposition ( "WM 7", FALSE );
	    insert_staffposition ( "WM 8", FALSE );
	    insert_staffposition ( "WM 9", FALSE );
	    /*
	    insert_staffposition ( "Gruppenführer", TRUE );
	    insert_staffposition ( "Maschinist", TRUE );
	    insert_staffposition ( "Atemschutzträger", TRUE );
	    insert_staffposition ( "Feuerwehrmann/-frau", TRUE );
	    insert_staffposition ( "Wachhabender", FALSE );
	    insert_staffposition ( "Wachmann/-frau", FALSE );
	    */
		return true;
	} else {
		return false;
	}
}