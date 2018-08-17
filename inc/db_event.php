<?php

require_once 'inc/db_connect.php';
require_once 'inc/mail.php';

create_table_events();
create_table_staff();

function insert_event($date, $start, $end, $type_uuid, $title, $comment, $manager){
	global $db;

	$uuid = getGUID();
	$hash = hash("sha256", $uuid.$date.$start.$end.$type_uuid.$title);
	$query = "INSERT INTO events (uuid, date, start_time, end_time, type, title, comment, hash, manager)
		VALUES ('".$uuid."', '".$date."', '".$start."', '".$end."', '".$type_uuid."', '".$title."', '".$comment."', '".$hash."', '".$manager."')";
		
	$result = $db->query($query);

	if ($result) {
		//echo "New event record created successfully";
		return $uuid;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
	return FALSE;
}

function insert_staff($event_uuid, $staff){
	global $db;

	$uuid = getGUID();
	$query = "INSERT INTO staff (uuid, position, event, user)
		VALUES ('".$uuid."', '".$staff."', '".$event_uuid."', NULL)";
		
	$result = $db->query($query);

	if ($result) {
		//echo "New staff record created successfully";
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
}

function get_events(){
	global $db;
	$data = array();
	$result = $db->query("SELECT * FROM events");
	
	if ($result) {
		if (mysqli_num_rows($result)) {
			while($date = $result->fetch_object()) {
				$data[] = $date;
			}
			$result->free();
		}   
	}
	return $data;
}

function get_staff($event_uuid){
	global $db;
	$data = array();
	$result = $db->query("SELECT * FROM staff WHERE event = '".$event_uuid."'");
	
	if ($result) {
		if (mysqli_num_rows($result)) {
			while($date = $result->fetch_object()) {
				$data[] = $date;
			}
			$result->free();
		}   
	}
	return $data;
}

function get_event($uuid){
	global $db;
	$result = $db->query("SELECT * FROM events WHERE uuid = '".$uuid."'");
	
	if ($result) {
		return $result->fetch_object();  
	} else {
		//echo "UUID not found";
	}
}

function add_staff_user($uuid, $user){
	global $db;
	$query = "UPDATE staff SET user = '".$user."' WHERE uuid = '".$uuid."'";
	$result = $db->query($query);

	if ($result) {
		//echo "Record ".$uuid." updated successfully";
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
}

function remove_staff_user($uuid){
	global $db;
	$query = "UPDATE staff SET user = NULL WHERE uuid='".$uuid."'";
	$result = $db->query($query);
	
	if ($result) {
		//echo "Record ".$uuid." updated successfully";
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
}

function delete_event($uuid){
	global $db;
	$query = "DELETE FROM staff WHERE event='".$uuid."'";
	$result1 = $db->query($query);
	
	$query = "DELETE FROM events WHERE uuid='".$uuid."'";
	$result2 = $db->query($query);
	
	if ($result1 && $result2) {
		//echo "Record ".$uuid." removed successfully";
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
}

function create_table_events(){
	global $db;
	$query = "CREATE TABLE events (
                          uuid CHARACTER(32) NOT NULL,
						  date DATE NOT NULL,
                          start_time TIME NOT NULL,
                          end_time TIME NOT NULL,
                          type CHARACTER(32) NOT NULL,
						  title VARCHAR(96) NOT NULL,
						  comment VARCHAR(255),
						  hash VARCHAR(64) NOT NULL,
						  manager CHARACTER(32) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (manager) REFERENCES user(uuid),
						  FOREIGN KEY (type) REFERENCES eventtypes(uuid)
                          )";
						  
	$result = $db->query($query);
	
	if($result){
		//echo "Table created<br>";
	} else {
		//echo "Error: " . $db->error . "<br><br>";
	}
}

function create_table_staff(){
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
						  
	$result = $db->query($query);
	
	if($result){
		//echo "Table created<br>";
	} else {
		//echo "Error: " . $db->error . "<br><br>";
	}
}

?>