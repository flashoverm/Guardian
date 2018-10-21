<?php
require_once 'db_connect.php';
require_once 'mail.php';
require_once 'password.php';
require_once 'db_engines.php';

create_table_user ();

function insert_user($firstname, $lastname, $email, $engine_uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE firstname = ? AND lastname = ? AND email = ? AND engine = ?");
	$statement->bind_param('ssss', $firstname, $lastname, $email, $engine_uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			return $data->uuid;
		}
	}

	$uuid = getGUID ();
	
	$statement = $db->prepare("INSERT INTO user (uuid, firstname, lastname, email, password, isadmin, ismanager, loginenabled, engine) VALUES (?, ?, ?, ?, NULL, FALSE, FALSE, FALSE, ?)");
	$statement->bind_param('sssss', $uuid, $firstname, $lastname, $email, $engine_uuid);
	
	$result = $statement->execute();

	if ($result) {
		// echo "New record created successfully";
	    return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function insert_manager($firstname, $lastname, $email, $password, $engine_uuid) {
	global $db;
	$uuid = getGUID ();
	$pwhash = password_hash ( $password, PASSWORD_DEFAULT );
	
	$statement = $db->prepare("INSERT INTO user (uuid, firstname, lastname, email, password, isadmin, ismanager, loginenabled, engine) VALUES (?, ?, ?, ?, ?, FALSE, TRUE, TRUE, ?)");
	$statement->bind_param('ssssss', $uuid, $firstname, $lastname, $email, $pwhash, $engine_uuid);
	
	$result = $statement->execute();

	if ($result) {
		// echo "New record created successfully";
	    return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function insert_admin($firstname, $lastname, $email, $password, $engine_uuid) {
	global $db;
	$uuid = getGUID ();
	$pwhash = password_hash ( $password, PASSWORD_DEFAULT );
	
	$statement = $db->prepare("INSERT INTO user (uuid, firstname, lastname, email, password, isadmin, ismanager, loginenabled, engine) VALUES (?, ?, ?, ?, ?, TRUE, TRUE, TRUE, ?)");
	$statement->bind_param('ssssss', $uuid, $firstname, $lastname, $email, $pwhash, $engine_uuid);
	
	$result = $statement->execute();

	if ($result) {
		// echo "New record created successfully";
	    return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function get_all_manager() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user WHERE ismanager = TRUE");
	
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

function get_manager_except_engine($engine_uuid){
	global $db;
	$data = array ();
		
	$statement = $db->prepare("SELECT * FROM user WHERE ismanager = TRUE AND NOT engine = ?");
	$statement->bind_param('s', $engine_uuid);
	
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

function get_manager_of_engine($engine_uuid) {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user WHERE ismanager = TRUE AND engine = ?");
	$statement->bind_param('s', $engine_uuid);
	
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

function get_user($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE uuid = ?");
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

function get_engine_of_user($user_uuid){
    global $db;
    
    $statement = $db->prepare("SELECT engine FROM user WHERE uuid = ?");
    $statement->bind_param('s', $user_uuid);
    
    if ($statement->execute()) {
    	$result = $statement->get_result();
    	
    	if (mysqli_num_rows ( $result )) {
            $data = $result->fetch_row ();
            $result->free ();
            return $data[0];
        }
    }
    return false;
}

function email_in_use($email) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE (isadmin = TRUE OR ismanager = TRUE) AND email = ?");
	$statement->bind_param('s', $email);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
    		return true;
    	}
	}
	return false;
}

function is_admin($uuid) {
	global $db;
	
	$statement = $db->prepare("SELECT isadmin FROM user WHERE isadmin = TRUE AND uuid = ?");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_row ();
			$result->free ();
			return $data [0];
		}
	}
	return FALSE;
}

function login_enabled($email) {
	global $db;
	
	$statement = $db->prepare("SELECT loginenabled FROM user WHERE (isadmin = TRUE OR ismanager = TRUE) AND email = ?");
	$statement->bind_param('s', $email);
		
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_row ();
			$result->free ();
			return $data [0];
		}
	}
	return false;
}

function check_password($email, $password) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE (isadmin = TRUE OR ismanager = TRUE) AND email = ?");
	$statement->bind_param('s', $email);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			if (password_verify ( $password, $data->password )) {
				return $data->uuid;
			}
		}
	}
	return false;
}

function deactivate_manager($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET loginenabled = FALSE WHERE uuid= ?");
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

function reactivate_manager($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET loginenabled = TRUE WHERE uuid= ?");
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

function reset_password($uuid) {
	$password = random_password ();
	$pwhash = password_hash ( $password, PASSWORD_DEFAULT );

	global $db;
	
	$statement = $db->prepare("UPDATE user SET password = ? WHERE uuid = ?");
	$statement->bind_param('ss', $pwhash, $uuid);
	
	$result = $statement->execute();

	if ($result) {
		// echo "Record ".$uuid." updated successfully";
		return $password;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function change_password($uuid, $old_password, $new_passwort) {
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE uuid = ?");
	$statement->bind_param('s', $uuid);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
			$data = $result->fetch_object ();
			$result->free ();
			if (password_verify ( $old_password, $data->password )) {
				$pwhash = password_hash ( $new_passwort, PASSWORD_DEFAULT );
				
				$statement = $db->prepare("UPDATE user SET password = ? WHERE uuid = ?");
				$statement->bind_param('ss', $pwhash, $uuid);
				
				$result = $statement->execute();
				
				if ($result) {
					// echo "Record ".$uuid." updated successfully";
					return true;
				} else {
					// echo "Error: " . $query . "<br>" . $db->error;
				    return false;
				}
			}
		}
	} else {
		 //echo "Error: " . $query . "<br>" . $db->error;
	}
	return false;
}

function create_table_user() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE user (
                          uuid CHARACTER(36) NOT NULL,
						  firstname VARCHAR(64) NOT NULL,
                          lastname VARCHAR(64) NOT NULL,
                          email VARCHAR(96) NOT NULL,
                          password VARCHAR(255),
                          isadmin BOOLEAN NOT NULL,
						  ismanager BOOLEAN NOT NULL,
						  loginenabled BOOLEAN NOT NULL,
						  engine CHARACTER(36) NOT NULL,
                          PRIMARY KEY  (uuid),
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

?>