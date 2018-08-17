<?php

require_once 'inc/db_connect.php';
require_once 'inc/mail.php';
require_once 'inc/password.php';

create_table_user();

function insert_user($firstname, $lastname, $email, $engine_uuid){
	global $db;
	
	$query = "SELECT * FROM user 
		WHERE firstname = '".$firstname."' AND lastname = '".$lastname."' 
		AND email = '".$email."' AND engine = '".$engine_uuid."'";
	$result = $db->query($query);
	if ($result) {
		if (mysqli_num_rows($result)) {
			$data = $result->fetch_object();
			$result->free();
			return $data->uuid;
		}   
	}	
	
	$uuid = getGUID();
	$query = "INSERT INTO user (uuid, firstname, lastname, email, password, isadmin, ismanager, loginenabled, engine)
		VALUES ('".$uuid."', '".$firstname."', '".$lastname."', '".$email."', NULL, FALSE, FALSE, FALSE, '".$engine_uuid."')";
		
	$result = $db->query($query);
	
	if ($result) {
		//echo "New record created successfully";
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
	return $uuid;
}

function insert_manager($firstname, $lastname, $email, $password, $engine_uuid){
	global $db;
	$uuid = getGUID();
	$pwhash = password_hash($password, PASSWORD_DEFAULT);
	$query = "INSERT INTO user (uuid, firstname, lastname, email, password, isadmin, ismanager, loginenabled, engine)
		VALUES ('".$uuid."', '".$firstname."', '".$lastname."', '".$email."', '".$pwhash."', FALSE, TRUE, TRUE, '".$engine_uuid."')";
		
	$result = $db->query($query);
	
	if ($result) {
		//echo "New record created successfully";
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
	return $result;
}

function insert_admin($firstname, $lastname, $email, $password, $engine_uuid){
	global $db;
	$uuid = getGUID();
	$pwhash = password_hash($password, PASSWORD_DEFAULT);
	$query = "INSERT INTO user (uuid, firstname, lastname, email, password, isadmin, ismanager, loginenabled, engine)
		VALUES ('".$uuid."', '".$firstname."', '".$lastname."', '".$email."', '".$pwhash."', TRUE, FALSE, TRUE, '".$engine_uuid."')";
		
	$result = $db->query($query);
	
	if ($result) {
		//echo "New record created successfully";
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
	return $result;
}

function get_manager(){
	global $db;
	$data = array();
	$result = $db->query("SELECT * FROM user WHERE ismanager = TRUE");
	
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

function get_user($uuid){
	global $db;
	$query = "SELECT * FROM user WHERE uuid = '".$uuid."'";
	$result = $db->query($query);
	if ($result) {
		if (mysqli_num_rows($result)) {
			$data = $result->fetch_object();
			$result->free();
			return $data;
		}   
	}
	return FALSE;
}

function email_in_use($email){
	global $db;
	$query = "SELECT * FROM user WHERE (isadmin = TRUE OR ismanager = TRUE) AND email = '".$email."'";
	$result = $db->query($query);
	if (mysqli_num_rows($result)) {
		return TRUE;
	}
	return FALSE;
}

function is_admin($uuid){
	global $db;
	$query = "SELECT isadmin FROM user WHERE isadmin = TRUE AND uuid = '".$uuid."'";
	$result = $db->query($query);
	if ($result) {
		if (mysqli_num_rows($result)) {
			$data = $result->fetch_row();
			$result->free();
			return $data[0];
		}
	}
	return FALSE;
}

function login_enabled($email){
	global $db;
	$query = "SELECT loginenabled FROM user WHERE (isadmin = TRUE OR ismanager = TRUE) AND email = '".$email."'";
	$result = $db->query($query);
	if ($result) {
		if (mysqli_num_rows($result)) {
			$data = $result->fetch_row();
			$result->free();
			return $data[0];
		}
	}
	return FALSE;
}

function check_password($email, $password){
	global $db;
	$query = "SELECT * FROM user WHERE (isadmin = TRUE OR ismanager = TRUE) AND email = '".$email."'";
	$result = $db->query($query);
	if ($result) {
		if (mysqli_num_rows($result)) {
			$data = $result->fetch_object();
			$result->free();
			if(password_verify($password, $data->password)){
				return $data->uuid;
			}
		}   
	}
	return FALSE;
}

function deactivate_manager($uuid){
	global $db;
	$query = "UPDATE user SET loginenabled = FALSE WHERE uuid='".$uuid."'";
	$result = $db->query($query);
	
	if ($result) {
		//echo "Record ".$uuid." updated successfully";
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
}

function reactivate_manager($uuid){
	global $db;
	$query = "UPDATE user SET loginenabled = TRUE WHERE uuid='".$uuid."'";
	$result = $db->query($query);
	
	if ($result) {
		//echo "Record ".$uuid." updated successfully";
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
}

function reset_password($uuid){
	$password = random_password();
	$pwhash = password_hash($password, PASSWORD_DEFAULT);
	
	global $db;
	$query = "UPDATE user SET password = '".$pwhash."' WHERE uuid = '".$uuid."'";
	$result = $db->query($query);
	if ($result) {
		//echo "Record ".$uuid." updated successfully";
		return $password;
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
		return FALSE;
	}
}

function change_password($uuid, $old_password, $new_passwort){
	global $db;
	$query = "SELECT * FROM user WHERE uuid = '".$uuid."'";
	$result = $db->query($query);
	if ($result) {
		if (mysqli_num_rows($result)) {
			$data = $result->fetch_object();
			$result->free();
			if(password_verify($old_password, $data->password)){
				$pwhash = password_hash($new_passwort, PASSWORD_DEFAULT);
				$query = "UPDATE user SET password = '".$pwhash."' WHERE uuid = '".$uuid."'";
				$result = $db->query($query);
				if ($result) {
					//echo "Record ".$uuid." updated successfully";
					return TRUE;
				} else {
					//echo "Error: " . $query . "<br>" . $db->error;
					return FALSE;
				}
			}
		}   
	} else {
		//echo "Error: " . $query . "<br>" . $db->error;
	}
	return FALSE;
}

function create_table_user(){
	global $db;
	$query = "CREATE TABLE user (
                          uuid CHARACTER(32) NOT NULL,
						  firstname VARCHAR(64) NOT NULL,
                          lastname VARCHAR(64) NOT NULL,
                          email VARCHAR(96) NOT NULL,
                          password VARCHAR(255),
                          isadmin BOOLEAN NOT NULL,
						  ismanager BOOLEAN NOT NULL,
						  loginenabled BOOLEAN NOT NULL,
						  engine CHARACTER(32) NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (engine) REFERENCES engines(uuid)
                          )";
						  
	$result = $db->query($query);
	
	if($result){
		//echo "Table created<br>";
	} else {
		//echo "Error: " . $db->error . "<br><br>";
	}
}

?>