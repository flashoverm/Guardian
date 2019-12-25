<?php
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/password.php";
require_once LIBRARY_PATH . "/mail.php";
require_once LIBRARY_PATH . "/db_connect.php";

create_table_user ();

function insert_user($firstname, $lastname, $email, $engine_uuid) {
	global $db;

	$uuid = getGUID ();
	$mail = strtolower($email);
	
	$statement = $db->prepare("INSERT INTO user (uuid, firstname, lastname, email, password, engine, rights, loginenabled, available) 
		VALUES (?, ?, ?, ?, NULL, ?, NULL, FALSE, TRUE)");
	$statement->bind_param('sssss', $uuid, $firstname, $lastname, $mail, $engine_uuid);
	
	$result = $statement->execute();

	if ($result) {
		
		$statement = $db->prepare("SELECT * FROM user WHERE uuid = ?");
		$statement->bind_param('s', $uuid);
		$statement->execute();
		$data = $statement->get_result()->fetch_object ();
		return $data;
		
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function insert_manager($firstname, $lastname, $email, $password, $engine_uuid) {
	global $db;
	$uuid = getGUID ();
	$pwhash = password_hash ( $password, PASSWORD_DEFAULT );
	$mail = strtolower($email);
	$rights[] = EVENTMANAGER;
	$rightsJson = json_encode($rights);
		
	$statement = $db->prepare("INSERT INTO user (uuid, firstname, lastname, email, password, engine, rights, loginenabled, available) 
		VALUES (?, ?, ?, ?, ?, ?, ?, TRUE, TRUE)");
	$statement->bind_param('sssssss', $uuid, $firstname, $lastname, $mail, $pwhash, $engine_uuid, $rightsJson);
	
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
	$mail = strtolower($email);
	$rights[] = EVENTADMIN;
	$rightsJson = json_encode($rights);
	
	$statement = $db->prepare("INSERT INTO user (uuid, firstname, lastname, email, password, engine, rights, loginenabled, available)
		VALUES (?, ?, ?, ?, ?, ?, ?, TRUE, TRUE)");
	$statement->bind_param('sssssss', $uuid, $firstname, $lastname, $mail, $pwhash, $engine_uuid, $rightsJson);
	
	$result = $statement->execute();

	if ($result) {
		// echo "New record created successfully";
	    return $uuid;
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function get_all_user() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user ORDER BY lastname");
	
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

function get_all_available_user() {
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user WHERE available = TRUE ORDER BY lastname");
	
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

function get_all_manager() {
	global $db;
	$data = array ();
	
	$right = '%' . EVENTMANAGER . '%';
	$statement = $db->prepare("SELECT * FROM user WHERE rights LIKE ?");
	$statement->bind_param('s', $right);
	
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

function get_user_of_engine($engine_uuid){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM user WHERE engine = ? AND available = TRUE ORDER BY lastname");
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
	
	$right = '%' . EVENTMANAGER . '%';
	$statement = $db->prepare("SELECT * FROM user WHERE rights LIKE ? AND engine = ?");
	$statement->bind_param('ss', $right, $engine_uuid);
	
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

function get_user_by_data($firstname, $lastname, $email, $engine_uuid){
	global $db;
	
	$statement = $db->prepare("SELECT * FROM user WHERE firstname = ? AND lastname = ? AND email = ? AND engine = ?");
	$statement->bind_param('ssss', $firstname, $lastname, $email, $engine_uuid);
	
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


function get_engine_obj_of_user($user_uuid){
    global $db;
    
    $statement = $db->prepare("SELECT * FROM engine, user WHERE user.engine = engine.uuid AND user.uuid = ?");
    $statement->bind_param('s', $user_uuid);
    
    if ($statement->execute()) {
        
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            $data = $result->fetch_object();
            $result->free ();
            return $data;
        }
    }
    return false;
}

function email_in_use($email) {
	global $db;
	$mail = strtolower($email);
	
	$statement = $db->prepare("SELECT * FROM user WHERE email = ?");
	$statement->bind_param('s', $mail);
	
	if ($statement->execute()) {
		$result = $statement->get_result();
		
		if (mysqli_num_rows ( $result )) {
    		return true;
    	}
	}
	return false;
}

function is_admin($uuid) {
    return hasRight($uuid, EVENTADMIN);
}

function is_manager($uuid) {
    return hasRight($uuid, EVENTMANAGER);
}

function is_manager_of($user_uuid, $engine_uuid){
	global $db;
	
	$right = '%' . EVENTMANAGER . '%';
	$statement = $db->prepare("SELECT * FROM user WHERE rights LIKE ? AND uuid = ? AND engine = ?");
	$statement->bind_param('sss', $right, $user_uuid, $engine_uuid);
	
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
	$mail = strtolower($email);
	
	$statement = $db->prepare("SELECT loginenabled FROM user WHERE email = ?");
	$statement->bind_param('s', $mail);
		
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

function get_rights($uuid){
    global $db;
    $statement = $db->prepare("SELECT rights FROM user WHERE uuid = ?");
    $statement->bind_param('s', $uuid);
    
    if ($statement->execute()) {
        $result = $statement->get_result();
        
        if (mysqli_num_rows ( $result )) {
            $data = $result->fetch_object();
            $result->free ();
            if($data){
                return json_decode($data->rights);
            }
        }
    }
    return false;
}

function hasRight($uuid, $right){
    $rights = get_rights($uuid);
    if($rights){
        if(in_array($right, $rights)){
            return true;
        }
    }
    return false;
}

function userHasRight($right){	
    if(isset ($_SESSION ['guardian_userid'])){
        return hasRight($_SESSION ['guardian_userid'], $right);
    }
    return false;
}

function check_password($email, $password) {
	global $db;
	$mail = strtolower($email);
	
	$statement = $db->prepare("SELECT * FROM user WHERE email = ?");
	$statement->bind_param('s', $mail);
	
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


function deactivate_user($uuid) {
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

function reactivate_user($uuid) {
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

function hide_user($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET available = FALSE WHERE uuid= ?");
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

function show_user($uuid) {
	global $db;
	
	$statement = $db->prepare("UPDATE user SET available = TRUE WHERE uuid= ?");
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

function addRight($uuid, $right){
    global $db;
    
    $rights = get_rights($uuid);
    if($rights){
        if(!in_array ($right, $rights)){
            $rights[] = $right;
        }
    } else {
        $rights = array();
        $rights[] = $right;
    }
    $rightsJson = json_encode($rights);
    
    $statement = $db->prepare("UPDATE user SET rights = ? WHERE uuid = ?");
    $statement->bind_param('ss', $rightsJson, $uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        return true;
    } else {
        //echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function removeRight($uuid, $right){
    global $db;
    
    $rights = get_rights($uuid);
    if($rights && in_array ($right, $rights)){
        $idx = array_search($right, $rights);
        unset($rights[$idx]);
    } else {
        $rights = array();
    }
    
    $rightsJson = json_encode($rights);
    
    $statement = $db->prepare("UPDATE user SET rights = ? WHERE uuid = ?");
    $statement->bind_param('ss', $rightsJson, $uuid);
    
    $result = $statement->execute();
    
    if ($result) {
        return true;
    } else {
        //echo "Error: " . $query . "<br>" . $db->error;
        return false;
    }
}

function create_table_user() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE user (
                          uuid CHARACTER(36) NOT NULL,
						  firstname VARCHAR(64) NOT NULL,
                          lastname VARCHAR(64) NOT NULL,
                          email VARCHAR(96) NOT NULL,
                          password VARCHAR(255),
						  engine CHARACTER(36) NOT NULL,
						  rights VARCHAR(255),
						  loginenabled BOOLEAN NOT NULL,
                          available BOOLEAN NOT NULL,
                          PRIMARY KEY  (uuid),
						  FOREIGN KEY (engine) REFERENCES engine(uuid)
                          )");
	
	$result = $statement->execute();

	if ($result) {
		// echo "Table created<br>";
		insert_admin("Admin", "Admin", "admin@guardian.de", "admin", "2BAA144B-F946-1524-E60E-7DD485FE1881");
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

?>