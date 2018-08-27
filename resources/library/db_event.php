<?php
require_once 'db_connect.php';
require_once 'mail.php';
require_once 'db_user.php';

create_table_events ();
create_table_staff ();

function insert_event($date, $start, $end, $type_uuid, $title, $comment, $engine_only, $manager) {
	global $db;

	$uuid = getGUID ();
	$hash = hash ( "sha256", $uuid . $date . $start . $end . $type_uuid . $title );
	if($engine_only){
	    $engine = get_engine_of_user($manager);
	    
	    $query = "INSERT INTO events (uuid, date, start_time, end_time, type, title, comment, engine, hash, manager)
		VALUES ('" . $uuid . "', '" . $date . "', '" . $start . "', '" . $end . "', '" . $type_uuid . "', '" . $title . "', '" . $comment . "', '" . $engine . "','" . $hash . "', '" . $manager . "')";
	    
	} else {
	    $query = "INSERT INTO events (uuid, date, start_time, end_time, type, title, comment, engine, hash, manager)
		VALUES ('" . $uuid . "', '" . $date . "', '" . $start . "', '" . $end . "', '" . $type_uuid . "', '" . $title . "', '" . $comment . "', NULL,'" . $hash . "', '" . $manager . "')";
	}

	$result = $db->query ( $query );

	if ($result) {
		// echo "New event record created successfully";
		return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
	}
	return false;
}

function insert_staff($event_uuid, $staff) {
	global $db;

	$uuid = getGUID ();
	$query = "INSERT INTO staff (uuid, position, event, user)
		VALUES ('" . $uuid . "', '" . $staff . "', '" . $event_uuid . "', NULL)";

	$result = $db->query ( $query );

	if ($result) {
		// echo "New staff record created successfully";
	    return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function get_public_events() {
    return get_events(null);   
}
    
function get_events($user_uuid) {
	global $db;
	$data = array ();
	
	$engine = get_engine_of_user($user_uuid);

	$result = $db->query ( "SELECT * FROM events WHERE engine IS NULL OR engine = '" . $engine . "'" );

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

function get_staff($event_uuid) {
	global $db;
	$data = array ();
	$result = $db->query ( "SELECT * FROM staff WHERE event = '" . $event_uuid . "'" );

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

function get_event($event_uuid) {
	global $db;
	$result = $db->query ( "SELECT * FROM events WHERE uuid = '" . $event_uuid . "'" );

	if ($result) {
		return $result->fetch_object ();
	} else {
		// echo "UUID not found";
	}
}

function add_staff_user($uuid, $user) {
	global $db;
	$query = "UPDATE staff SET user = '" . $user . "' WHERE uuid = '" . $uuid . "'";
	$result = $db->query ( $query );

	if ($result) {
		// echo "Record ".$uuid." updated successfully";
		return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function remove_staff_user($uuid) {
	global $db;
	$query = "UPDATE staff SET user = NULL WHERE uuid='" . $uuid . "'";
	$result = $db->query ( $query );

	if ($result) {
		// echo "Record ".$uuid." updated successfully";
	    return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
	    return false;
	}
}

function publish_event($uuid){
    global $db;
    $query = "UPDATE events SET engine = NULL WHERE uuid='" . $uuid . "'";
    $result = $db->query ( $query );
    
    if ($result) {
        // echo "Record ".$uuid." updated successfully";
        return true;
    } else {
        // echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function delete_event($uuid) {
	global $db;
	$query = "DELETE FROM staff WHERE event='" . $uuid . "'";
	$result1 = $db->query ( $query );

	$query = "DELETE FROM events WHERE uuid='" . $uuid . "'";
	$result2 = $db->query ( $query );

	if ($result1 && $result2) {
		// echo "Record ".$uuid." removed successfully";
	    return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function create_table_events() {
	global $db;
	$query = "CREATE TABLE events (
                          uuid CHARACTER(32) NOT NULL,
						  date DATE NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME NOT NULL,
                          type CHARACTER(32) NOT NULL,
						  title VARCHAR(96) NOT NULL,
						  comment VARCHAR(255),
                          engine CHARACTER(32),
						  hash VARCHAR(64) NOT NULL,
						  manager CHARACTER(32) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (manager) REFERENCES user(uuid),
						  FOREIGN KEY (type) REFERENCES eventtypes(uuid)
                          )";

	$result = $db->query ( $query );

	if ($result) {
		// echo "Table created<br>";
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

function create_table_staff() {
	global $db;
	$query = "CREATE TABLE staff (
						  uuid CHARACTER(32) NOT NULL,
                          position VARCHAR(64) NOT NULL,
                          event CHARACTER(32) NOT NULL,
						  user CHARACTER(32),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid),
						  FOREIGN KEY (event) REFERENCES events(uuid)
                          )";

	$result = $db->query ( $query );

	if ($result) {
		// echo "Table created<br>";
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

?>