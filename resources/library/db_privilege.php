<?php 
require_once LIBRARY_PATH . "/db_connect.php";

//Restrictions
define("EVENTMANAGER", "EVENTMANAGER");
define("EVENTADMIN", "EVENTADMIN");

create_table_privilege();
create_table_privilege_user();

function create_privilege($privilege){
	global $db;
	
	$privilege_name = strtoupper($privilege);
	
	$statement = $db->prepare("INSERT INTO privilege (name) VALUES (?)");
	$statement->bind_param('s', $privilege_name);
	
	$result = $statement->execute();
	
	if ($result) {
		return true;		
	} else {
		// echo "Error: " . $query . "<br>" . $db->error;
		return false;
	}
}

function add_privilege_to_user($user_uuid, $privilege){
	
}

function get_all_privileges(){
	global $db;
	$data = array ();
	
	$statement = $db->prepare("SELECT * FROM privilege ORDER BY name");
	
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

function get_users_privileges($user_uuid){
	return get_rights($user_uuid);
}

function user_has_privilege($user_uuid, $privilege){
	return hasRight($user_uuid, $privilege);
}

function current_user_has_privilege($privilege){
	if(isset ($_SESSION ['guardian_userid'])){
		return user_has_privilege($_SESSION ['guardian_userid'], $privilege);
	}
	return false;
}

function remove_privilege_from_user($user_uuid, $privilege){
	
}

function remove_privileges_from_user($user_uuid){
	return true;
}


function create_table_privilege() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE privilege (
						  name VARCHAR(32) NOT NULL,
                          PRIMARY KEY (name)
                          )");
	echo  $db->error;
	$result = $statement->execute();
	
	if ($result) {
		create_privilege(EVENTMANAGER);
		create_privilege(EVENTADMIN);
		// echo "Table created<br>";
		return true;
	} else {
		// echo "Error: " . $db->error . "<br><br>";
		return false;
	}
}

function create_table_privilege_user() {
	global $db;
	
	$statement = $db->prepare("CREATE TABLE privilege_user (
						  privilege VARCHAR(32) NOT NULL,
						  user CHARACTER(36) NOT NULL,
                          PRIMARY KEY (privilege, user),
						  FOREIGN KEY (privilege) REFERENCES privilege(name),
						  FOREIGN KEY (user) REFERENCES user(uuid)
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



/**
 * Old right methods
 */

function get_rights($user_uuid){
	global $db;
	$statement = $db->prepare("SELECT rights FROM user WHERE uuid = ?");
	$statement->bind_param('s', $user_uuid);
	
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

?>