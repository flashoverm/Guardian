<?php
require_once 'db_connect.php';
require_once 'mail.php';
require_once 'db_user.php';

create_table_event ();
create_table_staff ();

function insert_event($date, $start, $end, $type_uuid, $type_other, $title, $comment, $engine, $creator, $published) {
	global $db;

	$uuid = getGUID ();
	$hash = hash ( "sha256", $uuid . $date . $start . $end . $type_uuid . $title );

	if($published){
	    
	    $statement = $db->prepare("INSERT INTO event (uuid, date, start_time, end_time, type, type_other, title, comment, engine, creator, published, hash)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, ?)");
	    $statement->bind_param('sssssssssss', $uuid, $date, $start, $end, $type_uuid, $type_other, $title, $comment, $engine, $creator, $hash);
	    
	} else {
	    
	    $statement = $db->prepare("INSERT INTO event (uuid, date, start_time, end_time, type, type_other, title, comment, engine, creator, published, hash)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE, ?)");
	    $statement->bind_param('sssssssssss', $uuid, $date, $start, $end, $type_uuid, $type_other, $title, $comment, $engine, $creator, $hash);
	    
	}

	$result = $statement->execute();
	
	if ($result) {
		// echo "New event record created successfully";
		return $uuid;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function insert_staff($event_uuid, $position_uuid) {
	global $db;
	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO staff (uuid, position, event, user) VALUES (?, ?, ?, NULL)");
	$statement->bind_param('sss', $uuid, $position_uuid, $event_uuid);
	
	$result = $statement->execute();

	if ($result) {
		// echo "New staff record created successfully";
	    return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function get_public_events() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM event WHERE published = TRUE ORDER BY date DESC");
	
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
    
function get_events($user_uuid) {
	global $db;
	$data = array ();
	$engine = get_engine_of_user($user_uuid);
	
	$statement = $db->prepare("SELECT * FROM event WHERE engine = ? OR creator = ? ORDER BY date DESC");
	$statement->bind_param('ss', $engine, $user_uuid);
	
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

function get_staff($event_uuid) {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM staff WHERE event = ?");
	$statement->bind_param('s', $event_uuid);
	
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


function get_occupancy($event_uuid){
    
    $staff = get_staff($event_uuid);
    
    $length = sizeof($staff);
    $occupancy = 0;
    foreach ( $staff as $entry ) {
        if($entry->user != NULL){
            $occupancy ++;
        }
    }
    return $occupancy . "/" . $length;
}

function is_event_full($event_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT COUNT(*) AS empty_pos FROM staff WHERE user IS NULL AND event = ?");
	$statement->bind_param('s', $event_uuid);
	
	$result = $statement->execute();
		
	if ($result && $statement->get_result()->fetch_row () [0] == 0) {
		return true;
	} else {
		return false;
	}
}

function get_event($event_uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM event WHERE uuid = ?");
	$statement->bind_param('s', $event_uuid);
	
	$result = $statement->execute();

	if ($result) {
		return $statement->get_result()->fetch_object ();
	} else {
		// echo "UUID not found";
	}
}

function get_events_creator($event_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user, event WHERE event.creator = user.uuid AND event.uuid = ?");
	$statement->bind_param('s', $event_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$creator = $result->fetch_object ();
			$result->free ();
			return $creator;
		}
	}
}

function get_events_staff($event_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user, staff WHERE user.uuid = staff.user AND staff.event = ?");
	$statement->bind_param('s', $event_uuid);
	
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

function get_events_staffposition($staff_uuid){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM staff, staffposition WHERE staff.position = staffposition.uuid AND staff.uuid = ?");
    $statement->bind_param('s', $staff_uuid);
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            $position = $result->fetch_object ();
            $result->free ();
            return $position;
        }
    }
}

function get_staff_user($staff_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user, staff WHERE user.uuid = staff.user AND staff.uuid = ?");
	$statement->bind_param('s', $staff_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$user = $result->fetch_object ();
			$result->free ();
			return $user;
		}
	}
}

function add_staff_user($uuid, $user) {
	global $db;
	
	$statement = $db->prepare("UPDATE staff SET user = ? WHERE uuid = ?");
	$statement->bind_param('ss', $user, $uuid);
	
	$result = $statement->execute();
	
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
	
	$statement = $db->prepare("UPDATE staff SET user = NULL WHERE uuid= ?");
	$statement->bind_param('s', $uuid);
	
	$result = $statement->execute();

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
    
    $statement = $db->prepare("UPDATE event SET published = TRUE WHERE uuid= ?");
    $statement->bind_param('s', $uuid);
    
    $result = $statement->execute();
    
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
	
	$statement = $db->prepare("DELETE FROM staff WHERE event= ?");
	$statement->bind_param('s', $uuid);
	
	$result1 = $statement->execute();
	
	$statement = $db->prepare("DELETE FROM event WHERE uuid= ?");
	$statement->bind_param('s', $uuid);
	
	$result2 = $statement->execute();

	if ($result1 && $result2) {
		// echo "Record ".$uuid." removed successfully";
	    return true;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function create_table_event() {
	global $db;
		
	$statement = $db->prepare("CREATE TABLE event (
                          uuid CHARACTER(36) NOT NULL,
						  date DATE NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME NOT NULL,
                          type CHARACTER(36) NOT NULL,
                          type_other VARCHAR(96),
						  title VARCHAR(96),
						  comment VARCHAR(255),
                          engine CHARACTER(36) NOT NULL,
						  creator CHARACTER(36) NOT NULL,
                          published BOOLEAN NOT NULL,
						  hash VARCHAR(64) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (creator) REFERENCES user(uuid),
						  FOREIGN KEY (type) REFERENCES eventtype(uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
	
	$result = $statement->execute();

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
	
	$statement = $db->prepare("CREATE TABLE staff (
						  uuid CHARACTER(36) NOT NULL,
                          position CHARACTER(36) NOT NULL,
                          event CHARACTER(36) NOT NULL,
						  user CHARACTER(36),
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (user) REFERENCES user(uuid),
						  FOREIGN KEY (event) REFERENCES event(uuid),
                          FOREIGN KEY (position) REFERENCES staffposition(uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
		// echo "Table created<br>";
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

?>